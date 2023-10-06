<?php

namespace App\Http\Livewire;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ManageTenantComponent extends Component
{

    public $users;
    public $tenant_name, $user_id;
    public $name, $email, $password, $tenant_role;

    public $saveUserModal = false, $deleteUserModal = false;

    public function mount()
    {
        $this->tenant_name = Auth::user()->tenant->name;
    }

    public function render()
    {
        $this->users = User::where("tenant_id", Auth::user()->tenant_id)
            ->where("id", "!=", Auth::user()->id)
            ->get();

        return view('livewire.manage-tenant-component');
    }
    public function updateTenantInformation()
    {
        $this->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
        ]);

        Tenant::where("id", Auth::user()->tenant_id)->update([
            "name" => $this->tenant_name
        ]);
    }

    /*<──  ───────    USERS   ───────  ──>*/
    public function clearUser()
    {
        $this->user_id = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->tenant_role = null;
    }

    public function deleteUser()
    {
        User::find($this->user_id)->delete();

        $this->clearUser();
        $this->closeDeleteUserModal();
    }

    public function openDeleteUserModal($id, $name)
    {
        $this->user_id = $id;
        session()->flash("name", $name);

        $this->deleteUserModal = true;
    }
    public function closeDeleteUserModal()
    {
        $this->deleteUserModal = false;
        $this->clearUser();
    }

    public function closeSaveUserModal()
    {
        $this->saveUserModal = false;
        $this->clearUser();
    }
    public function openSaveUserModal()
    {
        $this->saveUserModal = true;
    }

    public function openUpdateUserModal($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = "- - -";
        $this->tenant_role = $user->tenant_role;

        $this->openSaveUserModal();
    }

    public function saveUser()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            "password" => ["required"],
            'tenant_role' => ['required']
        ]);

        $data =  [
            "name" => $this->name,
            "email" => $this->email,
            "tenant_id" => Auth::user()->tenant_id,
            "tenant_role" => $this->tenant_role,
        ];
        if ($this->password != "- - -")
            $data["password"] = Hash::make($this->password);

        User::updateOrCreate(["id" => $this->user_id], $data);

        $this->closeSaveUserModal();
        $this->clearUser();
    }
}
