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

        session()->flash("message", $this->category_id ? "Category updated successfully" : "Category added succesfully");

        event(new CategoryEvent($this->category_id ? "update" : "create", $category));

        $this->closeSaveModal();
        $this->clean();
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

        Category::find($this->category_id)->delete();
        event(new CategoryEvent("delete", ["id" => $this->category_id]));

        session()->flash("message", "Category deleted succesfully");
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
