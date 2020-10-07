<?php


class QuestionSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $data = [];
        $now  = now();
        for ($i = 1;$i<=100;$i++) {
            $question = factory(\App\Models\Question::class)->make([
                'user_id' => random_int(1,10),
            ]);

            $data[] = [
                'title' => $question->title,
                'content' => $question->content,
                'user_id' => $question->user_id,
                'created_at' => $now,
                'updated_at' => $now,
                'published_at' => $now,
            ];
        }
        \App\Models\Question::insert($data);
    }
}