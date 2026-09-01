<?php

namespace App\Services\Search;

use App\Search\Contracts\SearchProviderInterface;
use App\Search\Providers\LocalSearchProvider;
use Illuminate\Database\Eloquent\Model;

class IndexingService
{
    public function getProvider(): SearchProviderInterface
    {
        return new LocalSearchProvider;
    }

    /**
     * Generic indexing for any platform model
     */
    public function indexModel(Model $model, int $categoryId, string $title, ?string $description = null, array $meta = [])
    {
        $data = [
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'keywords' => $this->extractKeywords($title, $description),
            'country_id' => $meta['country_id'] ?? null,
            'status' => true,
        ];

        return $this->getProvider()->index(get_class($model), $model->id, $data);
    }

    protected function extractKeywords($title, $description): string
    {
        $text = strtolower($title.' '.$description);
        $tokens = array_unique(explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $text)));

        return implode(' ', array_filter($tokens));
    }
}
