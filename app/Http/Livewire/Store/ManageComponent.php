<?php

namespace App\Http\Livewire\Store;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ManageComponent extends Component
{

    public $users;
    public $store_name, $user_id;
    public $name, $email, $password, $store_role;

    public $saveUserModal = false, $deleteUserModal = false;

    public function mount()
    {
        $this->store_name = Auth::user()->store->name;
    }

    public function render()
    {
        $this->users = User::where("store_id", Auth::user()->store_id)
            ->where("id", "!=", Auth::user()->id)
            ->get();

        return view('livewire.store.manage-component');
    }
    public function updateStoreInformation()
    {
        $this->validate([
            'store_name' => ['required', 'string', 'max:255'],
        ]);

        Store::where("id", Auth::user()->store_id)->update([
            "name" => $this->store_name
        ]);
    }

    /*<──  ───────    USERS   ───────  ──>*/
    public function clearUser()
    {
        $this->user_id = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->store_role = null;
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
        $this->store_role = $user->store_role;

        $this->openSaveUserModal();
    }

    public function saveUser()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            "password" => ["required"],
            'store_role' => ['required']
        ]);

        $data =  [
            "name" => $this->name,
            "email" => $this->email,
            "store_id" => Auth::user()->store_id,
            "store_role" => $this->store_role,
        ];
        if ($this->password != "- - -")
            $data["password"] = Hash::make($this->password);

        User::updateOrCreate(["id" => $this->user_id], $data);

        $this->closeSaveUserModal();
        $this->clearUser();
    }
}
