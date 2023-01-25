<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPerson
{
    public function __invoke(Person $person): JsonResource
    {
        return PersonResource::make($person);
    }
}
