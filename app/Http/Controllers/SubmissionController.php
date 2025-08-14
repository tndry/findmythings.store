<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class SubmissionController extends Controller
{
    public function userIndex(Request $request)
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return redirect(front_route('login.index'))->with('error', 'Anda harus login untuk melihat submission.');
        }

        $query = Submission::where('user_id', $customer->id);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Definisikan filter statuses untuk dropdown di view (hanya key status)
        $filter_statuses = ['pending', 'approved', 'rejected', 'revision_needed', 'sold'];

        return view('submissions.user_index', compact('submissions', 'filter_statuses'));
    }

    public function edit(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            return redirect('/id/account/titipan')->with('error', 'Anda tidak memiliki akses untuk mengedit submission ini.');
        }

        if ($submission->status !== 'revision_needed') {
            return redirect('/id/account/titipan')->with('error', 'Submission hanya bisa diedit jika status "Perlu Revisi". Untuk submission yang disetujui, silakan hubungi admin jika ada masalah.');
        }

        $categories = \InnoShop\Common\Repositories\CategoryRepo::getInstance()->all(['active' => true]);
        
        $parentCategories = $categories->filter(function($category) {
            return $category->parent_id == 0 || is_null($category->parent_id);
        });

        return view('submission.create', [
            'submission' => $submission,
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ]);
    }

    public function update(Request $request, Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            return redirect('/id/account/titipan')->with('error', 'Anda tidak memiliki akses untuk mengedit submission ini.');
        }

        if ($submission->status !== 'revision_needed') {
            return redirect('/id/account/titipan')->with('error', 'Submission hanya bisa diedit jika status "Perlu Revisi".');
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:191',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'submitter_whatsapp' => 'required|string|max:20',
            'description' => 'required|string|max:1000',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $imagePaths = [];

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
            
            $maxFiles = min(count($uploadedFiles), 3);
            
            for ($i = 0; $i < $maxFiles; $i++) {
                $file = $uploadedFiles[$i];
                
                if ($file->isValid() && $file->getSize() <= 4 * 1024 * 1024) {
                    try {
                        // Use ImageService to compress the uploaded image
                        $compressedPath = \InnoShop\Common\Services\ImageService::compressUpload(
                            $file->getRealPath(),
                            'submissions',
                            1200, // max width
                            1200, // max height
                            85    // quality (85% for good balance of quality/size)
                        );
                        $imagePaths[] = $compressedPath;
                    } catch (\Exception $e) {
                        // Fallback to regular upload if compression fails
                        \Log::warning("Image compression failed, using regular upload: " . $e->getMessage());
                        $path = $file->store('submissions', 'public');
                        $imagePaths[] = $path;
                    }
                }
            }
        } else {
            $imagePaths = json_decode($submission->images, true) ?? [];
        }

        $attributes = [];
        if ($request->filled('kondisi')) {
            $attributes['kondisi'] = $request->kondisi;
        }

        $submission->update([
            'product_name'         => $request->product_name,
            'category_id'          => $request->category_id,
            'price'                => $request->price,
            'submitter_whatsapp'   => $request->submitter_whatsapp,
            'description'          => $request->description,
            'attributes'           => !empty($attributes) ? json_encode($attributes) : null,
            'images'               => !empty($imagePaths) ? json_encode($imagePaths) : null,
            'status'               => 'pending',
        ]);

        return redirect('/id/account/titipan')->with('success', __('front/submission.update_success'));
    }

    public function create()
    {
        $parentCategories = \InnoShop\Common\Models\Category::where(function ($query){
            $query->whereNull('parent_id')->orWhere('parent_id', 0);
        })->where('active', true)->get();

        return view('submission.create', [
            'parentCategories'=> $parentCategories,
        ]);
    }

    public function getSubcategories(\InnoShop\Common\Models\Category $category)
    {
        $subcategories = $category->children()->where('active', true)->get();
        
        // Transform the data to include proper name for frontend
        $transformedSubcategories = $subcategories->map(function($subcategory) {
            return [
                'id' => $subcategory->id,
                'name' => $subcategory->fallbackName(),
            ];
        });
        
        return response()->json($transformedSubcategories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:191',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'submitter_whatsapp' => 'required|string|max:20',
            'description' => 'required|string|max:1000',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = auth('customer')->user();
        if (!$customer) {
            return redirect(front_route('login.index'))->with('error', 'Anda harus login untuk menitipkan barang.');
        }
        
        $imagePaths = [];

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
            
            $maxFiles = min(count($uploadedFiles), 3);
            
            for ($i = 0; $i < $maxFiles; $i++) {
                $file = $uploadedFiles[$i];
                
                if ($file->isValid() && $file->getSize() <= 4 * 1024 * 1024) {
                    try {
                        // Use ImageService to compress the uploaded image
                        $compressedPath = \InnoShop\Common\Services\ImageService::compressUpload(
                            $file->getRealPath(),
                            'submissions',
                            1200, // max width
                            1200, // max height
                            85    // quality (85% for good balance of quality/size)
                        );
                        $imagePaths[] = $compressedPath;
                    } catch (\Exception $e) {
                        // Fallback to regular upload if compression fails
                        \Log::warning("Image compression failed, using regular upload: " . $e->getMessage());
                        $path = $file->store('submissions', 'public');
                        $imagePaths[] = $path;
                    }
                }
            }
        }

        $attributes = [];
        if ($request->filled('kondisi')) {
            $attributes['kondisi'] = $request->kondisi;
        }

        $submission = Submission::create([
            'product_name'         => $request->product_name,
            'category_id'          => $request->category_id,
            'price'                => $request->price,
            'submitter_whatsapp'   => $request->submitter_whatsapp,
            'description'          => $request->description,
            'attributes'           => !empty($attributes) ? json_encode($attributes) : null,
            'images'               => !empty($imagePaths) ? json_encode($imagePaths) : null,
            'status'               => 'pending',
            'user_id'              => $customer->id,
        ]);

        return redirect()->back()->with('success', 'Submission berhasil dikirim! Kami akan meninjau pengajuan Anda dalam 1-2 hari kerja.');
    }

    public function markAsSold(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            return redirect('/id/account/titipan')->with('error', 'Anda tidak memiliki akses untuk mengubah submission ini.');
        }

        $submission->update(['status' => 'sold']);

        if ($submission->product) {
            $submission->product->update(['active' => 0]);
        }

        return redirect('/id/account/titipan')->with('success', 'Produk berhasil ditandai sebagai terjual dan tidak lagi tampil di toko.');
    }
}