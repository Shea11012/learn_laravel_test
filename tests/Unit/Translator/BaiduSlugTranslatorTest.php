<?php


namespace Tests\Unit\Translator;


use App\Translator\BaiduSlugTranslator;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class BaiduSlugTranslatorTest
 * @group online
 * @package Tests\Unit\Translator@
 */
class BaiduSlugTranslatorTest extends TestCase
{
    /** @test */
    public function can_translate_chinese_to_english()
    {
        $translator = new BaiduSlugTranslator();
        $result = $translator->translate('英语英语');
        self::assertEquals("english-english",Str::lower($result));
    }
}