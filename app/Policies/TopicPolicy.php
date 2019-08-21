<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        // 如果用户拥有管理内容的权限的话，即授权通过
        if ($user->can('manage_contents')) {
            return true;
        }
    }

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
