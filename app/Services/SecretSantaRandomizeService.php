<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Facades\DB;

class SecretSantaRandomizeService
{
    private ?int $lastPersonId = null;

    public function runGame(): void
    {
        if (is_null($this->lastPersonId)) {
            $this->lastPersonId = Person::query()->orderByDesc('id')->first()->getKey();
        }

        /** @var Person $personToProceed */
        $personToProceed = Person::query()
            ->whereNull('from_person_id')
            ->orWhereNull('to_person_id')
            ->limit(1)
            ->first();

        DB::beginTransaction();

        try {
            if ($this->isNeedToSetSanta($personToProceed)) {
                $this->setSanta($personToProceed);
            }

            if ($this->isNeedToSetReceiver($personToProceed)) {
                $this->setReceiver($personToProceed);
            }

            if ($this->isLastPerson($personToProceed)) {
                $this->proceedLastPerson($personToProceed);
            }
        } catch (\Exception) {
            DB::rollBack();
        }

        DB::commit();

        $hasWork = Person::query()
            ->whereNull('from_person_id')
            ->orWhereNull('to_person_id')
            ->exists();

        if ($hasWork) {
            $this->runGame();
        }
    }

    private function proceedLastPerson(Person $person): void
    {
        $availablePerson = Person::query()
            ->where('from_person_id')
            ->where('to_person_id')
            ->first();

        $person->from_person_id = $availablePerson->getKey();
        $person->to_person_id = $availablePerson->getKey();
        $person->saveQuietly();
    }

    private function setSanta(Person $person): void
    {
        $availableSanta = Person::query()
            ->whereNot('id', $person->getKey())
            ->whereNull('to_person_id');

        $santa = $availableSanta->inRandomOrder()->first();

        $person->from_person_id = $santa->getKey();
        $person->saveQuietly();
    }

    private function setReceiver(Person $person): void
    {
        $availableSanta = Person::query()
            ->whereNot('id', $person->getKey())
            ->whereNull('from_person_id');

        $santa = $availableSanta->inRandomOrder()->first();

        $person->to_person_id = $santa->getKey();
        $person->saveQuietly();
    }

    private function isLastPerson(Person $person): bool
    {
        return $person->getKey() === $this->lastPersonId;
    }

    private function isNeedToSetSanta(Person $person): bool
    {
        return !$this->isLastPerson($person) && is_null($person->from_person_id);
    }

    private function isNeedToSetReceiver(Person $person): bool
    {
        return !$this->isLastPerson($person) && is_null($person->to_person_id);
    }
}
