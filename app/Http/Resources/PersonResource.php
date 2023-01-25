<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Person $resource
 */
class PersonResource extends JsonResource
{
    public function toArray($request): array
    {
        $person = $this->resource;
        $receiver = Person::query()->where('id', $person->to_person_id)->first();

        return [
            'id' => $person->getKey(),
            'Имя' => $person->name,
            'Тайный Cанта для' => $receiver->toArray(),
        ];
    }
}
