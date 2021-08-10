<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Label;

class LabelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::create([ 'name' => '1111', 'description' => '11111']);
        Label::create([ 'name' => '2222', 'description' => '22222']);
        Label::create([ 'name' => '3333', 'description' => '33333']);
        Label::create([ 'name' => '4444', 'description' => '44444']);
    }
}
