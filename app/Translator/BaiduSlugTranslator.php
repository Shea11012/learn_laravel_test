<?php


namespace App\Translator;


use GuzzleHttp\Client;
use Illuminate\Support\Str;

class BaiduSlugTranslator implements Translator
{

    public function translate(string $sentence)
    {
        $http = new Client();
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        if (empty($appid) || empty($key)) {
            return '';
        }

        $sign = md5($appid.$sentence.$salt.$key);
        $query = http_build_query([
            'q' => $sentence,
            'from' => 'zh',
            'to' => 'en',
            'appid' => $appid,
            'salt' => $salt,
            'sign' => $sign,
        ]);

        $response = $http->get($api.$query);

        $result = json_decode($response->getBody(),true);
        if (isset($result['trans_result'][0]['dst'])) {
            return Str::slug($result['trans_result'][0]['dst']);
        }

        return '';
    }
}