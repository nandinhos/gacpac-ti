<?php

namespace Tests\Feature;

use App\Livewire\Custody\PrintCautela;
use App\Livewire\Custody\Show;
use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\MilitaryUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CustodyShowTest extends TestCase
{
    use RefreshDatabase;

    protected MilitaryUser $user;

    protected CustodyLog $custody;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = MilitaryUser::factory()->create([
            'user_role' => 'admin',
        ]);

        $this->custody = CustodyLog::factory()
            ->for($this->user, 'user')
            ->has(Asset::factory()->count(2))
            ->create();
    }

    public function test_show_page_displays_custody_details(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('custody.show', $this->custody));

        $response->assertStatus(200);
        $response->assertSee($this->custody->cautela_number);
        $response->assertSee($this->user->name);
    }

    public function test_show_component_renders_correctly(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->assertStatus(200)
            ->assertSee($this->custody->cautela_number)
            ->assertSee($this->custody->assets->first()->name);
    }

    public function test_print_page_renders_correctly(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('custody.print', $this->custody));

        $response->assertStatus(200);
        $response->assertSee('TERMO DE RESPONSABILIDADE');
        $response->assertSee($this->custody->cautela_number);
    }

    public function test_print_component_renders_correctly(): void
    {
        $this->actingAs($this->user);

        Livewire::test(PrintCautela::class, ['custodyLog' => $this->custody])
            ->assertStatus(200)
            ->assertSee($this->custody->cautela_number)
            ->assertSee('COMANDO DA AERONÁUTICA');
    }

    public function test_can_upload_signed_document(): void
    {
        $this->actingAs($this->user);
        Storage::fake('public');

        $file = UploadedFile::fake()->create('signed_document.pdf', 1024, 'application/pdf');

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->call('openUploadModal')
            ->set('signedDocument', $file)
            ->set('uploadJustification', 'Documento assinado pelo responsável')
            ->call('uploadSignedDocument')
            ->assertHasNoErrors();

        $this->custody->refresh();
        $this->assertNotNull($this->custody->signed_term_url);
    }

    public function test_upload_requires_justification(): void
    {
        $this->actingAs($this->user);
        Storage::fake('public');

        $file = UploadedFile::fake()->create('signed_document.pdf', 1024, 'application/pdf');

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->call('openUploadModal')
            ->set('signedDocument', $file)
            ->set('uploadJustification', '')
            ->call('uploadSignedDocument')
            ->assertHasErrors(['uploadJustification']);
    }

    public function test_can_remove_signed_document(): void
    {
        $this->actingAs($this->user);
        Storage::fake('public');

        // First upload a document
        $this->custody->update(['signed_term_url' => 'signed-documents/test.pdf']);

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->call('openRemoveModal')
            ->set('removeJustification', 'Documento enviado incorretamente')
            ->call('removeSignedDocument')
            ->assertHasNoErrors();

        $this->custody->refresh();
        $this->assertNull($this->custody->signed_term_url);
    }

    public function test_can_perform_checkin(): void
    {
        $this->actingAs($this->user);

        $asset = $this->custody->assets->first();
        $asset->update(['status' => 'EM_USO']);

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->call('openCheckinModal')
            ->set('checkinJustification', 'Devolução programada dos equipamentos')
            ->call('performCheckin')
            ->assertHasNoErrors();

        $this->custody->refresh();
        $asset->refresh();

        $this->assertNotNull($this->custody->checkin_date);
        $this->assertEquals('DISPONIVEL', $asset->status);
    }

    public function test_checkin_requires_justification(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Show::class, ['custodyLog' => $this->custody])
            ->call('openCheckinModal')
            ->set('checkinJustification', '')
            ->call('performCheckin')
            ->assertHasErrors(['checkinJustification']);
    }
}
