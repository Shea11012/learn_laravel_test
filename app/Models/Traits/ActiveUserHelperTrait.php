<?php


namespace App\Models\Traits;


use App\Models\Answer;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelperTrait
{
    protected $users = [];

    protected $question_weight = 4;
    protected $answer_weight = 1;
    protected $pass_days = 7;   // 多少天发表过内容
    protected $user_number = 6; // 取出多少用户

    protected $cache_key = 'zhihu_active_users';
    protected $cache_expire_in_seconds = 60 * 60;

    public function getActiveUsers()
    {
        return Cache::remember($this->cache_key,$this->cache_expire_in_seconds,function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $activeUsers = $this->calculateActiveUsers();
        $this->cacheActiveUsers($activeUsers);
    }

    private function calculateActiveUsers()
    {
        $this->calculateQuestionScore();
        $this->calculateAnswerScore();

        $users = Arr::sort($this->users,function ($user) {
            return $user['score'];
        });

        $users = array_reverse($users,true);

        $users = array_slice($users,0,$this->user_number,true);

        $activeUsers = collect();
        foreach ($users as $userId => $user) {
            $user = $this->find($userId);
            if ($user) {
                $activeUsers->push($user);
            }
        }

        return $activeUsers;
    }

    private function calculateQuestionScore()
    {
        // 找出所有用户发布的问题数量，时间要在7天内
        $questionUsers = Question::query()->select(DB::raw('user_id,count(*) as question_count'))
            ->where('created_at','>=',Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        foreach ($questionUsers as $questionUser) {
            if ($questionUser->question_count > 0) {
                $this->users[$questionUser->user_id]['score'] = $questionUser->question_count * $this->question_weight;
            }
        }
    }

    private function calculateAnswerScore()
    {
        $answerUsers = Answer::query()->select(DB::raw('user_id,count(*) as answer_count'))
            ->where('created_at','>=',Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        foreach ($answerUsers as $answerUser) {
            if ($answerUser->answer_count > 0) {
                $answerScore = $answerUser->answer_count * $this->answer_weight;
                if (isset($this->users[$answerUser->user_id])) {
                    $this->users[$answerUser->user_id]['score'] += $answerScore;
                } else {
                    $this->users[$answerUser->user_id]['score'] = $answerScore;
                }
            }
        }
    }

    private function cacheActiveUsers($activeUsers)
    {
        Cache::put($this->cache_key,$activeUsers,$this->cache_expire_in_seconds);
    }
}