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
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'ext' => $this->ext,
            'size' => $this->size,
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
