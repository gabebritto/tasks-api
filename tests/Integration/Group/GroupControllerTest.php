<?php

namespace Group;

use App\User\Infrastructure\Models\User;
use Database\Factories\GroupFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use WithFaker;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }

    public function test_group_list(): void
    {
        GroupFactory::times(5)->create();

        // Send GET request to retrieve all data
        $response = $this->actingAs($this->user)->getJson('/api/group/list', ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data'); //factory created groups
    }

    public function test_group_get_by_id(): void
    {
        $group = GroupFactory::new()->create();

        // Send GET request to retrieve customer by email
        $response = $this->actingAs($this->user)->getJson("/api/group/get-id/$group->id", ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['group']
        ]);
    }

    public function test_group_save(): void
    {
        $payload = [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'sector' => $this->faker->word(),
            'acls' => ['test', 'test1'],
            'status' => $this->faker->boolean()
        ];

        $response = $this->actingAs($this->user)->post("api/group/save", $payload, ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
    }

    public function test_group_update(): void
    {
        $group = GroupFactory::new()->create();

        $payload = [
            ...$group->toArray(),
            'title' => 'updated title',
            'description' => 'updated description'
        ];

        $response = $this->actingAs($this->user)->put("api/group/$group->id", $payload, ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertDatabaseHas('groups', ['id' => $group->id, 'title' => $payload['title'], 'description' => $payload['description']]);
    }

    public function test_group_delete(): void
    {
        $group = GroupFactory::new()->create();

        $response = $this->actingAs($this->user)->delete("api/group/$group->id", ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
