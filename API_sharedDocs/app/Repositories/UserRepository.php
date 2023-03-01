<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function create(array $attributes)
    {
       return DB::transaction( function() use($attributes)
       {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email'),
                'password' => data_get($attributes, 'password')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create the user.');

            return $created;
       });
    }

    public function update($user, $attributes)
    {
        return DB::transaction( function() use($user, $attributes) {
            $updated = $user->update([
                'name' => data_get($attributes, 'name', $user->name),
                'email' => data_get($attributes, 'email', $user->email),
                'password' => data_get($attributes, 'password', $user->password)
            ]);

            // if(!$updated) {
            //     throw new \Exception('Failed to update user');
            // }
            throw_if(!$updated, GeneralJsonException::class, 'Failed to update the user.');

            return $user;
        });
    }

    public function forceDelete($user)
    {
        return DB::transaction( function() use($user) {
            $deleted = $user->forceDelete();

            // if(!$deleted) {
            //     throw new \Exception('Cannot delete user');
            // }
            throw_if(!$deleted, GeneralJsonException::class, 'Failed to update the user.');

            return $deleted;
        });
    }
}
