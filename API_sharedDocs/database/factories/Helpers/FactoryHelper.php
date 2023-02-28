<?php

namespace Database\Factories\Helpers;

class FactoryHelper
{
    /**
     * This function will get a random id from the DB
     * @param string | HasFactory $model
     */
    public static function getRandomModelId(string $model)
    {
        // get model count
        $count = $model::query()->count();

        if ($count === 0)
        {
            // if model count is 0, create new record and retrieve the record id
            return $model::factory()->create()->id;

        } else {
            // generate radom number between 1 and model count
            return rand(1, $count);
        }
    }
}
