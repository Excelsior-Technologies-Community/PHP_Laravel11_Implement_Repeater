 # PHP_Laravel11_Implement_Repeater

This documentation explains how to build a complete Product CRUD system in Laravel 11 with:

- Multiple image upload using a repeater
- JSON image storage in database
- Add new images
- Delete selected images
- Update product
- Delete product and remove all related images

This includes full migration, model, controller, and blade files.

---

# Step 1: Install Laravel 11

Run the following command to create a new Laravel project:

```
composer create-project laravel/laravel example-app
```

---

# Step 2: Configure MySQL Database

Update your `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=root
```

---

# Step 3: Create Products Migration

Create migration:

```
php artisan make:migration create_products_table --create=products
```

Add the following fields:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('details');
    $table->decimal('price', 8, 2);
    $table->string('size');
    $table->string('color');
    $table->string('category');
    $table->string('image')->nullable();
    $table->timestamps();
});
```

---

# Step 3 (Part-2): Add JSON Column for Multiple Images

Create new migration:

```
php artisan make:migration add_images_to_products_table --table=products
```

Inside migration:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->json('images')->nullable();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('images');
    });
}
```

Run migration:

```
php artisan migrate
```

---

# Step 4: Add Resource Route

In `routes/web.php`:

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

---

# Step 5: Create Controller & Update Model

Create controller + model:

```
php artisan make:controller ProductController --resource --model=Product
```

### Product Model

```php
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'details',
        'images',  
        'size',
        'color',
        'category',
        'price',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
```

---

# Step 6: ProductController Logic (CRUD + Multiple Images)

## Display All Products

```php
public function index()
{
    $products = Product::latest()->get();
    return view('products.index', compact('products'));
}
```

---

## Store Product (Multiple Image Upload)

```php
public function store(Request $request)
{
    $request->validate([
        'name'      => 'required',
        'details'   => 'required',
        'size'      => 'required',
        'color'     => 'required',
        'category'  => 'required',
        'price'     => 'required|numeric',
        'images.*'  => 'required|image|max:2048',
    ]);

    $imagePaths = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {

            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('images'), $imageName);

            $imagePaths[] = 'images/'.$imageName;
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
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}
```

---

## Edit Product Page

```php
public function edit(Product $product)
{
    return view('products.edit', compact('product'));
}
```

---

## Update Product (Remove Old Images + Add New Ones)

```php
public function update(Request $request, Product $product)
{
    $request->validate([
        'name'      => 'required',
        'details'   => 'required',
        'size'      => 'required',
        'color'     => 'required',
        'category'  => 'required',
        'price'     => 'required|numeric',
        'images.*'  => 'nullable|image|max:2048',
    ]);

    $finalImages = $product->images ?? [];

    // Delete selected images
    if ($request->has('delete_images')) {
        foreach ($request->delete_images as $delImg) {
            if (file_exists(public_path($delImg))) {
                unlink(public_path($delImg));
            }
            $finalImages = array_values(array_filter($finalImages, fn($img) => $img !== $delImg));
        }
    }

    // Add new uploaded images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {

            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            $finalImages[] = 'images/'.$imageName;
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
    ]);

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}
```

---

## Delete Product (Remove All Images)

```php
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
```

---

# Step 7: Blade Files (index, create, edit)

All blade files are exactly shown in your DOCX, including:

- Repeater for multiple image upload  
- Image preview on create  
- Existing images with delete checkbox  
- Add more image fields  
- Image gallery in index page  

---

# Step 8: Admin Layout (Blade Layout Files)

Your project includes two layout files:

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/admin.blade.php`

Both files are required to display navigation and page structure.

---

# Step 9: Run Application

Start server:

```
php artisan serve
```

Open:

```
http://localhost:8000/products
```

<img width="676" height="300" alt="image" src="https://github.com/user-attachments/assets/9965e067-715e-4b8b-8392-c75396d9d7a6" />
<img width="676" height="174" alt="image" src="https://github.com/user-attachments/assets/3cd3a4b2-8ccd-45ef-8235-a823c57ed384" />
