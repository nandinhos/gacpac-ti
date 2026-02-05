<?php

namespace App\Livewire\Custody;

use App\Models\CustodyLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    use WithFileUploads;

    public CustodyLog $custodyLog;

    // Signed Document Upload
    public $signedDocument;
    public $uploadJustification = '';
    public $showUploadModal = false;
    public $showRemoveModal = false;
    public $removeJustification = '';

    // Check-in Modal
    public $showCheckinModal = false;
    public $checkinJustification = '';

    public function mount(CustodyLog $custodyLog)
    {
        $this->custodyLog = $custodyLog->load(['user', 'assets']);
    }

    // Signed Document Methods
    public function openUploadModal()
    {
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->signedDocument = null;
        $this->uploadJustification = '';
    }

    public function uploadSignedDocument()
    {
        $this->validate([
            'signedDocument' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'uploadJustification' => 'required|string|min:5',
        ], [
            'signedDocument.required' => 'Selecione um arquivo.',
            'signedDocument.mimes' => 'O arquivo deve ser PDF, JPG ou PNG.',
            'signedDocument.max' => 'O arquivo deve ter no máximo 10MB.',
            'uploadJustification.required' => 'Informe a justificativa.',
            'uploadJustification.min' => 'A justificativa deve ter pelo menos 5 caracteres.',
        ]);

        $path = $this->signedDocument->store('signed-documents', 'public');

        $this->custodyLog->update([
            'signed_term_url' => $path,
        ]);

        $this->closeUploadModal();
        $this->custodyLog->refresh();

        $this->dispatch('notify', message: 'Documento assinado enviado com sucesso!', type: 'success');
    }

    public function openRemoveModal()
    {
        $this->showRemoveModal = true;
    }

    public function closeRemoveModal()
    {
        $this->showRemoveModal = false;
        $this->removeJustification = '';
    }

    public function removeSignedDocument()
    {
        $this->validate([
            'removeJustification' => 'required|string|min:5',
        ], [
            'removeJustification.required' => 'Informe a justificativa para remoção.',
            'removeJustification.min' => 'A justificativa deve ter pelo menos 5 caracteres.',
        ]);

        // Delete file from storage
        if ($this->custodyLog->signed_term_url) {
            Storage::disk('public')->delete($this->custodyLog->signed_term_url);
        }

        $this->custodyLog->update([
            'signed_term_url' => null,
        ]);

        $this->closeRemoveModal();
        $this->custodyLog->refresh();

        $this->dispatch('notify', message: 'Documento removido com sucesso.', type: 'info');
    }

    public function downloadSignedDocument()
    {
        if ($this->custodyLog->signed_term_url) {
            return Storage::disk('public')->download($this->custodyLog->signed_term_url);
        }
    }

    // Check-in Methods
    public function openCheckinModal()
    {
        $this->showCheckinModal = true;
    }

    public function closeCheckinModal()
    {
        $this->showCheckinModal = false;
        $this->checkinJustification = '';
    }

    public function performCheckin()
    {
        $this->validate([
            'checkinJustification' => 'required|string|min:5',
        ], [
            'checkinJustification.required' => 'Informe o motivo da baixa.',
            'checkinJustification.min' => 'O motivo deve ter pelo menos 5 caracteres.',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () {
            $this->custodyLog->update([
                'checkin_date' => now(),
                'notes' => $this->custodyLog->notes
                    ? $this->custodyLog->notes . "\n\n[BAIXA] " . now()->format('d/m/Y H:i') . ": " . $this->checkinJustification
                    : "[BAIXA] " . now()->format('d/m/Y H:i') . ": " . $this->checkinJustification,
            ]);

            // Release assets
            foreach ($this->custodyLog->assets as $asset) {
                $asset->update(['status' => 'DISPONIVEL', 'custodian_user_id' => null]);
            }
        });

        $this->closeCheckinModal();
        $this->custodyLog->refresh();

        $this->dispatch('notify', message: 'Cautela baixada com sucesso!', type: 'success');
    }

    public function render()
    {
        return view('livewire.custody.show')->layout('layouts.sgaiti');
    }
}
