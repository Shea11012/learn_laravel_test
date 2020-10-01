<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewQuestionsTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_view_questions()
    {
        $this->withoutExceptionHandling();
        $test = $this->get('questions');

        $test->assertStatus(200);
    }
}
