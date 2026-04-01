<?php

namespace Tests\Feature;

use App\Models\InventoryRecord;
use App\Models\User;
use App\Notifications\InventoryAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dropdown_displays_unread_notifications_count()
    {
        $inventory = InventoryRecord::factory()->create([
            'responsible_user_id' => $this->user->id,
        ]);

        $this->user->notify(new InventoryAssignedNotification($inventory));

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Notifications\Dropdown::class)
            ->assertSet('unreadCount', 1)
            ->assertSee('Inventário Atribuído');
    }

    public function test_can_mark_notification_as_read_from_dropdown()
    {
        $inventory = InventoryRecord::factory()->create([
            'responsible_user_id' => $this->user->id,
        ]);

        $this->user->notify(new InventoryAssignedNotification($inventory));
        $notification = $this->user->unreadNotifications->first();

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Notifications\Dropdown::class)
            ->call('markAsRead', $notification->id)
            ->assertRedirect("/inventory/{$inventory->id}");

        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    public function test_can_mark_all_notifications_as_read()
    {
        $inventory1 = InventoryRecord::factory()->create(['responsible_user_id' => $this->user->id]);
        $inventory2 = InventoryRecord::factory()->create(['responsible_user_id' => $this->user->id]);

        $this->user->notify(new InventoryAssignedNotification($inventory1));
        $this->user->notify(new InventoryAssignedNotification($inventory2));

        $this->assertEquals(2, $this->user->unreadNotifications()->count());

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Notifications\Dropdown::class)
            ->call('markAllAsRead');

        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    public function test_notifications_index_page_displays_notifications()
    {
        $inventory = InventoryRecord::factory()->create(['responsible_user_id' => $this->user->id]);
        $this->user->notify(new InventoryAssignedNotification($inventory));

        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Inventário Atribuído');
    }

    public function test_can_delete_notification_from_index()
    {
        $inventory = InventoryRecord::factory()->create(['responsible_user_id' => $this->user->id]);
        $this->user->notify(new InventoryAssignedNotification($inventory));
        $notification = $this->user->notifications->first();

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Notifications\Index::class)
            ->call('deleteNotification', $notification->id);

        $this->assertEquals(0, $this->user->notifications()->count());
    }
}
