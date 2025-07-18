<?php

declare(strict_types=1);

namespace App\Traits;

use stdClass;

trait ValidatedAsObject
{
    /**
     * Convert the validated data array to a stdClass object (deep conversion).
     */
    public function validatedObject(): stdClass
    {
        $validated = $this->validated();

        // Force conversion even if $validated is empty
        return $this->toObject($validated);
    }

    /**
     * Recursively convert array to stdClass.
     */
    private function toObject(array $array): stdClass
    {
        $object = new stdClass();

        foreach ($array as $key => $value) {
            $object->{$key} = is_array($value) ? $this->toObject($value) : $value;
        }

        return $object;
    }
}
