<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;

class ProductsComponent extends Component
{

    use WithFileUploads;

    public $initialData = [
        "id" => null,
        "photo" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "category_id" => null,
        "stock" => null,
    ];
    public $data;

    public $user;
    public $products, $categories, $taxes;

    public  $tax_id, $taxes_id = [];

    public $modals = [
        "save" =>  false,
        "unpublish" =>  false,
    ];

    public $initialFilter = [
        "category" => null,
        "name" => null,
        "min_price" => null,
        "max_price" => null,
        "status" => "available"
    ];
    public $filter;

    public $statusType = [
        "available" => [
            "label" => "Available",
            "icon" => "fas fa-check-circle",
            "color" => "#28a745",
        ],
        "waiting" => [
            "label" => "Waiting",
            "icon" => "fas fa-clock",
            "color" => "#ffc107",
        ],
        "unavailable" => [
            "label" => "Unavailable",
            "icon" => "fas fa-times-circle",
            "color" => "#dc3545",
        ],
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->data = $this->initialData;
        $this->filter = $this->initialFilter;
    }

    public function render()
    {
        /*<──  ───────    PRODUCTS   ───────  ──>*/
        $this->products = Product::where(function ($query) {
            $query->where("status", $this->filter["status"]);

            $query->where("store_id", $this->user->store_id);
            $query->where("category_id", "like", "%" . $this->filter["category"] . "%");

            if (!empty($this->filter["name"])) {
                $query->where("name", "like", "%" . $this->filter["name"] . "%");
            }

            if (!empty($this->filter["min_price"])) {
                $query->where("price", ">=", $this->filter["min_price"]);
            }

            if (!empty($this->filter["max_price"])) {
                $query->where("price", "<=", $this->filter["max_price"]);
            }
        })->get();

        /*<──  ───────    TAXES   ───────  ──>*/
        $this->taxes = Tax::all();

        /*<──  ───────    CATEGORIES   ───────  ──>*/
        $this->categories = Category::all();

        return view('livewire.store.products-component');
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
    public function clean()
    {
        $this->data = $this->initialData;
        $this->taxes_id = [];
    }

    public function clearFilters()
    {
        $this->filter = $this->initialFilter;
    }

    public function changeStatus($status)
    {
        $this->filter["status"] = $status;
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        $this->validate([
            "data.photo" => Rule::requiredIf(!$this->data["id"]),
            "data.name" => "required|string|max:20",
            "data.description" => "required|string|max:600",
            "data.price" => "required",
            "taxes_id" => "required",
            "data.category_id" => "required",
            "data.stock" => "required|integer",
        ]);
        $photo = $this->data["photo"];
        if (gettype($this->data["photo"]) == "string") {
            $photo = null;
        } else {
            $filename = "photo" . "." . $this->data["photo"]->extension();
            $this->data["photo"] = $filename;
        }

        $this->data["store_id"] = $this->user->store_id;
        $this->data["user_id"] = $this->user->id;

        $product = Product::updateOrCreate(["id" => $this->data["id"]], $this->data);

        /*<──  ───────    DELETE OLD TAXES   ───────  ──>*/
        if ($this->data["id"])
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

        if ($photo)
            $photo->storeAs("public/products/" . $product->id . "/" . $filename);

        session()->flash("message", $this->data["id"] ? "Product updated successfully" : "Product added succesfully");

        $this->Modal("save", false);
    }

    /*<──  ───────    UNPUBLISH   ───────  ──>*/
    public function unpublish()
    {
        $product = Product::find($this->data["id"]);
        $product->status = "unavailable";
        $product->save();

        session()->flash("message", "Product unpublished succesfully");

        $this->Modal("unpublish", false);
    }

    public function Modal($modal, $value, $id = null)
    {
        if ($value == true) {
            $this->clean();
            switch ($modal) {
                case 'save':
                    if ($id) {
                        $product = Product::find($id);
                        $this->data = $product->toArray();

                        $this->taxes_id = $product->product_taxes->map(function ($product_tax) {
                            return $product_tax->tax->id;
                        })->toArray();
                    }
                    break;
                case 'unpublish':
                    $product = $this->products->find($id);
                    $this->data = $product->toArray();

                    break;

                default:
                    # code...
                    break;
            }
        }
        $this->modals[$modal] = $value;
    }
}
