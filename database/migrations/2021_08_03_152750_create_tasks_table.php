<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->text('description')->nullable($value = true);
            $table->integer('status_id');
            $table->foreign('status_id')->references('id')->on('task_statuses');
            $table->integer('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->integer('assigned_to_id');
            $table->foreign('assigned_to_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
