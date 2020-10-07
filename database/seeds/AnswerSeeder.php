<?php


class AnswerSeeder  extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $data = [];
        $now = \Carbon\Carbon::now();
        for ($i = 1;$i<=100;$i++) {
            $answer = factory(\App\Models\Answer::class)->make([
                'user_id' => random_int(1,10),
                'question_id' => random_int(1,100),
            ]);

            $data[] = [
                'content' => $answer->content,
                'user_id' => $answer->user_id,
                'question_id' => $answer->question_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        \App\Models\Answer::insert($data);
    }
}