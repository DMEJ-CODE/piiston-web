<?php

namespace App\Http\Resources\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->part->name ?? null,
            'part_number' => $this->part->part_number ?? null,
            'brand' => $this->part->brand->name ?? null,
            'category' => $this->part->category->name ?? null,
            'price' => $this->price,
            'currency' => $this->currency->code ?? null,
            'quantity' => $this->quantity,
            'condition' => $this->condition,
            'availability' => $this->availability,
            'seller' => [
                'id' => $this->seller->id ?? null,
                'name' => $this->seller->business_name ?? null,
                'rating' => $this->seller->rating ?? 0,
                'verification_status' => $this->seller->verification_status ?? 'pending',
            ],
            'media' => $this->whenLoaded('documents', function () {
                return $this->documents->map(function ($doc) {
                    return [
                        'url' => $doc->file_path,
                        'type' => $doc->mediaFile->media_type ?? 'IMAGE',
                        'thumbnail' => $doc->mediaFile->thumbnail ?? null,
                    ];
                });
            }),
            'social' => [
                'likes_count' => $this->likes_count ?? 0,
                'comments_count' => $this->comments_count ?? 0,
                'is_liked' => (bool) ($this->is_liked ?? false),
            ],
            'created_at' => $this->created_at,
        ];
    }
}
