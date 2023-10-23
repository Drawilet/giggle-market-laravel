<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationComponent extends Component
{
    public $message, $type;

    protected $listeners = ["notify" => "notify"];
    public function notify($data)
    {
        $this->message = $data["message"];
        $this->type = $data["type"];

        $this->emit("showNotification");
    }


    public function render()
    {
        return view('livewire.notification-component');
    }
}
