<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegistersValidation()
    {
        $token = factory(User::class)->make()->generateToken();
        $file = UploadedFile::fake()->image('avatar.pdf');

        $payload = [
            'avatar' => $file,
        ];

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('POST', 'api/users',$payload)
            ->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
                'avatar' => ['The avatar must be an image.']
            ]);
    }
    public function testsRegistersSuccessfully()
    {
        $token = factory(User::class)->make()->generateToken();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'toptal123',
            'role_id' => 1,
            'avatar' => $file,
        ];

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('post', '/api/users', $payload)->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'role_id',
                'email',
                'created_at',
                'updated_at',
            ]);

        // Assert the file was stored...
        Storage::disk('public_folder')->assertExists('avatars/'.$file->hashName());
    }
    public function testsUsersAreUpdatedCorrectly()
    {
        $token = factory(User::class)->make()->generateToken();

        $user = factory(User::class)->create(['avatar' => UploadedFile::fake()->image('avatar.jpg')]);

        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = 'avatars/'. $file->hashName();

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'avatar' => $file,
            'password' => 'newone',
            'role_id' => 1
        ];

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('PUT', "/api/users/$user->id", $payload)
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $payload['name'],
                'email' => $payload['email'],
                'avatar' => $path
            ]);

        // Assert the file was stored...
        Storage::disk('public_folder')->assertExists($path);

        // Assert a file does not exist...
        Storage::disk('public_folder')->assertMissing($user->avatar);

        // Assert if password change...
        $user = User::find($user->id);
        $this->assertTrue(Hash::check('newone',$user->password));
    }
    public function testsUsersAreDeletedCorrectly()
    {
        $token = factory(User::class)->make()->generateToken();

        $user = factory(User::class)->create(['avatar' => UploadedFile::fake()->image('avatar.jpg')]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('DELETE', '/api/users/' . $user->id, [])
            ->assertStatus(204);

        // Assert a file does not exist...
        Storage::disk('public_folder')->assertMissing($user->avatar);
    }
    public function testsUsersByRole()
    {
        $token = factory(User::class)->make()->generateToken();

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('GET', '/api/usersByRoleName?role=Administrador')
            ->assertStatus(200);
    }
}
