<?php


namespace App\Support\Admin;

use App\Models\Menu;
use Illuminate\Support\Str;

class LeftMenu
{
    // Исключения для хлебных крошек, т.е. к ним добавляется ещё один сегмент, добивить в массив
    private static $breadcrumbExceptions = ['edit', 'form/', 'order/'];



    /**
     *
     * @return array
     *
     * Возвращает левое меню для админки.
     * Кэшируем запрос, чтобы увидеть измения сбросьте кэш admin_left_menu.
     */
    public static function getMenu()
    {
        if (cache()->has('admin_left_menu')) {
            $leftMenuArr = cache()->get('admin_left_menu');
        } else {
            $leftMenuArr = Menu::where('belong_id', 2)
                ->active()
                ->orderBy('sort')
                ->get()
                ->keyBy('id')
                ->toArray();
            cache()->put('admin_left_menu', $leftMenuArr);
        }
        return $leftMenuArr;
    }


    /**
     *
     * @return array
     *
     * Возвращает массив в виде дерева.
     *
     * @param array $menu - передайте меню, необязательный параметр.
     */
    public static function getTree(array $menu = [])
    {
        $menu = $menu ?: self::getMenu();
        $arr = [];
        if ($menu) {
            foreach ($menu as $key => $item) {

                // Текущее меню
                if (Str::contains(request()->path(), $item['slug'])) {
                    Main::set('admin_current_menu', $item);
                }
                /*if (request()->path() === config('add.admin') . $item['slug']) {
                    Main::set('admin_current_menu', $item);
                }*/

                // Построем дерево из меню
                if ($item['parent_id']) {
                    $arr[$item['parent_id']]['child'][$item['id']] = $item;
                    $arr[$item['parent_id']]['slugs'][] = $item['slug'];

                } else {
                    $arr[$key] = $item;
                }
            }
        }
        return $arr;
    }


    /**
     *
     * @return array
     *
     * Возвращает хлебные крошки.
     *
     * @param array $menu - передайте меню, необязательный параметр.
     */
    public static function breadcrumbs(array $menu = [])
    {
        $menu = $menu ?: self::getMenu();
        $currentMenu = Main::get('admin_current_menu');
        $breadcrumbs = [];
        if ($menu && $currentMenu) {
            foreach ($menu as $key => $item) {

                if (isset($menu[$currentMenu['id']])) {

                    $breadcrumbs[] = $menu[$currentMenu['id']];

                    if ($currentMenu['id'] !== $menu[$currentMenu['id']]['parent_id'] && 'list' !== $menu[$currentMenu['id']]['title']) { // Названия пропускаем
                        $currentMenu['id'] = $menu[$currentMenu['id']]['parent_id'];

                    } else break;
                } else break;
            }
        }

        if (!empty($breadcrumbs[0])) {

            // Если страница редактирования, то добавим элемент в начало, исключение добивить в массив
            if (Str::contains(request()->path(), self::$breadcrumbExceptions)) {
                array_unshift($breadcrumbs, [
                    'title' => 'edit',
                ]);
            }

            // Для первого элемента запишем end = true
            $breadcrumbs[0]['end'] = true;

            // Перевернём массив
            $breadcrumbs = array_reverse($breadcrumbs);
        }
        return $breadcrumbs;
    }
}
