<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return bool
     */
    public function update(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }

    public function destroy(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }
}
