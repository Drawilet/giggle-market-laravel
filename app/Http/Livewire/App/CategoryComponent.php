<?php

namespace App\Http\Livewire\App;

use App\Events\CategoryEvent;
use Livewire\Component;
use App\Models\Category;

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
                break;

            case "update":
                $category = $this->categories->first(function ($category) use ($data) {
                    return $category->id === $data["id"];
                });
                if ($category) {
                    $category->fill($data);
                }
                break;

            case "delete":
                $this->categories = $this->categories->filter(function ($category) use ($data) {
                    return $category->id != $data["id"];
                });
                break;
            default:
                # code...
                break;
        }

        $this->emit("notify", [
            "type" => "info",
            "message" =>  $data["name"] . " category " . $action . "d"
        ]);
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
        if ($value == true) {
            $this->clean();
            switch ($modal) {
                case 'save':
                    if ($id) {
                        $category = Category::find($id);
                        $this->data = $category->toArray();
                    }
                    break;
                case 'delete':
                    $category = $this->categories->find($id);
                    $this->data = $category->toArray();

                    break;

                default:
                    # code...
                    break;
            }
        }
        $this->modals[$modal] = $value;
    }

    public function save()
    {
        $category = Category::updateOrCreate(["id" => $this->data["id"]], $this->data);

        event(new CategoryEvent($this->data["id"] ? "update" : "create", $category));

        $this->Modal("save", false);
    }

    public function delete()
    {
        $this->Modal("delete", false);
        $category = Category::find($this->data["id"]);

        $this->count = $category->products->count();
        if ($this->count > 0) {
            return $this->Modal("error", true);
        }

        $category->delete();

        event(new CategoryEvent("delete", $this->data));
    }
}
