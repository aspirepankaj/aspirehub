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
        $field = strtolower($this->targetField);
        if (\Illuminate\Support\Str::contains($field, ['image', 'logo', 'avatar', 'photo'])) {
            $rules = 'file|image|max:102400';
        } elseif (\Illuminate\Support\Str::contains($field, ['video'])) {
            $rules = 'file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/webm,video/x-matroska,video/x-flv,video/x-ms-wmv|max:102400';
        } else {
            $rules = 'file|mimes:pdf,xls,xlsx,txt,mp4,avi,mov,wmv,flv,mkv,webm,doc,docx,zip,csv,ppt,pptx,jpg,jpeg,png,gif,webp,svg|max:102400';
        }

        $this->validate([
            'files.*' => $rules,
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
        $query = Media::where('file_name', 'like', '%' . $this->search . '%');

        $field = strtolower($this->targetField);
        if (\Illuminate\Support\Str::contains($field, ['image', 'logo', 'avatar', 'photo'])) {
            $query->where('mime_type', 'like', 'image/%');
        } elseif (\Illuminate\Support\Str::contains($field, ['video'])) {
            $query->where('mime_type', 'like', 'video/%');
        }

        $mediaFiles = $query->latest()->paginate(12);

        return view('modules.crm.media.media-picker', [
            'mediaFiles' => $mediaFiles,
        ]);
    }
}
