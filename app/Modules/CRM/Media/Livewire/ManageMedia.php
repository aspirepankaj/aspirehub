<?php

namespace App\Modules\CRM\Media\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Modules\CRM\Media\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ManageMedia extends Component
{
    use WithFileUploads, WithPagination;

    public $files = [];
    public $search = '';
    public $category = ''; // '', 'image', 'video', 'document'

    // Media Details Modal State
    public $showDetailsModal = false;
    public $selectedMedia = null;
    public $editFileName = '';
    public $savedSuccessMessage = '';

    protected $rules = [
        'files.*' => 'file|mimes:pdf,xls,xlsx,txt,mp4,avi,mov,wmv,flv,mkv,webm,doc,docx,zip,csv,ppt,pptx,jpg,jpeg,png,gif,webp,svg|max:102400', // 100MB Max per file
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    // View media details modal
    public function viewDetails($id)
    {
        $this->selectedMedia = Media::find($id);
        if ($this->selectedMedia) {
            $this->editFileName = $this->selectedMedia->file_name;
            $this->savedSuccessMessage = '';
            $this->showDetailsModal = true;
        }
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedMedia = null;
        $this->editFileName = '';
        $this->savedSuccessMessage = '';
    }

    // Update media title
    public function updateMediaName($id, $newName)
    {
        $media = Media::find($id);
        if (!$media) return;

        $media->update([
            'file_name' => $newName,
        ]);

        $this->savedSuccessMessage = 'Title updated successfully!';
        session()->flash('success', 'Media title updated successfully!');
        $this->dispatch('notify', ['message' => 'Media title updated successfully!', 'type' => 'success']);
    }

    public function deleteSelectedMedia($id)
    {
        $this->closeDetailsModal();
        $this->deleteMedia($id);
    }

    // Listen for file updates to process uploads immediately
    public function updatedFiles()
    {
        $this->validate();

        foreach ($this->files as $file) {
            Media::uploadFile($file);
        }

        $this->reset('files');
        $this->dispatch('notify', ['message' => 'Files uploaded successfully!', 'type' => 'success']);
    }

    public function deleteMedia($id)
    {
        $media = Media::find($id);
        if ($media) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
            $media->delete();
            $this->dispatch('notify', ['message' => 'File deleted successfully!', 'type' => 'success']);
        }
    }

    public function render()
    {
        Media::syncStorageFiles();

        $query = Media::latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                  ->orWhere('file_path', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($this->category === 'video') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($this->category === 'document') {
            $query->where('mime_type', 'not like', 'image/%')
                  ->where('mime_type', 'not like', 'video/%');
        }

        $mediaFiles = $query->paginate(24);

        // Determine if we are in admin or staff route
        $layout = request()->routeIs('staff.*') ? 'layouts.staff' : 'layouts.admin';

        return view('modules.crm.media.manage-media', [
            'mediaFiles' => $mediaFiles,
        ])->layout($layout)->layoutData(['title' => 'Media Library - Aspire Hub']);
    }
}
