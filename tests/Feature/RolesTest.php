<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Role;
class RolesTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreValidation()
    {
        $token = factory(User::class)->make()->generateToken();

        $payload = [
            'slug' => 'admin',
            'description' => 'sd'
        ];

        $this->withoutExceptionHandling();
        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('POST', 'api/roles',$payload)
            ->assertStatus(422)
            ->assertJson([
                'name' => ['The name field is required.'],
                'slug' => ['The slug has already been taken.'],
                'description' => ['The description must be at least 5 characters.']
            ]);
    }
    public function testStoreSuccessfully()
    {
        $token = factory(User::class)->make()->generateToken();

        $name = $this->faker->unique()->realText(20);
        $payload = [
            'name' => $name,
            'slug' => str_slug($name),
            'description' => $this->faker->text(900)
        ];

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('post', '/api/roles', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'slug',
                'description'
            ]);
    }
    public function testUpdate()
    {
        $token = factory(User::class)->make()->generateToken();
        $role = factory(Role::class)->create();

        $name = $this->faker->unique()->realText(20);
        $payload = [
            'name' => $name,
            'slug' => str_slug($name),
            'description' => $this->faker->text(900)
        ];

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('PUT', "/api/roles/$role->id", $payload)
            ->assertStatus(200)
            ->assertJson([
                'id' => $role->id,
                'slug' => $payload['slug'],
                'description' => $payload['description'],
            ]);
    }
    public function testDestroy()
    {
        $token = factory(User::class)->make()->generateToken();
        $role = factory(Role::class)->create();

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('DELETE', '/api/roles/' . $role->id, [])
            ->assertStatus(204);
    }
    public function testDestroyRelationg()
    {
        $token = factory(User::class)->make()->generateToken();
        $role = factory(Role::class)->create();

        // create relation
        factory(User::class)->create(['role_id' => $role->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('DELETE', '/api/roles/' . $role->id, [])
            ->assertStatus(409)
            ->assertJson([
                'error' => 'Your role is related, can not be eliminated.'
            ]);
    }
}
