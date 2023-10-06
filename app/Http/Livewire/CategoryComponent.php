<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryComponent extends Component
{
    public $categories, $name, $category_id;

    public $saveModal = false;
    public $deleteModal = false;

    public function render()
    {
        $this->categories = Category::where("tenant_id", Auth::user()->tenant_id)->get();
        return view('livewire.category-component');
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->name = "";
        $this->category_id = "";
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        Category::updateOrCreate(["id" => $this->category_id], [
            "name" => $this->name,
            "tenant_id" => Auth::user()->tenant_id
        ]);

        session()->flash("message", $this->category_id ? "Category updated successfully" : "Category added succesfully");

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
        Category::find($this->category_id)->delete();
        session()->flash("message", "Category deleted succesfully");

        $this->clean();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($id, $name)
    {
        $this->category_id = $id;
        session()->flash("name", $name);

        $this->deleteModal = true;
    }
    public function closeDeleteModal()
    {
        $this->deleteModal = false;
        $this->clean();
    }
}
