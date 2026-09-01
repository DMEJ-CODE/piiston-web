<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\CMSPages;
use App\Models\Administration\CMSPage;

class CMSPagesTest extends AdminTestCase
{
    public function test_can_create_cms_page()
    {
        $this->actingAsAdmin();

        $component = new CMSPages;
        $component->form = [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Test content',
            'status' => true,
        ];

        $component->savePage();

        $this->assertDatabaseHas('cms_pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'status' => true,
        ]);
    }

    public function test_can_update_cms_page_status()
    {
        $this->actingAsAdmin();

        $page = CMSPage::create([
            'title' => 'Existing Page',
            'slug' => 'existing-page',
            'content' => 'Content',
            'status' => true,
        ]);

        $component = new CMSPages;
        $component->editingPage = $page;
        $component->form = [
            'title' => 'Existing Page',
            'slug' => 'existing-page',
            'content' => 'Content',
            'status' => false,
        ];

        $component->savePage();

        $this->assertFalse($page->fresh()->status);
    }
}
