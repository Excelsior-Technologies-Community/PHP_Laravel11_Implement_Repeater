<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products (latest first) - basic listing
    public function index()
    {
        // Fetch all products ordered by creation date (newest first)
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    // Display create product form
    public function create()
    {
        // Simple create form - no additional data needed
        return view('products.create');
    }

    // Store new product with MULTIPLE image uploads (REQUIRED)
    public function store(Request $request)
    {
        // Validate form - images.* validates each file in multiple upload
        $request->validate([
            'name'      => 'required',           // Product name required
            'details'   => 'required',           // Description required
            'size'      => 'required',           // Size required
            'color'     => 'required',           // Color required
            'category'  => 'required',           // Category required
            'price'     => 'required|numeric',   // Numeric price required
            'images.*'  => 'required|image|max:2048',  // Each image required, max 2MB
        ]);

        $imagePaths = [];

        // HANDLE MULTIPLE IMAGE UPLOADS
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate unique filename using timestamp + uniqid + original extension
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                // Move each image to public/images folder
                $image->move(public_path('images'), $imageName);
                // Store relative path in array
                $imagePaths[] = 'images/' . $imageName;
            }
        }

        // Create product record with array of image paths
        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'images'    => $imagePaths,          // Array stored directly in DB
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
        ]);

        // Redirect to products list with success message
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Display edit form for specific product
    public function edit(Product $product)
    {
        // Route model binding automatically loads product by ID
        return view('products.edit', compact('product'));
    }

    // Update existing product with advanced image management
    public function update(Request $request, Product $product)
    {
        // Same validation as store, but images optional (nullable)
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'images.*'  => 'nullable|image|max:2048',  // Optional new images
        ]);

        // Start with existing images array (safely handle null)
        $finalImages = $product->images ?? [];

        // DELETE SELECTED IMAGES (from checkboxes in edit form)
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $delImg) {
                // Remove physical file from server
                if (file_exists(public_path($delImg))) {
                    unlink(public_path($delImg));
                }
                // Remove image path from array (preserve keys with array_values)
                $finalImages = array_values(array_filter($finalImages, fn($img) => $img !== $delImg));
            }
        }

        // APPEND NEW IMAGES (add to existing images)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $finalImages[] = 'images/' . $imageName;
            }
        }

        // Update product with final image array
        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'images'    => $finalImages,         // Updated images array
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Permanently delete product and ALL associated images
    public function destroy(Product $product)
    {
        // Delete all product images from server
        if ($product->images) {
            foreach ($product->images as $img) {
                // Remove each physical image file
                if (file_exists(public_path($img))) {
                    unlink(public_path($img));
                }
            }
        }

        // Permanently delete product record from database (hard delete)
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
