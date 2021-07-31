<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed TaskStatusesTableSeeder');
    }

    public function testIndex(): void
    {
        $response = $this->get('/task_statuses');
        $response->assertOk();
        $this->assertDatabaseHas('task_statuses', ['name' => 'на тестировании']);
        $response->assertSeeTextInOrder(
            ['новый', 'в работе', 'на тестировании', 'завершен'],
            true
        );
    }
    public function testCreate(): void
    {
        $response = $this->get('/task_statuses/create');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Создать статус', 'Имя', 'Создать'],
            true
        );
    }
    public function testStore(): void
    {
        $response = $this->post(route('task_statuses.store'), ['name' => 'testStatus']);
        $this->assertDatabaseHas('task_statuses', ['name' => 'testStatus']);
    }
    /*public function testDestroy(): void
    {
        $response = $this->post(route('task_statuses.store'), ['name' => 'testStatus']);
        $status = TaskStatus::find($response->id);
        //$status->delete();
        $this->post(route('task_statuses.store'), ['name' => 'testStatus']);
        $this->assertDeleted($status);
        $this->assertDatabaseMissing('task_statuses', ['name' => 'testStatus']);
    }*/

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
