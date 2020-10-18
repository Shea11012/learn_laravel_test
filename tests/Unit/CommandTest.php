<?php
namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_calculate_active_user_by_artisan_command()
    {
        $john = create(User::class,['name' => 'john']);
        $jane = create(User::class,['name' => 'jane']);

        // john 创建问题得 4 分
        $question = create(Question::class,['user_id' => $john->id]);
        // jane 创建答案 得 1分
        create(Answer::class,['user_id' => $jane->id,'question_id' => $question->id]);

        create(User::class,[],10);

        $code = $this->artisan('zhihu:calculate-active-user');
        self::assertEquals(0,$code);

        $activeUsers = Cache::get('zhihu_active_users');

        self::assertEquals(2,$activeUsers->count());
        self::assertTrue($john->is($activeUsers[0]));
        self::assertTrue($jane->is($activeUsers[1]));
    }
}