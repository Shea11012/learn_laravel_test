<?php


namespace App\Translator;


class FakeSlugTranslator implements Translator
{
    public function translate($str): string
    {
        return 'english-english';
    }
}