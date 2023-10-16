<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductTax;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Models\Tax;

class TaxComponent extends Component
{

    public $taxes, $name, $percentage, $tax_id, $user, $count = 0;

    public $saveModal = false, $deleteModal = false, $errorModal = false;

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
        $this->closeDeleteModal();

        $relations = ProductTax::where("tax_id", $this->tax_id)->get();
        if (!$relations->isEmpty()) {
            $this->count = $relations->count();
            return $this->openErrorModal();
        }

        Tax::find($this->tax_id)->delete();
        session()->flash("message", "Tax deleted succesfully");
    }

    public function openDeleteModal($id, $name)
    {
        $this->clean();

        $this->tax_id = $id;
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
