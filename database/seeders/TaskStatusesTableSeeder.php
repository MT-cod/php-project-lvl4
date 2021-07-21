<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskStatuses;

class TaskStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskStatuses::create([ 'name' => 'новый']);
        TaskStatuses::create([ 'name' => 'в работе']);
        TaskStatuses::create([ 'name' => 'на тестировании']);
        TaskStatuses::create([ 'name' => 'завершен']);
    }
}
