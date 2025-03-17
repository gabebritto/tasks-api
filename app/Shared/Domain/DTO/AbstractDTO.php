<?php

namespace App\Shared\Domain\DTO;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractDTO extends JsonResource
{
    public function all(): array
    {
        $attributes = get_object_vars($this);
        unset($attributes['additional'], $attributes['with'], $attributes['resource']);

        return $attributes;
    }

    public function toArray(Request $request): array
    {
        return $this->all();
    }
}
