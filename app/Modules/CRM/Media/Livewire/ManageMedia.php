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

    protected $rules = [
        'files.*' => 'file|mimes:pdf,xls,xlsx,txt,mp4,avi,mov,wmv,flv,mkv,webm,doc,docx,zip,csv,ppt,pptx,jpg,jpeg,png,gif,webp,svg|max:102400', // 100MB Max per file
    ];

    // Listen for file updates to process uploads immediately
    public function updatedFiles()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $path = $file->store('media', 'public');
            Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        $this->reset('files');
        $this->dispatch('notify', ['message' => 'Files uploaded successfully!', 'type' => 'success']);
    }

    public function deleteMedia($id)
    {
        $media = Media::findOrFail($id);
        
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $media->delete();
        $this->dispatch('notify', ['message' => 'File deleted successfully!', 'type' => 'success']);
    }

    public function render()
    {
        $mediaFiles = Media::latest()->paginate(24);

        // Determine if we are in admin or staff route
        $layout = request()->routeIs('staff.*') ? 'layouts.staff' : 'layouts.admin';

        return view('modules.crm.media.manage-media', [
            'mediaFiles' => $mediaFiles,
        ])->layout($layout)->layoutData(['title' => 'Media Library - Aspire Hub']);
    }
}
