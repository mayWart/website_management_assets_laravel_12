<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AdminChat extends Component
{
    public $selectedUserId = null;
    public $messageText = '';
    public $search = '';

    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    // Mengambil daftar user (Computed Property)
    public function getConversationsProperty()
    {
        return User::whereHas('chats')
            ->where('role', '!=', 'admin')
            ->when($this->search, function($q) {
                $q->where('username', 'like', '%' . $this->search . '%');
            })
            ->with(['chats' => function($q) {
                $q->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function($user) {
                return $user->chats->first()->created_at ?? 0;
            });
    }

    // Mengambil pesan chat (Computed Property)
    public function getMessagesProperty()
    {
        if (!$this->selectedUserId) {
            return collect(); 
        }

        return Message::where('user_id', $this->selectedUserId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        
        // Tandai sudah dibaca
        Message::where('user_id', $userId)
               ->where('sender_id', '!=', Auth::id())
               ->update(['is_read' => true]);

        $this->reset('messageText');
        $this->dispatch('chat-scrolled');
    }

    public function sendMessage()
    {
        $this->validate();

        if (!$this->selectedUserId) return;

        Message::create([
            'user_id' => $this->selectedUserId,
            'sender_id' => Auth::id(),
            'message' => $this->messageText,
            'is_read' => false,
        ]);

        $this->reset('messageText');
        $this->dispatch('chat-scrolled');
    }

    public function render()
    {
        return view('livewire.admin-chat');
    }
}