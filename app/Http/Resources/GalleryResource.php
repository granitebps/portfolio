<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            /** @phpstan-ignore-next-line */
            'id' => $this->id,
            /** @phpstan-ignore-next-line */
            'name' => $this->name,
            /** @phpstan-ignore-next-line */
            'created_at' => $this->created_at,
            /** @phpstan-ignore-next-line */
            'ext' => $this->ext,
            /** @phpstan-ignore-next-line */
            'size' => $this->size,
            /** @phpstan-ignore-next-line */
            'file' => $this->getFileUrl($this->file)
        ];
    }

    private function getFileUrl(null|string $file): string
    {
        if ($file) {
            return Storage::url($file);
        }
        return '';
    }
}
