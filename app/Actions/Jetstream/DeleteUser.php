<?php

namespace App\Actions\Jetstream;

use App\Enums\States;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        $user->update([ 'state' => States::Deleted ]);
//        $user->delete();
    }
}
