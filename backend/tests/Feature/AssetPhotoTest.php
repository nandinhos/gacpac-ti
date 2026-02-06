<?php

namespace Tests\Feature;

use App\Livewire\Assets\Edit;
use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssetPhotoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_view_photos_tab()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('activeTab', 'fotos')
            ->assertStatus(200)
            ->assertSee('Nenhuma foto cadastrada');
    }

    #[Test]
    public function can_upload_single_photo()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(1024);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('activeTab', 'fotos')
            ->set('uploadPhotos', [$file])
            ->set('caption', 'Foto frontal')
            ->call('savePhotos')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('asset_photos', [
            'asset_id' => $asset->id,
            'caption' => 'Foto frontal',
            'mime_type' => 'image/jpeg',
            'is_primary' => true,
        ]);

        $photo = AssetPhoto::where('asset_id', $asset->id)->first();
        Storage::disk('public')->assertExists($photo->url);
    }

    #[Test]
    public function can_upload_multiple_photos()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $files = [
            UploadedFile::fake()->image('photo1.jpg', 800, 600)->size(512),
            UploadedFile::fake()->image('photo2.png', 800, 600)->size(512),
        ];

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('activeTab', 'fotos')
            ->set('uploadPhotos', $files)
            ->call('savePhotos')
            ->assertHasNoErrors();

        $this->assertEquals(2, AssetPhoto::where('asset_id', $asset->id)->count());
    }

    #[Test]
    public function first_photo_is_set_as_primary()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->image('photo.jpg')->size(512);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('uploadPhotos', [$file])
            ->call('savePhotos');

        $photo = AssetPhoto::where('asset_id', $asset->id)->first();
        $this->assertTrue($photo->is_primary);
    }

    #[Test]
    public function validates_file_type()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('uploadPhotos', [$file])
            ->call('savePhotos')
            ->assertHasErrors(['uploadPhotos.0']);
    }

    #[Test]
    public function validates_file_size()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $file = UploadedFile::fake()->image('huge.jpg')->size(6000); // 6MB > 5MB limit

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('uploadPhotos', [$file])
            ->call('savePhotos')
            ->assertHasErrors(['uploadPhotos.0']);
    }

    #[Test]
    public function can_set_primary_photo()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $photo1 = AssetPhoto::factory()->create(['asset_id' => $asset->id, 'is_primary' => true]);
        $photo2 = AssetPhoto::factory()->create(['asset_id' => $asset->id, 'is_primary' => false]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->call('setPrimary', $photo2->id);

        $this->assertFalse($photo1->fresh()->is_primary);
        $this->assertTrue($photo2->fresh()->is_primary);
    }

    #[Test]
    public function can_delete_photo()
    {
        $this->withoutDefer();
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $path = 'asset-photos/test.jpg';
        Storage::disk('public')->put($path, 'fake-image-content');

        $photo = AssetPhoto::factory()->create([
            'asset_id' => $asset->id,
            'url' => $path,
            'is_primary' => false,
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->call('deletePhoto', $photo->id);

        $this->assertDatabaseMissing('asset_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($path);
    }

    #[Test]
    public function deleting_primary_promotes_next_photo()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $path1 = 'asset-photos/primary.jpg';
        $path2 = 'asset-photos/secondary.jpg';
        Storage::disk('public')->put($path1, 'content');
        Storage::disk('public')->put($path2, 'content');

        $primary = AssetPhoto::factory()->create([
            'asset_id' => $asset->id,
            'url' => $path1,
            'is_primary' => true,
        ]);
        $secondary = AssetPhoto::factory()->create([
            'asset_id' => $asset->id,
            'url' => $path2,
            'is_primary' => false,
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->call('deletePhoto', $primary->id);

        $this->assertTrue($secondary->fresh()->is_primary);
    }

    #[Test]
    public function shows_gallery_with_photos()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        AssetPhoto::factory()->create([
            'asset_id' => $asset->id,
            'caption' => 'Vista lateral',
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->set('activeTab', 'fotos')
            ->assertSee('Vista lateral')
            ->assertSee('1 foto');
    }

    #[Test]
    public function tabs_switch_correctly()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        Livewire::actingAs($user)
            ->test(Edit::class, ['asset' => $asset])
            ->assertSet('activeTab', 'dados')
            ->assertSee('Nome do Ativo')
            ->set('activeTab', 'fotos')
            ->assertSee('Salvar Fotos')
            ->assertDontSee('Nome do Ativo');
    }
}
