<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Models\Tax;

class TaxComponent extends Component
{

    public $taxes, $name, $percentage, $tax_id, $user;

    public $saveModal = false;
    public $deleteModal = false;

    public function render()
    {
        $this->user = Auth::user();
        $this->taxes = Tax::where("tenant_id", $this->user->tenant_id)->get();

        return view('livewire.tax-component');
    }

    /*<──  ───────    UTILS   ───────  ──>*/
    public function clean()
    {
        $this->name = "";
        $this->percentage = "";
        $this->tax_id = "";
    }

    /*<──  ───────    SAVE   ───────  ──>*/
    public function save()
    {
        Tax::updateOrCreate(["id" => $this->tax_id], [
            "name" => $this->name,
            "percentage" => $this->percentage,
            "tenant_id" => $this->user->tenant_id
        ]);

        session()->flash("message", $this->tax_id ? "Tax updated successfully" : "Tax added succesfully");

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
        $tax = Tax::findOrFail($id);
        $this->tax_id = $id;
        $this->name = $tax->name;
        $this->percentage = $tax->percentage;

        $this->openSaveModal();
    }

    /*<──  ───────    DELETE   ───────  ──>*/
    public function delete()
    {
        $tax = Tax::find($this->tax_id);

        return;
        $tax->delete();

        session()->flash("message", "Tax deleted succesfully");

        $this->clean();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($id, $name)
    {
        $this->tax_id = $id;
        session()->flash("name", $name);

        $this->deleteModal = true;
    }
    public function closeDeleteModal()
    {
        $this->deleteModal = false;
        $this->clean();
    }
}
