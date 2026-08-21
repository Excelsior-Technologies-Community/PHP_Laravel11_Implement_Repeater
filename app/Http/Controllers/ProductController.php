<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Product listing
     *
     * Features:
     * - Search
     * - Status filter
     * - Category filter
     * - Minimum price
     * - Maximum price
     * - Stock filter
     * - Low stock filter
     * - Sorting
     * - Pagination
     */
    public function index(Request $request)
    {
        $query = Product::with('variants')
            ->whereNull('deleted_at');

        /*
         * SEARCH
         *
         * Searches:
         * - Product name
         * - Details
         * - Category
         * - Color
         * - Size
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")

                    /*
                     * Search inside variants
                     */
                    ->orWhereHas('variants', function ($variantQuery) use ($search) {

                        $variantQuery
                            ->where('size', 'like', "%{$search}%")
                            ->orWhere('color', 'like', "%{$search}%");
                    });
            });
        }

        /*
         * STATUS FILTER
         */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
         * CATEGORY FILTER
         */
        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        /*
         * MINIMUM PRICE
         */
        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        /*
         * MAXIMUM PRICE
         */
        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
         * STOCK FILTER
         *
         * all
         * in_stock
         * out_of_stock
         * low_stock
         */
        if ($request->filled('stock_filter')) {

            switch ($request->stock_filter) {

                case 'in_stock':

                    $query->whereHas('variants', function ($q) {

                        $q->where('stock', '>', 0);
                    });

                    break;


                case 'out_of_stock':

                    $query->whereDoesntHave('variants', function ($q) {

                        $q->where('stock', '>', 0);
                    });

                    break;


                case 'low_stock':

                    $lowStock = (int) $request->input(
                        'low_stock',
                        5
                    );

                    $query->whereHas('variants', function ($q) use ($lowStock) {

                        $q->where('stock', '>', 0)
                            ->where('stock', '<=', $lowStock);
                    });

                    break;
            }
        }

        /*
         * SORTING
         */
        $allowedSorts = [
            'name',
            'price',
            'created_at',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (!in_array($sort, $allowedSorts, true)) {

            $sort = 'created_at';
        }

        $direction = $request->input(
            'direction',
            'desc'
        );

        if (!in_array($direction, ['asc', 'desc'], true)) {

            $direction = 'desc';
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
         * PAGINATION
         */
        $products = $query
            ->paginate(5)
            ->withQueryString();

        /*
         * Status options
         */
        $statuses = [
            'active',
            'inactive',
            'draft',
        ];

        /*
         * Category options
         */
        $categories = Product::whereNull('deleted_at')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        /*
         * Total products
         */
        $totalProducts = Product::whereNull('deleted_at')
            ->count();

        /*
         * Active products
         */
        $activeProducts = Product::whereNull('deleted_at')
            ->where('status', 'active')
            ->count();

        /*
         * Out of stock products
         */
        $outOfStockProducts = Product::whereNull('deleted_at')
            ->whereDoesntHave('variants', function ($q) {

                $q->where('stock', '>', 0);
            })
            ->count();

        /*
         * Low stock products
         */
        $lowStockProducts = Product::whereNull('deleted_at')
            ->whereHas('variants', function ($q) {

                $q->where('stock', '>', 0)
                    ->where('stock', '<=', 5);
            })
            ->count();

        return view(
            'products.index',
            compact(
                'products',
                'statuses',
                'categories',
                'totalProducts',
                'activeProducts',
                'outOfStockProducts',
                'lowStockProducts'
            )
        );
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'product_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'product_ids.*' => [
                'integer',
                'exists:products,id',
            ],

            'bulk_status' => [
                'required',
                'in:active,inactive,draft',
            ],
        ]);

        Product::whereIn(
            'id',
            $request->product_ids
        )->update([
            'status' => $request->bulk_status,
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                count($request->product_ids)
                    . ' product(s) status updated successfully.'
            );
    }


    /**
     * Export filtered products to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Product::with('variants')
            ->whereNull('deleted_at');

        /*
         * SEARCH
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")
                    ->orWhereHas('variants', function ($variantQuery) use ($search) {

                        $variantQuery
                            ->where('size', 'like', "%{$search}%")
                            ->orWhere('color', 'like', "%{$search}%");
                    });
            });
        }

        /*
         * STATUS
         */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
         * CATEGORY
         */
        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        /*
         * MIN PRICE
         */
        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        /*
         * MAX PRICE
         */
        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
         * STOCK FILTER
         */
        if ($request->filled('stock_filter')) {

            switch ($request->stock_filter) {

                case 'in_stock':

                    $query->whereHas('variants', function ($q) {

                        $q->where('stock', '>', 0);
                    });

                    break;


                case 'out_of_stock':

                    $query->whereDoesntHave('variants', function ($q) {

                        $q->where('stock', '>', 0);
                    });

                    break;


                case 'low_stock':

                    $lowStock = (int) $request->input(
                        'low_stock',
                        5
                    );

                    $query->whereHas('variants', function ($q) use ($lowStock) {

                        $q->where('stock', '>', 0)
                            ->where('stock', '<=', $lowStock);
                    });

                    break;
            }
        }

        /*
         * SORTING
         */
        $allowedSorts = [
            'name',
            'price',
            'created_at',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (!in_array($sort, $allowedSorts, true)) {

            $sort = 'created_at';
        }

        $direction = $request->input(
            'direction',
            'desc'
        );

        if (!in_array($direction, ['asc', 'desc'], true)) {

            $direction = 'desc';
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
         * CSV download
         */
        $fileName = 'products_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($query) {

            $handle = fopen('php://output', 'w');

            /*
             * CSV header
             */
            fputcsv($handle, [
                'ID',
                'Name',
                'Details',
                'Category',
                'Default Size',
                'Default Color',
                'Price',
                'Status',
                'Tags',
                'Total Variants',
                'Total Stock',
                'Created At',
            ]);

            /*
             * Products
             */
            $query->chunk(100, function ($products) use ($handle) {

                foreach ($products as $product) {

                    $totalStock = $product->variants->sum('stock');

                    $tags = '';

                    if (is_array($product->tag_ids)) {

                        $tags = implode(
                            ', ',
                            $product->tag_ids
                        );
                    }

                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        $product->details,
                        $product->category,
                        $product->size,
                        $product->color,
                        $product->price,
                        ucfirst($product->status),
                        $tags,
                        $product->variants->count(),
                        $totalStock,
                        $product->created_at?->format(
                            'Y-m-d H:i:s'
                        ),
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }




    public function create()
    {
        $tags = Tag::all();

        $statuses = [
            'active',
            'inactive',
            'draft',
        ];

        return view(
            'products.create',
            compact(
                'tags',
                'statuses'
            )
        );
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

                foreach (
                    $request->file('images')
                    as $key => $image
                ) {

                    $imageName =
                        time()
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

            $primaryImage = null;

            if ($request->filled('primary_image')) {

                $primarySelection =
                    $request->primary_image;

                if (
                    isset(
                        $uploadedImages[$primarySelection]
                    )
                ) {

                    $primaryImage =
                        $uploadedImages[$primarySelection];
                }
            }

            if (
                !$primaryImage
                && !empty($uploadedImages)
            ) {

                $primaryImage =
                    reset($uploadedImages);
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
            ->with(
                'success',
                'Product created successfully.'
            );
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

        return view(
            'products.edit',
            compact(
                'product',
                'tags',
                'statuses'
            )
        );
    }


    public function update(
        Request $request,
        Product $product
    ) {
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

        DB::transaction(function () use (
            $request,
            $product
        ) {

            $finalImages =
                $product->images ?? [];

            if (
                $request->has('delete_images')
            ) {

                foreach (
                    $request->delete_images
                    as $deleteImage
                ) {

                    if (
                        file_exists(
                            public_path($deleteImage)
                        )
                    ) {

                        unlink(
                            public_path($deleteImage)
                        );
                    }

                    $finalImages =
                        array_values(
                            array_filter(
                                $finalImages,
                                fn($image) =>
                                $image !== $deleteImage
                            )
                        );
                }
            }

            $newImages = [];

            if ($request->hasFile('images')) {

                foreach (
                    $request->file('images')
                    as $key => $image
                ) {

                    $imageName =
                        time()
                        . '_'
                        . uniqid()
                        . '.'
                        . $image->getClientOriginalExtension();

                    $image->move(
                        public_path('images'),
                        $imageName
                    );

                    $path =
                        'images/' . $imageName;

                    $newImages[$key] = $path;
                    $finalImages[] = $path;
                }
            }

            $primaryImage = null;

            if ($request->filled('primary_image')) {

                $primarySelection =
                    $request->primary_image;

                if (
                    in_array(
                        $primarySelection,
                        $finalImages,
                        true
                    )
                ) {

                    $primaryImage =
                        $primarySelection;
                } elseif (
                    isset(
                        $newImages[$primarySelection]
                    )
                ) {

                    $primaryImage =
                        $newImages[$primarySelection];
                }
            }

            if (
                !$primaryImage
                && !empty($finalImages)
            ) {

                $primaryImage =
                    $finalImages[0];
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
            ->with(
                'success',
                'Product updated successfully.'
            );
    }


    public function destroy(Product $product)
    {
        /*
         * Soft delete product.
         *
         * Images are NOT deleted here.
         */
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product moved to trash successfully.'
            );
    }
}
