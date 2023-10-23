<?php

namespace App\Http\Livewire\App;

use App\Events\CategoryEvent;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;

class CategoryComponent extends Component
{
    public $categories, $name, $category_id, $count = 0;

    public $saveModal = false, $deleteModal = false, $errorModal = false;

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
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.app.category-component');
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->name = null;
        $this->category_id = null;
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        $category = Category::updateOrCreate(["id" => $this->category_id], [
            "name" => $this->name,
        ]);

        event(new CategoryEvent($this->category_id ? "update" : "create", $category));

        $this->closeSaveModal();
        $this->clean();
    }
    public function openCreateModal()
    {
        $this->clean();
        $this->openSaveModal();
    }
    public function openSaveModal()
    {
        $this->saveModal = true;
    }
    public function closeSaveModal()
    {
        $this->saveModal = false;
    }

    /*<──  ───────    UPDATE   ───────  ──>*/
    public function openUpdateModal($id)
    {
        $this->clean();

        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;

        $this->openSaveModal();
    }

    /*<──  ───────    DELETE   ───────  ──>*/
    public function delete()
    {
        $this->closeDeleteModal();

        $products = Product::where("category_id", $this->category_id)->get();
        if (!$products->isEmpty()) {
            $this->count = $products->count();
            return $this->openErrorModal();
        }

        $category = Category::find($this->category_id);
        $id = $category->id;
        $name = $category->name;

        $category->delete();

        event(new CategoryEvent("delete", ["id" => $id, "name" => $name]));
    }

    public function openDeleteModal($id, $name)
    {
        $this->clean();

        $this->category_id = $id;
        session()->flash("name", $name);

        $this->deleteModal = true;
    }
    public function closeDeleteModal()
    {
        $this->deleteModal = false;
    }

    public function closeErrorModal()
    {
        $this->errorModal = false;
    }

    public function openErrorModal()
    {
        $this->errorModal = true;
    }
}
