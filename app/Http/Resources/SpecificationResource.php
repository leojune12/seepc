<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request)
        return [
            'motherboard' => $this->motherboard,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'graphics' => $this->graphics,
            'storage' => $this->storage,
            'display' => $this->display,
            'keyboard' => $this->keyboard,
            'mouse' => $this->mouse
        ];
    }
}
