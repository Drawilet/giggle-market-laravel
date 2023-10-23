<?php

namespace App\Http\Livewire\App;

use App\Events\CategoryEvent;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;

class CategoryComponent extends Component
{
    public $initialData = [
        "id" => null,
        "name" => null
    ];
    public $data;

    public $modals = [
        "save" => false,
        "delete" => false,
        "error" => false
    ];

    public $categories, $count = 0;


    protected $listeners = ["CategoryEvent" => "eventHandler"];

    public function eventHandler($e)
    {
        $action = $e["action"];
        $data = $e["data"];

        switch ($action) {
            case 'create':
                $category = Category::make($data);
                $category->id = $data["id"];

                $this->categories->push($category);

                $this->emit("notify", [
                    "type" => "success",
                    "message" =>  $data["name"] . " category created"
                ]);
                break;

            case "update":
                $category = $this->categories->first(function ($category) use ($data) {
                    return $category->id === $data["id"];
                });

                if ($category) {
                    $category->fill($data);
                }

                $this->emit("notify", [
                    "type" => "warning",
                    "message" =>  $data["name"] . " category updated"
                ]);
                break;

            case "delete":
                $this->categories = $this->categories->filter(function ($category) use ($data) {
                    return $category->id != $data["id"];
                });

                $this->emit("notify", [
                    "type" => "error",
                    "message" =>  $data["name"] . " category deleted"
                ]);
                break;
            default:
                # code...
                break;
        }
    }

    public function mount()
    {
        $this->data = $this->initialData;
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.app.category-component');
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->data = $this->initialData;
    }

    public function Modal($modal, $value, $id = null)
    {
        if ($value) {
            $this->clean();
            switch ($modal) {
                case 'save':
                    break;
                case 'delete':
                    $this->data = $this->categories->find($id);
                    break;
                case "update":
                    $category = Category::findOrFail($id);
                    $this->data = $category->toArray();
                    break;

                default:
                    # code...
                    break;
            }
        }
        $this->modals[$modal] = $value;
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        $category = Category::updateOrCreate(["id" => $this->data["id"]], $this->data);

        event(new CategoryEvent($this->data["id"] ? "update" : "create", $category));

        $this->clean();
    }

    /*<──  ───────    DELETE   ───────  ──>*/
    public function delete()
    {
        $this->Modal("delete", false);

        $products = Product::where("category_id", $this->data["id"])->get();
        if (!$products->isEmpty()) {
            $this->count = $products->count();
            return $this->Modal("error", true);
        }

        $category = Category::find($this->data["id"]);
        $category->delete();

        event(new CategoryEvent("delete", $this->data));
    }
}
