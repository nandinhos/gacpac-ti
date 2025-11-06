<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryAssignedNotification extends Notification
{
    use Queueable;

    protected $inventory;

    /**
     * Create a new notification instance.
     */
    public function __construct($inventory)
    {
        $this->inventory = $inventory;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $sectorName = $this->inventory->sector ? $this->inventory->sector->name : 'Todos os setores';

        return [
            'title' => 'Inventário Atribuído',
            'message' => "Inventário {$this->inventory->commission_number} atribuído à sua comissão - Setor: {$sectorName}",
            'urgency' => 'medium',
            'type' => 'inventory_assigned',
            'action_url' => "/inventory/{$this->inventory->id}",
            'inventory_id' => $this->inventory->id,
            'commission_number' => $this->inventory->commission_number,
            'sector_name' => $sectorName,
            'start_date' => $this->inventory->start_date->toISOString(),
        ];
    }
}
