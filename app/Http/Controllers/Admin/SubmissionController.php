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
        DB::beginTransaction();

        try {

            $uploadedImageValues = [];

            $uploadService = app(UploadService::class);

            $submissionImages = json_decode($submission->images, true) ?? [];

            foreach ($submissionImages as $imagePathData) {
                $imagePath = $imagePathData; 
                $originalFileName = basename($imagePath);
                $mimeType = mime_content_type(Storage::disk('public')->path($imagePath));
                
                if (Storage::disk('public')->exists($imagePath)) {
                    $fullPathOnDisk = Storage::disk('public')->path($imagePath);
                    // Buat instance UploadedFile dari file yang sudah ada di storage sementara
                    $uploadedFile = new UploadedFile(
                        $fullPathOnDisk,
                        $originalFileName, // Nama file asli yang diunggah user
                        $mimeType,
                        0, 
                        true // Ini adalah "test file", penting untuk Laravel
                    );

                    $uploadResult = $uploadService->uploadForPanel($uploadedFile, 'products');
                    $uploadedImageValues[] = $uploadResult['value'];

                    Storage::disk('public')->delete($imagePath);
            }
        }

            // 2. Buat produk di tabel utama
                $product = Product::create([
                    'submission_id' => $submission->id,
                    'type'      => 'normal',
                    'brand_id'  => 5, // Pastikan ID ini ada di tabel inno_brands
                    // Ubah 'images' menjadi $uploadedImageValues yang didapat dari UploadService
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
                $submission->status = 'approved';
                $submission->save();

                DB::commit();

                return redirect()
                    ->route('panel.submissions.index')
                    ->with('success', 'Produk berhasil disetujui dan diterbitkan!');
            } catch (\Exception $e) {
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