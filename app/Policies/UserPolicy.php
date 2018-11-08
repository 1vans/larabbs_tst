<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    //判断当前用户和进行授权的用户是否同一人
    public function update(User $currenUser,User $user)
    {
            return $currenUser->id === $user->id;
    }
}
