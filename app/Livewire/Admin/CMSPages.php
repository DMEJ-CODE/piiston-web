<?php

namespace App\Livewire\Admin;

use App\Models\Administration\CMSPage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CMSPages extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingPage = null;

    public $form = [
        'title' => '',
        'slug' => '',
        'content' => '',
        'status' => false,
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal($pageId = null)
    {
        if ($pageId) {
            $this->editingPage = CMSPage::findOrFail($pageId);
            $this->form = [
                'title' => $this->editingPage->title,
                'slug' => $this->editingPage->slug,
                'content' => $this->editingPage->content,
                'status' => $this->editingPage->status,
            ];
        } else {
            $this->form = ['title' => '', 'slug' => '', 'content' => '', 'status' => false];
        }
        $this->showModal = true;
    }

    public function savePage()
    {
        $this->validate([
            'form.title' => 'required|string|max:200',
            'form.slug' => 'required|string|max:100|unique:cms_pages,slug'.($this->editingPage ? ','.$this->editingPage->id : ''),
            'form.content' => 'required|string',
            'form.status' => 'boolean',
        ]);

        if ($this->editingPage) {
            $this->editingPage->update($this->form);
            $this->dispatch('notify', message: 'Page updated successfully');
        } else {
            CMSPage::create($this->form);
            $this->dispatch('notify', message: 'Page created successfully');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deletePage($pageId)
    {
        CMSPage::findOrFail($pageId)->delete();
        $this->dispatch('notify', message: 'Page deleted successfully');
    }

    public function resetForm()
    {
        $this->form = ['title' => '', 'slug' => '', 'content' => '', 'status' => false];
        $this->editingPage = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $pages = CMSPage::when($this->search, function ($query) {
            $query->where('title', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%");
        })
            ->paginate(15);

        return view('livewire.admin.cms-pages', [
            'pages' => $pages,
        ]);
    }
}
