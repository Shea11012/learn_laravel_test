<?php


namespace Tests\Unit\Translator;


use App\Translator\FakeSlugTranslator;
use Illuminate\Support\Str;
use Tests\TestCase;

class FakeSlugTranslatorTest extends TestCase
{
    /** @test */
    public function can_translate_chinese_to_english()
    {
        $translator = new FakeSlugTranslator();
        $result = $translator->translate("英语 英语");

        self::assertEquals("english-english",Str::lower($result));
    }
}