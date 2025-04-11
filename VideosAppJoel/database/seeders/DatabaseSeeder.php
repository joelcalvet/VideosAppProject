<?php

namespace Database\Seeders;

use App\Helpers\SeriesHelper;
use App\Helpers\TeamHelper;
use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;
use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
        ]);

        UserHelper::createDefaultUser();
        UserHelper::createDefaultTeacher();
        VideoHelper::createDefaultVideo();

        $superAdmin = UserHelper::create_superadmin_user();
        $regularUser = UserHelper::create_regular_user();
        $videoManager = UserHelper::create_video_manager_user();

        $superAdmin->assignRole('super-admin');
        $videoManager->givePermissionTo('manage videos');

        TeamHelper::addpersonalteam($superAdmin);
        TeamHelper::addpersonalteam($regularUser);
        TeamHelper::addpersonalteam($videoManager);

        SeriesHelper::create_series();
    }
}
