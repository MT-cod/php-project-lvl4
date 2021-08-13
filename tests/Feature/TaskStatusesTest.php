<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskStatusesTest extends TestCase
{
    use RefreshDatabase;

    private string $testStatusName;
    private \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed TaskStatusesTableSeeder');
        $faker = \Faker\Factory::create();
        $this->testStatusName = $faker->word();
        $this->testUser = User::factory()->create();
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
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/task_statuses/create');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Создать статус', 'Имя', 'Создать'],
            true
        );
    }
    public function testStore(): void
    {
        $response = $this->post(route('task_statuses.store'), ['name' => $this->testStatusName]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->post(route('task_statuses.store'), ['name' => $this->testStatusName]);
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testStatusName]);
        $response = $this->get('/task_statuses');
        $response->assertSeeTextInOrder(['Статус успешно создан'], true);
    }
    public function testEdit(): void
    {
        $response = $this->get('/task_statuses/1/edit');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/task_statuses/1/edit');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Изменение статуса', 'Имя', 'Обновить'],
            true
        );
    }
    public function testUpdate(): void
    {
        $response = $this->post(route('task_statuses.update', 1), ['_method' => 'PATCH', 'name' => $this->testStatusName]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->post(route('task_statuses.update', 1), ['_method' => 'PATCH', 'name' => $this->testStatusName]);
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testStatusName]);
        $response = $this->get('/task_statuses');
        $response->assertSeeTextInOrder(['Статус успешно изменён'], true);
    }
    public function testDestroy(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('task_statuses.store'), ['name' => $this->testStatusName]);
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testStatusName]);
        $status = TaskStatus::firstWhere('name', $this->testStatusName);
        Auth::logout();
        if ($status) {
            $response = $this->post(route('task_statuses.destroy', $status->id), ['_method' => 'DELETE']);
            $response->assertStatus(403);
            Auth::loginUsingId(1);
            $this->post(route('task_statuses.destroy', $status->id), ['_method' => 'DELETE']);
            $this->assertDeleted($status);
            $this->assertDatabaseMissing('task_statuses', ['name' => $this->testStatusName]);
            $response = $this->get('/task_statuses');
            $response->assertSeeTextInOrder(['Статус успешно удалён'], true);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
