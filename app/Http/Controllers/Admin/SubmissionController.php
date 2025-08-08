<?php

namespace App\Http\Controllers\Admin;

use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InnoShop\Common\Models\Locale; 
use InnoShop\Common\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InnoShop\RestAPI\Services\UploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubmissionStatusUpdate;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan 'tab' sebagai nama parameter di URL, defaultnya 'pending'
        $active_tab = $request->query('tab', 'pending');

        $query = Submission::latest();

        // Logika filter berdasarkan tab yang aktif
        if ($active_tab == 'pending') {
            $query->where('status', 'pending');
        } elseif ($active_tab == 'history') {
            $query->whereIn('status', ['approved', 'rejected', 'revision_needed', 'sold']);
        }

        $submissions = $query->paginate(20);

        // Kirim variabel $active_tab ke view
        return inno_view('panel::submissions.index', compact('submissions', 'active_tab'));
    }

    public function show(Submission $submission)
    {
        return inno_view('panel::submissions.show', compact('submission'));
    }

    public function approve(Submission $submission)
    {
        // PENCEGAHAN DUPLIKASI: Cek apakah submission sudah diapprove atau sudah ada produk
        if ($submission->status === 'approved') {
            return redirect()
                ->route('panel.submissions.index')
                ->withErrors(['error' => 'Submission ini sudah disetujui sebelumnya.']);
        }

        // Cek apakah sudah ada produk yang dibuat untuk submission ini
        $existingProduct = Product::where('submission_id', $submission->id)->first();
        if ($existingProduct) {
            return redirect()
                ->route('panel.submissions.index')
                ->withErrors(['error' => 'Produk untuk submission ini sudah pernah dibuat (ID: ' . $existingProduct->id . ').']);
        }

        DB::beginTransaction();

        try {
            \Log::info("Starting approval process for submission {$submission->id}");

            $uploadedImageValues = [];

            $uploadService = app(UploadService::class);

            $submissionImages = json_decode($submission->images, true) ?? [];

            foreach ($submissionImages as $imagePathData) {
                $imagePath = $imagePathData; 
                $originalFileName = basename($imagePath);
                
                \Log::info("Processing image for submission {$submission->id}: {$imagePath}");
                
                // Check if file exists before processing
                if (!Storage::disk('public')->exists($imagePath)) {
                    \Log::warning("Image file not found for submission {$submission->id}: {$imagePath}");
                    continue; // Skip this image and continue with others
                }
                
                try {
                    // PENDEKATAN LARAVEL: Gunakan Storage Facade untuk semua operasi file
                    $newFileName = 'catalog/products/' . \Str::random(40) . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);

                    // Salin file menggunakan Storage. Ini akan otomatis membuat direktori jika belum ada.
                    if (Storage::disk('public')->copy($imagePath, $newFileName)) {
                        \Log::info("Successfully copied {$imagePath} to {$newFileName}");
                        
                        // Simpan path baru yang relatif terhadap disk 'public'
                        $uploadedImageValues[] = $newFileName;
                        
                        // Hapus file asli
                        Storage::disk('public')->delete($imagePath);
                        \Log::info("Deleted original file: {$imagePath}");
                    } else {
                        \Log::error("Failed to copy {$imagePath} to {$newFileName} using Storage facade.");
                        continue;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing image {$imagePath}: " . $e->getMessage());
                    continue;
                }
            }

                // 2. Buat produk di tabel utama
                \Log::info("Creating product for submission {$submission->id}");
                $product = Product::create([
                    'submission_id' => $submission->id,
                    'type'      => 'normal',
                    'brand_id'  => 5, // Pastikan ID ini ada di tabel inno_brands
                    // Use simple array format like local environment
                    'images'    => $uploadedImageValues,
                    'active'    => 1,
                    'price'     => 0,
                    // FIX FINAL: Inisialisasi 'variables' sebagai array kosong untuk mencegah error
                    'variables' => [],
                    'spu_code'  => 'PRELOVED-' . strtoupper(Str::random(8)),
                    'slug'      => Str::slug($submission->product_name) . '-' . time(),
                ]);

                // 3. Lampirkan kategori
                $product->categories()->attach($submission->category_id);

                // 4. Buat terjemahan untuk SEMUA locale yang aktif
                $activeLocales = Locale::where('active', true)->pluck('code');
                foreach ($activeLocales as $locale) {
                    $product->translations()->create([
                        'locale'  => $locale,
                        'name'    => $submission->product_name,
                        'content' => $submission->description,
                    ]);
                }

                // 5. Buat SKU yang berisi harga dan stok asli
                $product->skus()->create([
                    'price'        => $submission->price,
                    'origin_price' => $submission->price,
                    'quantity'     => 1,
                    'is_default'   => 1,
                    'code'         => 'SKU-' . $product->id,
                ]);

                // 6. Ubah status submission
                \Log::info("Updating submission status to approved for submission {$submission->id}");
                $submission->status = 'approved';
                $submission->save();

                \Log::info("Committing transaction for submission {$submission->id}");
                DB::commit();

                \Log::info("Approval process completed successfully for submission {$submission->id}");
                return redirect()
                    ->route('panel.submissions.index')
                    ->with('success', 'Produk berhasil disetujui dan diterbitkan!');
            } catch (\Exception $e) {
                \Log::error("Approval process failed for submission {$submission->id}: " . $e->getMessage());
                \Log::error("Error location: " . $e->getFile() . ':' . $e->getLine());
                DB::rollBack();
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage() . '. Baris: ' . $e->getLine()]); // Tambahkan getLine() untuk debugging
            }
    }

    /**
 * Menolak submission.
 *
 * @param  \App\Models\Submission  $submission
 * @return \Illuminate\Http\RedirectResponse
 */
    public function reject(Submission $submission)
    {
        $submission->update([
            'status' => 'rejected',
            'admin_notes' => 'Submission ditolak oleh admin.'
        ]);
        //  Kirim email 
        Mail::to($submission->user->email)->send(new SubmissionStatusUpdate($submission));

        return redirect()->route('panel.submissions.index')->with('success', 'Submission telah ditolak.');
    }

/**
 * Meminta revisi untuk submission.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Submission  $submission
 * @return \Illuminate\Http\RedirectResponse
 */
    public function requestRevision(Request $request, Submission $submission)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10',
        ]);

        $submission->update([
            'status' => 'revision_needed',
            'admin_notes' => $request->input('admin_notes')
        ]);
        // Kirim email 
        Mail::to($submission->user->email)->send(new SubmissionStatusUpdate($submission));

        return redirect()->route('panel.submissions.index')->with('success', 'Permintaan revisi telah dikirimkan ke pengguna.');
    }   
}