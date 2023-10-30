<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function cleanPhotosDir()
    {
        $dir = public_path("storage/products");
        if (file_exists($dir)) {
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator(
                $it,
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
        }
    }

    public function getAPIS()
    {
        $fakeStore = [
            "api" => "https://fakestoreapi.com",
            "handler" => function ($data) {
                return [
                    "name" => $data->title,
                    "description" => $data->description,
                    "category" => $data->category,
                    "price" => $data->price,
                    "image" => $data->image,
                ];
            }
        ];


        $APIS = [   /* "https://api.escuelajs.co/api/v1/products" => function ($data) {
            return [
                "name" => $data->title,
                "description" => $data->description,
                "category" => $data->category->name,
                "price" => $data->price,
                "image" => $data->images[0],
            ];
        } */];
        $categories = json_decode(file_get_contents($fakeStore["api"] . "/products/categories"));

        foreach ($categories as $category) {
            $APIS[$fakeStore["api"] . "/products/category/" . $category] = function ($data) use ($category) {
                return [
                    "name" => $data->title,
                    "description" => $data->description,
                    "category" => $category,
                    "price" => $data->price,
                    "image" => $data->image,
                ];
            };
        }
        return   $APIS;
    }

    public function run()
    {
        $counter = 0;

        $this->cleanPhotosDir();
        $APIS = $this->getAPIS();
    
        foreach ($APIS as $API => $callback) {
            $stores = Store::factory()->count(1)->create();
            foreach ($stores as $store) {
                $users = User::factory()
                    ->count(5)
                    ->state(['store_id' => $store->id, 'store_role' => 'seller'])
                    ->create();

                //Fetch to api
                $products = json_decode(file_get_contents($API));

                foreach ($products as $product) {
                    $data = $callback($product);
                    $category = Category::firstOrCreate(['name' => $data["category"]]);

                    //Save image to storage (name photo.extension)
                    $image = file_get_contents($data["image"]);
                    $imageName = substr($data["image"], strrpos($data["image"], '/') + 1);

                    //Get image extension
                    $extension = explode('.', $imageName);
                    $extension = $extension[count($extension) - 1];

                    $imageName = "photo." . $extension;

                    $dataInDB = Product::factory()
                        ->count(1)
                        ->create([
                            'name' => $data["name"],
                            'description' => $data["description"],
                            'price' => $data["price"],
                            'photo' => $imageName,
                            "stock" => rand(1, 200),

                            "user_id" => $users->random()->id,
                            'store_id' => $store->id,
                            'category_id' => $category->id
                        ]);

                    $path = 'storage/products/' . $dataInDB[0]->id;

                    //Create folder
                    mkdir(public_path($path), 0777, true);
                    $path = $path .  '/' . $imageName;

                    file_put_contents(public_path($path), $image);

                    $counter++;
                }
            }
        }

        $this->command->info("Seeded $counter products");
    }
}
