<?php


namespace App\Support;


class Api
{
    /**
     *
     * @return string
     * Возвращает содержимое страницы, переданной в $url.
     *
     * @param string $url
     */
    public static function getQuery($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $cookie = tmpfile();
            $userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31' ;

            $ch = curl_init($url);
            $options = [
                CURLOPT_CONNECTTIMEOUT => 20 ,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_AUTOREFERER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_COOKIEFILE => $cookie,
                CURLOPT_COOKIEJAR => $cookie ,
                CURLOPT_SSL_VERIFYPEER => 0 ,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];
            curl_setopt_array($ch, $options);
            $res = curl_exec($ch);
            curl_close($ch);

            return $res;
        }
        return null;
    }
}
