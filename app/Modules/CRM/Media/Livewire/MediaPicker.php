<?php

namespace App\Modules\CRM\Media\Livewire;

use App\Modules\CRM\Media\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class MediaPicker extends Component
{
    use WithFileUploads, WithPagination;

    public $show = false;
    public $targetField = '';
    public $files = [];
    public $search = '';

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'file|max:10240', // 10MB Max
        ]);

        foreach ($this->files as $file) {
            $path = $file->store('media', 'public');
            Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
            ]);
        }

        $this->files = [];
        $this->resetPage();
        session()->flash('success', 'Media uploaded successfully!');
    }

    #[On('open-media-picker')]
    public function open($field = 'image')
    {
        $this->targetField = $field;
        $this->show = true;
        $this->resetPage();
    }

    public function close()
    {
        $this->show = false;
        $this->targetField = '';
    }

    public function selectMedia($path)
    {
        $media = Media::where('file_path', $path)->first();
        $this->dispatch('media-selected', 
            path: $path, 
            field: $this->targetField,
            name: $media ? $media->file_name : basename($path),
            mime_type: $media ? $media->mime_type : '',
            size: $media ? $media->size : 0
        );
        $this->close();
    }

    public function render()
    {
        $mediaFiles = Media::where('file_name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(12);

        return view('modules.crm.media.media-picker', [
            'mediaFiles' => $mediaFiles,
        ]);
    }
}
