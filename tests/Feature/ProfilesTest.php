<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_his_profile_page()
    {
        $this->signIn();
        $user = \Auth::user();
        $this->get(route('users.show',['user' => $user]))
            ->assertSee($user->name);
    }
}