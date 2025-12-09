 Laravel 11 Multiple Image Upload with Repeater (Dynamic Fields)

![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)

---

 Overview

This project demonstrates how to upload **multiple images dynamically using a repeater (Add More fields)** in Laravel 11.

It includes:

- Multiple image upload  
- Dynamic repeater fields  
- Save images in database  
- Delete images  
- Responsive Bootstrap UI  
- Product gallery view  

---

 Features

- Add unlimited images dynamically  
- Preview images before upload  
- Remove image field before submitting  
- Store images in folder + database  
- Display uploaded images in gallery  
- Clean UI with Bootstrap  
- Fully functional CRUD  

---

 Folder Structure

```
MULTIPLE_IMAGE_REPEATER/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php
│   ├── Models/
│   │   └── Product.php
│   │   └── ProductImage.php
│
├── database/
│   ├── migrations/
│   │   ├── create_products_table.php
│   │   ├── create_product_images_table.php
│
├── public/
│   └── product_images/
│
├── resources/
│   ├── views/
│   │   ├── products/
│   │   │   ├── create.blade.php
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│
├── routes/
│   └── web.php
│
└── README.md
```

---

 Installation

```bash
composer create-project laravel/laravel multiple-image-upload
cd multiple-image-upload
```

Install Breeze (Optional):

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

---

 Environment Setup

Update `.env`:

```
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

 Migrations

 Products Table

```
id  
name  
description  
```

 Product Images Table

```
id  
product_id  
image  
```

Run all migrations:

```bash
php artisan migrate
```

---

 Models

 Product Model

```php
class Product extends Model
{
    protected $fillable = ['name', 'description'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
```

 ProductImage Model

```php
class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image'];
}
```

---

 Routes

```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');
```

---

 Controller (Multiple Image Upload Logic)

 Store Function

```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    if ($request->hasFile('images')) {
        foreach ($request->images as $file) {
            $imgName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('product_images'), $imgName);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => 'product_images/'.$imgName,
            ]);
        }
    }

    return redirect()->route('products.index');
}
```

---

 Blade Pages

---

 Create Page (Dynamic Repeater)

```
/resources/views/products/create.blade.php
```

Includes:
- Add More button  
- Remove button  
- Dynamic input fields  
- Multiple file inputs  
- Bootstrap UI  

---

 Index Page

Shows product list with:
- Product name  
- View button  

---

 Show Page (Gallery)

Displays uploaded multiple images.

---




 Run Application

```
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/products
```
<img width="676" height="300" alt="image" src="https://github.com/user-attachments/assets/9965e067-715e-4b8b-8392-c75396d9d7a6" />
<img width="676" height="174" alt="image" src="https://github.com/user-attachments/assets/3cd3a4b2-8ccd-45ef-8235-a823c57ed384" />
