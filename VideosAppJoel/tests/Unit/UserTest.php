<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_super_admin()
    {
        $user = new User([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'super_admin' => true,
        ]);
        $user->save();

        $this->assertTrue($user->isSuperAdmin());
    }

    public function test_user_is_not_super_admin()
    {
        $user = new User([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'super_admin' => false,
        ]);
        $user->save();

        $this->assertFalse($user->isSuperAdmin());
    }
}
