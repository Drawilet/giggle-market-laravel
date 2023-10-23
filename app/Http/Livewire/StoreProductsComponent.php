<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;

class StoreProductsComponent extends Component
{
    use WithFileUploads;

    public $user;
    public $products, $categories, $taxes;

    public  $product_id, $tax_id;
    public $photo, $description, $name, $tags, $price, $stock, $category_id, $taxes_id = [];

    public $saveModal = false, $unpublishModal = false;

    public $filter_category, $filter_description = "", $filter_min_price, $filter_max_price;

    public function render()
    {
        $this->user = Auth::user();

        /*<──  ───────    PRODUCTS   ───────  ──>*/
        $this->products = Product::where(function ($query) {
            $query->where("unpublished", false);

            $query->where("store_id", $this->user->store_id);
            $query->where("category_id", "like", "%" . $this->filter_category . "%");

            if (!empty($this->filter_description)) {
                $query->where("description", "like", "%" . $this->filter_description . "%");
            }

            if (!empty($this->filter_min_price)) {
                $query->where("price", ">=", $this->filter_min_price);
            }

            if (!empty($this->filter_max_price)) {
                $query->where("price", "<=", $this->filter_max_price);
            }
        })->get();

        /*<──  ───────    TAXES   ───────  ──>*/
        $this->taxes = Tax::all();

        /*<──  ───────    CATEGORIES   ───────  ──>*/
        $this->categories = Category::all();

        return view('livewire.store-products-component');
    }

    /*<──  ───────    TAXES   ───────  ──>*/
    public function addTax()
    {
        $this->taxes_id[] = intval($this->tax_id);
        $this->tax_id = "";
    }
    public function removeTax($id)
    {
        $this->taxes_id = array_filter($this->taxes_id, function ($tax) use ($id) {
            return $tax !== $id;
        });
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clear()
    {
        $this->product_id = "";
        $this->description  = "";
        $this->name = "";
        $this->tags = "";
        $this->price = 0;
        $this->stock = 0;
        $this->category_id = "";
        $this->taxes_id = [];
        $this->photo = null;
    }

    public function clearFilters()
    {
        $this->filter_category = null;
        $this->filter_description = null;
        $this->filter_min_price = null;
        $this->filter_max_price = null;
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        $this->validate([
            "photo" => Rule::requiredIf(!$this->product_id),
            "name" => "required|string|max:20",
            "description" => "required|string|max:600",
            "price" => "required",
            "taxes_id" => "required",
            "category_id" => "required",
            "stock" => "required|integer",
        ]);
        if (gettype($this->photo) == "string") {
            $this->photo = null;
        } else {
            $filename = "photo" . "." . $this->photo->extension();
        }

        $user = Auth::user();

        $data = [
            "store_id" => $user->store_id,
            "user_id" => $user->id,
            "name" => $this->name,
            "description" => $this->description,
            "tags" => $this->tags,
            "price" => $this->price,
            "stock" => $this->stock,
            "category_id" => $this->category_id,
        ];
        if ($this->photo) $data["photo"] = $filename;

        $product = Product::updateOrCreate(["id" => $this->product_id], $data);

        /*<──  ───────    DELETE OLD TAXES   ───────  ──>*/
        if ($this->product_id)
            foreach ($product->product_taxes as $product_tax) {
                if (!in_array($product_tax->tax->id, $this->taxes_id)) {
                    ProductTax::find($product_tax->id)->delete();
                }
            }

        foreach ($this->taxes_id as $tax_id) {
            ProductTax::updateOrcreate([
                "product_id" => $product->id,
                "tax_id" => $tax_id,
            ]);
        }

        if ($this->photo)
            $this->photo->storeAs("public/products/" . $product->id . "/" . $filename);

        session()->flash("message", $this->product_id ? "Product updated successfully" : "Product added succesfully");

        $this->closeSaveModal();
    }
    public function openSaveModal()
    {
        $this->saveModal = true;
    }
    public function closeSaveModal()
    {
        $this->saveModal = false;
        $this->clear();
    }
    /*<──  ───────    UPDATE   ───────  ──>*/
    public function openUpdateModal($id)
    {
        $product = Product::findOrFail($id);
        $this->photo = $product->photo;
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->tags = $product->tags;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->taxes_id = $product->product_taxes->map(function ($product_tax) {
            return $product_tax->tax->id;
        })->toArray();

        $this->openSaveModal();
    }

    /*<──  ───────    UNPUBLISH   ───────  ──>*/
    public function unpublish()
    {
        $product = Product::find($this->product_id);
        $product->unpublished = TRUE;
        $product->save();

        session()->flash("message", "Product unpublished succesfully");

        $this->closeUnpublishModal();
    }

    public function openUnpublishModal($id, $description)
    {
        $this->product_id = $id;
        session()->flash("description", $description);

        $this->unpublishModal = true;
    }
    public function closeUnpublishModal()
    {
        $this->unpublishModal = false;
        $this->clear();
    }
}
