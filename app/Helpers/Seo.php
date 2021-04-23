<?php


namespace App\Helpers;

use Illuminate\Support\Facades\{DB, File, Route, Schema};

class Seo
{
    /*
     * Запусть этот метод, чтобы обновить сайт.
     * \App\Helpers\Seo::getUpload();
     * $htaccess - передать true, чтобы также сформировать файл .htaccess.
     */
    public static function getUpload($htaccess = false)
    {
        self::sitemap();
        self::robots();
        self::human();
        self::errorPage();

        if ($htaccess) {
            self::htaccess();
        }
    }


    // Сформировать карту сайта
    public static function sitemap()
    {
        $itemsDb = config('add.list_of_information_block.tables');
        $routesDb = config('add.list_of_information_block.routes');
        $items = config('add.list_pages_for_sitemap_no_db.items');
        $routes = config('add.list_pages_for_sitemap_no_db.routes');
        $active = config('add.page_statuses')[1] ?: 'active';
        $date = date('Y-m-d');

        $r = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $r .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        if ($itemsDb) {
            foreach ($itemsDb as $key => $table) {

                if (Schema::hasTable($table)) {

                    $route = Route::has($routesDb[$key]) ? $routesDb[$key] : null;
                    $values = DB::table($table)->where('status', $active)->pluck('slug')->toArray();

                    if ($route && $values) {
                        foreach ($values as $slug) {
                            $r .= "\t<url>\n\t\t";
                            $r .= '<loc>' . route($route, $slug) . "</loc>\n\t\t";
                            $r .= "<lastmod>{$date}</lastmod>\n";
                            $r .= "\t</url>\n";
                        }
                    }
                }
            }
        }

        if ($items) {
            foreach ($items as $key => $page) {

                $route = Route::has($routes[$key]) ? $routes[$key] : null;
                if ($route) {
                    $r .= "\t<url>\n\t\t";
                    $r .= '<loc>' . route($route) . "</loc>\n\t\t";
                    $r .= "<lastmod>{$date}</lastmod>\n";
                    $r .= "\t</url>\n";
                }
            }
        }
        $r .= '</urlset>';

        // Создать файл
        File::put(public_path('sitemap.xml'), $r);

        // Создать архив
        $data = implode('', file(public_path('sitemap.xml')));
        $gzdata = gzencode($data, 9);
        File::put(public_path('sitemap.xml.gz'), $gzdata);
    }


    // Сформировать robots.txt
    public static function robots()
    {
        $index = config('add.not_index_website'); // Если не нужно индексировать сайт, то true, если нужно, то false
        $disallow = config('add.disallow');

        $disallow[] = '*.php$';
        $disallow[] = 'js/*.js$';
        $disallow[] = 'css/*.css$';
        $r = 'User-agent: *' . PHP_EOL;
        $url = config('add.url', '/');

        // Если не индексировать
        if ($index) {
            $r .= 'Disallow: /';

            // Если индексировать
        } else {

            if ($disallow) {
                foreach ($disallow as $v) {
                    $r .= "Disallow: /{$v}" . PHP_EOL;
                }
            }

            $r .= PHP_EOL . "Host: {$url}" . PHP_EOL;
            $r .= "Sitemap: {$url}/sitemap.xml" . PHP_EOL;
            $r .= "Sitemap: {$url}/sitemap.xml.gz";
        }
        File::put(public_path('robots.txt'), $r);
    }


    // Сформировать humans.txt
    public static function human()
    {
        $values = config('add.development');
        if ($values && is_array($values)) {
            $r = '';
            foreach ($values as $k => $v) {
                $r .= "{$k}: {$v}\n";
            }
            $r .= 'Last update: ' . date('Y-m-d') . PHP_EOL;
            File::put(public_path('humans.txt'), $r);
        }
    }


    // Создаётся файл /public/error.php и в нём вид error из /resources/views/errors/error.blade.php
    public static function errorPage()
    {
        $noShowErrorPage = true; // Эту переменную можно использовать, например чтобы на странице ошибки не показывать меню

        if (view()->exists('errors.error')) {
            $view = view('errors.error')
                ->with(compact('noShowErrorPage'))
                ->render();

            File::put(public_path('error.php'), $view);
        }
    }


    // Сформировать .htaccess
    public static function htaccess()
    {
        $r = 'addDefaultCharset utf-8' . PHP_EOL;
        $r .= 'ErrorDocument 500 /error.php' . PHP_EOL . PHP_EOL;
        $r .= 'RewriteEngine On' . PHP_EOL . PHP_EOL;

        // Если протокол https
        if (config('add.protocol') == 'https') {
            $r .= '#no http and www' . PHP_EOL;
            $r .= 'RewriteCond %{HTTPS} off' . PHP_EOL;
            $r .= 'RewriteCond %{HTTP:X-Forwarded-Proto} !https' . PHP_EOL;
            $r .= 'RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]' . PHP_EOL;
            $r .= 'RewriteCond %{HTTP_HOST} ^www\.(.*)$' . PHP_EOL;
            $r .= 'RewriteRule ^(.*)$ https://%1/$1 [R=301,L]' . PHP_EOL;

        } else {
            $r .= '#no www' . PHP_EOL;
            $r .= 'RewriteCond %{HTTP_HOST} ^www\.(.*)$' . PHP_EOL;
            $r .= 'RewriteRule ^(.*)$ http://%1/$1 [R=301,L]' . PHP_EOL . PHP_EOL;
        }

        $r .= PHP_EOL . 'RewriteCond %{REQUEST_URI} !^public' . PHP_EOL;
        $r .= 'RewriteRule ^(.*)$ public/$1 [L]' . PHP_EOL . PHP_EOL;

        // Если индексирование сайта выключено
        if (config('add.not_index_website')) {
            $r .= PHP_EOL . 'SetEnvIfNoCase User-Agent "^Googlebot" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Yandex" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Yahoo" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Aport" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^msnbot" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^spider" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Robot" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^php" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Mail" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^bot" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^igdeSpyder" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Snapbot" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^WordPress" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^BlogPulseLive" search_bot' . PHP_EOL;
            $r .= 'SetEnvIfNoCase User-Agent "^Parser" search_bot' . PHP_EOL;
        }
        File::put(public_path('.htaccess'), $r);
    }
}
