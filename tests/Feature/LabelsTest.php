<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LabelsTest extends TestCase
{
    use RefreshDatabase;

    private string $testLabelName;
    private \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed LabelsTableSeeder');
        $faker = \Faker\Factory::create();
        $this->testLabelName = $faker->word();
        $this->testUser = User::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get('/labels');
        $response->assertOk();
        $this->assertDatabaseHas('labels', ['name' => '1111']);
        $response->assertSeeTextInOrder(
            ['Метки', 'Имя', '1111', '11111'],
            true
        );
    }
    public function testCreate(): void
    {
        $response = $this->get('/labels/create');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/labels/create');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Создать метку', 'Имя', 'Создать'],
            true
        );
    }
    public function testStore(): void
    {
        $response = $this->post(route('labels.store'), ['name' => $this->testLabelName]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->post(route('labels.store'), ['name' => $this->testLabelName]);
        $this->assertDatabaseHas('labels', ['name' => $this->testLabelName]);
        $response = $this->get('/labels');
        $response->assertSeeTextInOrder(['Метка успешно создана'], true);
    }
    public function testEdit(): void
    {
        $response = $this->get('/labels/1/edit');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/labels/1/edit');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Изменение метки', 'Имя', 'Обновить'],
            true
        );
    }
    public function testUpdate(): void
    {
        $response = $this->post(route('labels.update', 1), ['_method' => 'PATCH', 'name' => $this->testLabelName]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->post(route('labels.update', 1), ['_method' => 'PATCH', 'name' => $this->testLabelName]);
        $this->assertDatabaseHas('labels', ['name' => $this->testLabelName]);
        $response = $this->get('/labels');
        $response->assertSeeTextInOrder(['Метка успешно изменена'], true);
    }
    public function testDestroy(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('labels.store'), ['name' => $this->testLabelName]);
        $this->assertDatabaseHas('labels', ['name' => $this->testLabelName]);
        $label = Label::firstWhere('name', $this->testLabelName);
        Auth::logout();
        if ($label) {
            $response = $this->post(route('labels.destroy', $label->id), ['_method' => 'DELETE']);
            $response->assertStatus(403);
            Auth::loginUsingId(1);
            $this->post(route('labels.destroy', $label->id), ['_method' => 'DELETE']);
            $this->assertDeleted($label);
            $this->assertDatabaseMissing('labels', ['name' => $this->testLabelName]);
            $response = $this->get('/labels');
            $response->assertSeeTextInOrder(['Метка успешно удалена'], true);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
