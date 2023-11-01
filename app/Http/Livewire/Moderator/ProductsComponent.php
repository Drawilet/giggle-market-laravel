<?php

namespace App\Http\Livewire\Moderator;

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

    public $user;
    public $products, $categories, $taxes;


    public $initialData = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "category_id" => null,
        "stock" => null,
    ];
    public $data;

    public $initialFilter = [
        "category" => null,
        "name" => null,
        "min_price" => null,
        "max_price" => null,
        "status" => "waiting"
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

    public $modals = [
        "approve" =>  false,
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->filter = $this->initialFilter;
        $this->data = $this->initialData;
    }

    public function render()
    {
        /*<──  ───────    PRODUCTS   ───────  ──>*/
        $this->products = Product::where(function ($query) {
            $query->where("status", $this->filter["status"]);

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

        return view('livewire.moderator.products-component');
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->data = $this->initialData;
    }

    public function clearFilters()
    {
        $this->filter = $this->initialFilter;
    }

    public function changeStatus($status)
    {
        $this->filter["status"] = $status;
    }

    public function Modal($modal, $value, $id = null)
    {
        if ($value == true) {
            $this->clean();
            switch ($modal) {
                case 'approve':
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

    public function approve()
    {
        $product = Product::find($this->data["id"]);
        $product->status = "available";
        $product->save();

        $this->Modal("approve", false);
    }
}
