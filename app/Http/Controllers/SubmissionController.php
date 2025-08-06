<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InnoShop\Front\FrontServiceProvider;
use InnoShop\Front\Middleware\GlobalFrontData;
use Illuminate\Support\Facades\Validator;
use App\Models\Submission; 
use InnoShop\Common\Repositories\CategoryRepo;
use Illuminate\Support\Facades\Auth;
use InnoShop\Common\Models\Product;



class SubmissionController extends Controller
{
    /**
     * Menampilkan halaman formulir titip jual.
     */
    public function create()
{
    $paginator = \InnoShop\Common\Repositories\CategoryRepo::getInstance()->list(['active' => true]);
    $categories = $paginator->items();
    // dd($categories);
    

    return view('submission.create', [
        'categories' => $categories
    ]);
}
    /**
     * Menyimpan data dari formulir.
     * (Logika ini akan kita isi nanti)
     * 
     * 
     */
// Versi final dan sudah benar
public function store(Request $request)
{
    // 1. Validasi Data
    $validator = Validator::make($request->all(), [
        'product_name' => 'required|string|max:191',
        'description' => 'required|string',
        'category_id' => 'required|integer',
        'price' => 'required|numeric|min:0',
        'submitter_whatsapp' => 'required|string|max:20', // <-- PERBAIKAN: Menambahkan validasi WA
        'images' => 'required|array|max:3',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:4096', // 4MB per foto
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $customer = auth('customer')->user();
    if (!$customer) {
        return redirect()->route('login.index')->with('error', 'Anda harus login untuk menitipkan barang.');
    }
    
    $imagePaths = [];

    // 2. Proses Upload Foto
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            // Menyimpan file ke storage/app/public/submissions dan mendapatkan path-nya
            $path = $file->store('submissions', 'public');
            $imagePaths[] = $path;
        }
    }

    // 3. Simpan ke Database
    \App\Models\Submission::create([
        'user_id'              => $customer->id,
        'submitter_whatsapp'   => $request->input('submitter_whatsapp'),
        'product_name'         => $request->input('product_name'),
        'description'          => $request->input('description'),
        'price'                => $request->input('price'),
        'category_id'          => $request->input('category_id'),
        'images'               => json_encode($imagePaths), // Menyimpan path gambar sebagai JSON
        'status'               => 'pending',
    ]);

    return redirect()->back()->with('success', 'Barang Anda telah berhasil dikirim dan akan direview oleh Admin!');
}

public function userIndex(Request $request)
    {
        // Daftar status yang akan kita gunakan sebagai Tab
        $filter_statuses = ['pending', 'revision_needed', 'approved', 'rejected'];

        // Ambil input dari URL untuk filter status dan pencarian
        $status_filter = $request->query('status');
        $search_query = $request->query('search');

        // Query dasar untuk mengambil submission milik user yang login
        $query = Submission::where('user_id', Auth::id());

        // Terapkan filter jika ada
        if ($status_filter) {
            $query->where('status', $status_filter);
        }

        // Terapkan pencarian jika ada
        if ($search_query) {
            $query->where('product_name', 'like', '%' . $search_query . '%');
        }

        // Ambil data, urutkan dari yang terbaru, dan paginasi
        $submissions = $query->latest()->paginate(10);

        // Kirim semua data yang dibutuhkan ke view
        return view('submissions.user_index', compact('submissions', 'filter_statuses'));
    }

    public function edit(Submission $submission)
    {
        // Keamanan: Pastikan hanya pemilik yang bisa mengedit
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // Ambil data kategori untuk dropdown
        $paginator = \InnoShop\Common\Repositories\CategoryRepo::getInstance()->list(['active' => true]);
        $categories = $paginator->items();
        

        

        // Tampilkan view create, tapi kirim data submission yang ada
        return view('submission.create', compact('submission', 'categories'));
    }

    public function update(Request $request, Submission $submission)
    {
        // Keamanan: Pastikan hanya pemilik yang bisa mengupdate
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // Validasi data (sama seperti saat membuat baru)
        $request->validate([
            'product_name' => 'required|string|max:191',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'submitter_whatsapp' => 'required|string|max:20',
            'images' => 'nullable|array|max:3', // Gambar boleh dikosongkan jika tidak ingin diubah
            'images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        // Kumpulkan data untuk diupdate
        $updateData = $request->only(['product_name', 'description', 'category_id', 'price', 'submitter_whatsapp']);

        // Proses gambar jika ada yang diupload ulang
        if ($request->hasFile('images')) {
            // (Untuk saat ini kita hapus gambar lama dan ganti baru, bisa disempurnakan nanti)
            $imagePaths = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('submissions', 'public');
                $imagePaths[] = $path;
            }
            $updateData['images'] = json_encode($imagePaths);
        }
        
        // PENTING: Ubah status kembali menjadi 'Menunggu Persetujuan'
        $updateData['status'] = 'pending';
        $updateData['admin_notes'] = null; // Hapus catatan admin setelah direvisi

        // Update data di database
        $submission->update($updateData);

        return redirect(account_route('submissions.index'))->with('success', 'Titipan Anda berhasil diperbarui dan akan direview kembali oleh Admin.');
    }

    public function markAsSold(Submission $submission)
    {
        // Keamanan: Pastikan hanya pemilik yang bisa mengubah status
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // 1. Ubah status submission
        $submission->status = 'sold';
        $submission->save();

        // 2. Cari produk yang terhubung dan nonaktifkan
        $product = Product::where('submission_id', $submission->id)->first();
        if ($product) {
            $product->active = 0; // Menonaktifkan produk
            $product->save();
        }

        return redirect(account_route('submissions.index'))->with('success', 'Produk telah ditandai sebagai terjual.');
    }
}