<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
    public static function create_superadmin_user()
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@videosapp.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'super_admin' => true,
            ]
        );

        if (!$user->hasPermissionTo('manage videos')) {
            $user->givePermissionTo('manage videos');
        }
        if (!$user->hasPermissionTo('manage users')) {
            $user->givePermissionTo('manage users');
        }

        return $user;
    }


    public static function create_regular_user()
    {
        $user = User::firstOrCreate(
            ['email' => 'regularuser@videosapp.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );

        $user->syncPermissions([]); // Buida tots els permisos si en tenia algun

        return $user;
    }


    public static function create_video_manager_user()
    {
        $user = User::firstOrCreate(
            ['email' => 'videomanager@videosapp.com'],
            [
                'name' => 'Video Manager',
                'password' => Hash::make('password'),
            ]
        );

        // Afegir permís
        if (!$user->hasPermissionTo('manage videos')) {
            $user->givePermissionTo('manage videos');
        }

        $user->revokePermissionTo('manage users');

        return $user;
    }


    public static function createDefaultUser()
    {
        if (!User::where('email', config('users.default.email'))->exists()) {
            $user = User::create([
                'name' => config('users.default.name'),
                'email' => config('users.default.email'),
                'password' => Hash::make(config('users.default.password')),
                'super_admin' => false,
            ]);

            TeamHelper::addPersonalTeam($user);

            return $user;
        }
    }

    public static function createDefaultTeacher()
    {
        if (!User::where('email', config('users.teacher.email'))->exists()) {
            $teacher = User::create([
                'name' => config('users.teacher.name'),
                'email' => config('users.teacher.email'),
                'password' => Hash::make(config('users.teacher.password')),
                'super_admin' => true,
            ]);

            TeamHelper::addPersonalTeam($teacher);

            if (!$teacher->hasPermissionTo('manage videos')) {
                $teacher->givePermissionTo('manage videos');
            }
            if (!$teacher->hasPermissionTo('manage users')) {
                $teacher->givePermissionTo('manage users');
            }

            return $teacher;
        }
    }
}
