<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    private string $testTaskName;
    private string $testTaskDescription;
    private \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed TaskStatusesTableSeeder');
        $faker = \Faker\Factory::create();
        $this->testTaskName = $faker->word();
        $this->testTaskDescription = $faker->text();
        $this->testUser = User::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get('/tasks');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Задачи', 'ID', 'Статус', 'Имя', 'Автор', 'Исполнитель', 'Дата создания'],
            true
        );
        $response->assertDontSeeText(['Создать задачу'], true);
        Auth::loginUsingId(1);
        $response = $this->get('/tasks');
        $response->assertSeeTextInOrder(['Создать задачу'], true);
    }
    public function testCreate(): void
    {
        $response = $this->get('/tasks/create');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/tasks/create');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Создать задачу', 'Имя', 'Описание', 'Статус', 'Исполнитель', 'Метки', 'Создать'],
            true
        );
    }
    public function testStore(): void
    {
        $response = $this->storeTestTask();
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->storeTestTask();
        $this->assertDatabaseHas('tasks', ['name' => $this->testTaskName]);
        $response = $this->get('/tasks');
        $response->assertSeeTextInOrder(['Задача успешно создана'], true);
    }
    public function testShow(): void
    {
        Auth::loginUsingId(1);
        $this->storeTestTask();
        $response = $this->get('/tasks/1');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Просмотр задачи:', 'Имя:', 'Статус:', 'Описание:'],
            true
        );
    }
    public function testEdit(): void
    {
        Auth::loginUsingId(1);
        $this->storeTestTask();
        Auth::logout();
        $response = $this->get('/tasks/1/edit');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/tasks/1/edit');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Изменение задачи', 'Имя', 'Описание', 'Статус', 'Исполнитель', 'Метки', 'Обновить'],
            true
        );
    }
    public function testUpdate(): void
    {
        Auth::loginUsingId(1);
        $this->storeTestTask();
        Auth::logout();
        $response = $this->post(route('tasks.update', 1), [
            '_method' => 'PATCH',
            'name' => 'testUpdateName',
            'status_id' => 1
        ]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->post(route('tasks.update', 1), [
            '_method' => 'PATCH',
            'name' => 'testUpdateName',
            'status_id' => 1
        ]);
        $this->assertDatabaseHas('tasks', ['name' => 'testUpdateName']);
        $response = $this->get('/tasks');
        $response->assertSeeTextInOrder(['Задача успешно изменена'], true);
    }
    public function testDestroy(): void
    {
        Auth::loginUsingId(1);
        $this->storeTestTask();
        $task = Task::firstWhere('name', $this->testTaskName);
        if (!is_null($task)) {
            $this->post(route('tasks.destroy', $task->id), ['_method' => 'DELETE']);
            $this->assertDeleted($task);
            $this->assertDatabaseMissing('tasks', ['name' => $this->testTaskName]);
            $response = $this->get('/tasks');
            $response->assertSeeTextInOrder(['Задача успешно удалена'], true);
        }
    }

    private function storeTestTask(): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('tasks.store'), [
            'name' => $this->testTaskName,
            'description' => $this->testTaskDescription,
            'status_id' => 1,
            'created_by_id' => 1,
            'assigned_to_id' => 1
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
