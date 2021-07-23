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
/*    public function testShowUrls(): void
    {
        $this->post(route('urls.create'), ['url' => ['name' => 'http://example.com']]);
        $response = $this->get(route('urls.store'));
        $response->assertOk();
        $response->assertSee('http://example.com', true);
    }
    public function testShowUrl(): void
    {
        $this->post(route('urls.create'), ['url' => ['name' => 'http://example.com']]);
        $response = $this->get(route('urls.show', ['id' => 1]));
        $response->assertOk();
        $response->assertSee('http://example.com', true);
    }
    public function testCheckUrl(): void
    {
        Http::fake(['*' => Http::response('Hello World', 222, ['Headers'])]);
        $this->post(route('urls.create'), ['url' => ['name' => 'http://example.com']]);
        $this->post('/urls/{id}/checks', ['id' => 1]);
        $this->assertDatabaseHas('url_checks', ['id' => 1, 'url_id' => 1, 'status_code' => 222]);
    }*/

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
