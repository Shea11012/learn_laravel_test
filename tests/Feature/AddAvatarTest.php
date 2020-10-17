<?php
namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_add_avatars()
    {
        $this->expectException(AuthenticationException::class);
        $this->post(route('user-avatar.store',[1]));
    }

    /** @test */
    public function avatar_is_required()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $this->post(route('user-avatar.store',[\Auth::user()]),['avatar' => null])
            ->assertJsonValidationErrors('avatar');
    }

    /** @test */
    public function avatar_must_be_valid()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $this->post(route('user-avatar.store',[auth()->user()]),['avatar' => 'not-an-image'])
            ->assertJsonValidationErrors('avatar');
    }

    /** @test */
    public function poster_image_must_be_at_least_200px_width()
    {
        $this->withExceptionHandling();
        $this->signIn();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.png',199,516);

        $this->post(route('user-avatar.store',[\Auth::user()]),['avatar' => $file])
            ->assertJsonValidationErrors('avatar');
    }

    /** @test */
    public function poster_image_must_be_at_least_200px_height()
    {
        $this->withExceptionHandling();
        $this->signIn();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.png',516,199);
        $this->post(route('user-avatar.store',[\Auth::user()]),['avatar' => $file])
            ->assertJsonValidationErrors('avatar');
    }

    /** @test */
    public function user_can_add_an_avatar()
    {
        $this->signIn();
        Storage::fake('public');

        $this->post(route('user-avatar.store',[\Auth::user()]),[
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg',300,300),
        ]);

        self::assertEquals('avatars/'.$file->hashName(),\Auth::user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }
}