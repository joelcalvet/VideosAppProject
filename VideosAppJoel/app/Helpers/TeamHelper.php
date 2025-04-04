<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\User;

class TeamHelper
{
    public static function addPersonalTeam(User $user)
    {
        $team = Team::forceCreate([
            'name' => $user->name . ' Team',
            'user_id' => $user->id,
            'personal_team' => true,
        ]);

        $user->current_team_id = $team->id;
        $user->save();

        return $team;
    }
}
