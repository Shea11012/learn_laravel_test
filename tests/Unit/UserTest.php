<?php
namespace Tests\Unit;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_an_avatar_path()
    {
        $user = create(User::class,[
            'avatar_path' => 'http://example.com/avatar.png',
        ]);

        self::assertEquals('http://example.com/avatar.png',$user->avatar_path);
    }

    /** @test */
    public function user_can_determine_avatar_path()
    {
        $user = create(User::class);
        self::assertEquals(url('storage/avatars/default.png'),$user->avatar());
        $user->avatar_path = 'avatars/me.jpg';
        self::assertEquals(url('storage/avatars/me.jpg'),$user->avatar());
    }

    /** @test */
    public function can_get_user_avatar_attribute()
    {
        $user = create(User::class,[
            'avatar_path' => 'avatars/example.png',
        ]);
        self::assertEquals(url('storage/avatars/example.png'),$user->userAvatar);
    }

    /** @test */
    public function a_user_has_many_activities()
    {
        $user = create(User::class);
        create(Activity::class,['user_id' => $user->id]);
        self::assertInstanceOf(HasMany::class,$user->activities());
    }
}