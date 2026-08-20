<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('variants')
            ->latest()
            ->whereNull('deleted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(10);

        $statuses = [
            'active',
            'inactive',
            'draft',
        ];

        return view('products.index', compact(
            'products',
            'statuses'
        ));
    }

    public function create()
    {
        $tags = Tag::all();

        $statuses = [
            'active',
            'inactive',
            'draft',
        ];

        return view('products.create', compact(
            'tags',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string|max:2000',

            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',

            'primary_image' => 'nullable|string',

            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',

            'status' => 'required|in:active,inactive,draft',

            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',

            'variants' => 'required|array|min:1',

            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $imagePaths = [];
            $uploadedImages = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {

                    $imageName = time()
                        . '_'
                        . uniqid()
                        . '.'
                        . $image->getClientOriginalExtension();

                    $image->move(
                        public_path('images'),
                        $imageName
                    );

                    $path = 'images/' . $imageName;

                    $imagePaths[$key] = $path;
                    $uploadedImages[$key] = $path;
                }
            }

            /*
             * Determine primary image.
             */
            $primaryImage = null;

            if ($request->filled('primary_image')) {

                $primarySelection = $request->primary_image;

                if (isset($uploadedImages[$primarySelection])) {
                    $primaryImage = $uploadedImages[$primarySelection];
                }
            }

            /*
             * If no primary image was selected,
             * automatically use the first uploaded image.
             */
            if (!$primaryImage && !empty($uploadedImages)) {
                $primaryImage = reset($uploadedImages);
            }

            $product = Product::create([
                'name' => $request->name,
                'details' => $request->details,
                'images' => array_values($imagePaths),
                'primary_image' => $primaryImage,
                'size' => $request->size,
                'color' => $request->color,
                'category' => $request->category,
                'price' => $request->price,
                'status' => $request->status,
                'tag_ids' => $request->tags ?? [],
            ]);

            /*
             * Store repeater variants.
             */
            foreach ($request->variants as $variant) {

                $product->variants()->create([
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');

        $tags = Tag::all();

        $statuses = [
            'active',
            'inactive',
            'draft',
        ];

        return view('products.edit', compact(
            'product',
            'tags',
            'statuses'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string|max:2000',

            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',

            'primary_image' => 'nullable|string',

            'delete_images' => 'nullable|array',

            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',

            'status' => 'required|in:active,inactive,draft',

            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',

            'variants' => 'required|array|min:1',

            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $product) {

            $finalImages = $product->images ?? [];

            /*
             * Delete selected existing images.
             */
            if ($request->has('delete_images')) {

                foreach ($request->delete_images as $deleteImage) {

                    if (file_exists(public_path($deleteImage))) {
                        unlink(public_path($deleteImage));
                    }

                    $finalImages = array_values(
                        array_filter(
                            $finalImages,
                            fn ($image) => $image !== $deleteImage
                        )
                    );
                }
            }

            /*
             * Upload new images.
             */
            $newImages = [];

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $key => $image) {

                    $imageName = time()
                        . '_'
                        . uniqid()
                        . '.'
                        . $image->getClientOriginalExtension();

                    $image->move(
                        public_path('images'),
                        $imageName
                    );

                    $path = 'images/' . $imageName;

                    $newImages[$key] = $path;
                    $finalImages[] = $path;
                }
            }

            /*
             * Determine primary image.
             */
            $primaryImage = null;

            if ($request->filled('primary_image')) {

                $primarySelection = $request->primary_image;

                /*
                 * Existing image.
                 */
                if (
                    in_array(
                        $primarySelection,
                        $finalImages,
                        true
                    )
                ) {
                    $primaryImage = $primarySelection;
                }

                /*
                 * Newly uploaded image.
                 */
                elseif (isset($newImages[$primarySelection])) {
                    $primaryImage = $newImages[$primarySelection];
                }
            }

            /*
             * If selected primary image was deleted,
             * automatically choose the first remaining image.
             */
            if (!$primaryImage && !empty($finalImages)) {
                $primaryImage = $finalImages[0];
            }

            $product->update([
                'name' => $request->name,
                'details' => $request->details,
                'images' => $finalImages,
                'primary_image' => $primaryImage,
                'size' => $request->size,
                'color' => $request->color,
                'category' => $request->category,
                'price' => $request->price,
                'status' => $request->status,
                'tag_ids' => $request->tags ?? [],
            ]);

            /*
             * Replace existing variants.
             */
            $product->variants()->delete();

            foreach ($request->variants as $variant) {

                $product->variants()->create([
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->images) {

            foreach ($product->images as $image) {

                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}