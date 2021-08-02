<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private string $testStatusName;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed TaskStatusesTableSeeder');
        $faker = \Faker\Factory::create();
        $this->testStatusName = $faker->word();
        var_dump($this->testStatusName);
        $this->post(route('task_statuses.store'), ['name' => $this->testStatusName]);
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
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testStatusName]);
    }
    public function testEdit(): void
    {
        $response = $this->get('/task_statuses/1/edit');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Изменение статуса', 'Имя', 'Обновить'],
            true
        );
    }
    public function testUpdate(): void
    {
        $this->post(route('task_statuses.update', 1), ['_method' => 'PATCH', 'name' => 'test']);
        $this->assertDatabaseHas('task_statuses', ['name' => 'test']);
    }
    public function testDestroy(): void
    {
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testStatusName]);
        $status = TaskStatus::firstWhere('name', $this->testStatusName);
        $this->post(route('task_statuses.destroy', $status->id), ['_method' => 'DELETE']);
        $this->assertDeleted($status);
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->testStatusName]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
