<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\CustodyLog;

class CustodyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $custody;

    /**
     * Create a new notification instance.
     */
    public function __construct(CustodyLog $custody)
    {
        $this->custody = $custody;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $assetCount = $this->custody->custodyAssets()->count();

        return [
            'title' => 'Nova Cautela Criada',
            'message' => "Cautela {$this->custody->cautela_number} criada para {$this->custody->user->name} com {$assetCount} ativo(s)",
            'urgency' => 'info',
            'type' => 'custody_created',
            'action_url' => "/custody/{$this->custody->id}",
            'custody_id' => $this->custody->id,
            'user_name' => $this->custody->user->name,
            'asset_count' => $assetCount,
            'checkout_date' => $this->custody->checkout_date->toISOString(),
        ];
    }
}
