<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public $APIS = ["https://fakestoreapi.com/products"];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $dir = public_path("storage/products");
        //Clean all folders and files
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

        foreach ($this->APIS as $API) {
            $stores = Store::factory()->count(1)->create();
            foreach ($stores as $store) {
                $users = User::factory()
                    ->count(5)
                    ->state(['store_id' => $store->id, 'store_role' => 'seller'])
                    ->create();

                //Fetch to api
                $products = json_decode(file_get_contents($API));

                foreach ($products as $product) {
                    $category = Category::firstOrCreate(['name' => $product->category]);

                    //Save image to storage (name photo.extension)
                    $image = file_get_contents($product->image);
                    $imageName = substr($product->image, strrpos($product->image, '/') + 1);

                    //Get image extension
                    $extension = explode('.', $imageName);
                    $extension = $extension[count($extension) - 1];

                    $imageName = "photo." . $extension;

                    $productInDB = Product::factory()
                        ->count(1)
                        ->create([
                            'name' => $product->title,
                            'description' => $product->description,
                            'price' => $product->price,
                            'photo' => $imageName,
                            "stock" => rand(1, 200),

                            "user_id" => $users->random()->id,
                            'store_id' => $store->id,
                            'category_id' => $category->id
                        ]);

                    $path = 'storage/products/' . $productInDB[0]->id;

                    //Create folder
                    mkdir(public_path($path), 0777, true);
                    $path = $path .  '/' . $imageName;

                    file_put_contents(public_path($path), $image);
                }
            }
        }
    }
}
