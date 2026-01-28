<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatSupport extends Component
{
    public $messageText = '';

    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    public function getMessagesProperty()
    {
        return Message::where('user_id', Auth::id())
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        $this->validate();

        Message::create([
            'user_id'   => Auth::id(),
            'sender_id' => Auth::id(),
            'message'   => $this->messageText,
        ]);

        $this->reset('messageText');
    }

    public function render()
    {
        return view('livewire.chat-support');
    }
}
