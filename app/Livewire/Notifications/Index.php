<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        if (isset($notification->data['action_url'])) {
            return $this->redirect($notification->data['action_url'], navigate: true);
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($notificationId)
    {
        Auth::user()->notifications()->findOrFail($notificationId)->delete();
    }

    public function render()
    {
        return view('livewire.notifications.index', [
            'notifications' => Auth::user()->notifications()->paginate(15),
        ])->layout('layouts.sgaiti');
    }
}
