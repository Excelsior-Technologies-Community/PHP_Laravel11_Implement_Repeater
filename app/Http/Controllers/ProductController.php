<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::latest()->whereNull('deleted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(10);
        $statuses = ['active', 'inactive', 'draft'];

        return view('products.index', compact('products', 'statuses'));
    }

    public function create()
    {
        $tags = Tag::all();
        $statuses = ['active', 'inactive', 'draft'];
        return view('products.create', compact('tags', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string|max:2000',
            'size'      => 'required|string|max:50',
            'color'     => 'required|string|max:50',
            'category'  => 'required|string|max:100',
            'price'     => 'required|numeric|min:0',
            'status'    => 'required|in:active,inactive,draft',
            'images.*'  => 'nullable|image|max:2048',
            'tags'      => 'nullable|array',
            'tags.*'    => 'exists:tags,id',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $imagePaths[] = 'images/' . $imageName;
            }
        }

        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'images'    => $imagePaths,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'status'    => $request->status,
            'tag_ids'   => $request->tags ?? [],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $tags = Tag::all();
        $statuses = ['active', 'inactive', 'draft'];
        return view('products.edit', compact('product', 'tags', 'statuses'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string|max:2000',
            'size'      => 'required|string|max:50',
            'color'     => 'required|string|max:50',
            'category'  => 'required|string|max:100',
            'price'     => 'required|numeric|min:0',
            'status'    => 'required|in:active,inactive,draft',
            'images.*'  => 'nullable|image|max:2048',
            'tags'      => 'nullable|array',
            'tags.*'    => 'exists:tags,id',
        ]);

        $finalImages = $product->images ?? [];

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $delImg) {
                if (file_exists(public_path($delImg))) {
                    unlink(public_path($delImg));
                }
                $finalImages = array_values(array_filter($finalImages, fn($img) => $img !== $delImg));
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $finalImages[] = 'images/' . $imageName;
            }
        }

        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'images'    => $finalImages,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'status'    => $request->status,
            'tag_ids'   => $request->tags ?? [],
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $img) {
                if (file_exists(public_path($img))) {
                    unlink(public_path($img));
                }
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
