<?php


namespace App\Support;


class Tree
{
    /**
     *
     * @return array
     *
     * Возвращает html дерево, формирует дерево из обычного массива, где потомки будут в ключе child.
     *
     * $arr - принимает массив, где id элементов ключи массива.
     * $viewName - название вида из папки resources/views/tree. В вид передаются: объект элемента ($item), элемент вложенности ($tab), id элемента ($id), порядковый номер из цикла ($i).
     * $tab - показывает вложенность, например передать -.
     * $values - передать данные для вида, если необходимо, необязательный параметр.
     * $cacheName - по-умолчанию не кэшируется, если надо кэшировать, то передать название кэша, необязательный параметр.
     * $column - колонка связующая, по-умолчанию parent_id, необязательный параметр.
     */
    public static function get($arr, $viewName, $tab = '', $values = null, $cacheName = '', $column = 'parent_id')
    {
        if ($cacheName && cache()->has($cacheName)) {
            $view = cache()->get($cacheName);

        } else {

            $tree = self::tree($arr, $column);
            $view = self::view($viewName, $tree, $tab, $values);
            if ($cacheName) {
                cache()->put($cacheName, $view);
            }
        }
        return $view;
    }


    /**
     *
     * @return array
     *
     * Возвращает массив дерево, формирует дерево из обычного массива, где потомки будут в ключе child.
     *
     * $arr - принимает массив, где id элементов ключи массива.
     * $column - колонка связующая, по-умолчанию parent_id, необязательный параметр.
     */
    public static function tree($arr, $column = 'parent_id')
    {
        $tree = [];
        if ($arr && is_array($arr) && $column) {
            foreach ($arr as $id => &$node) {

                if (empty($node[$column])) {
                    $tree[$id] = &$node;
                } else {
                    $arr[$node[$column]]['child'][$id] = &$node;
                }
            }
        }
        return $tree;
    }


    /**
     * @return string
     *
     * Возвращает общий вид из переданного дерева, т.е. вид для одного элемента, он цикле складывается для всех элементов.
     *
     * $viewName - название вида из папки resources/views/tree. В вид передаются: объект элемента ($item), элемент вложенности ($tab), id элемента ($id), порядковый номер из цикла ($i).
     * $tree - массив в виде дерева, его строит метод выше get().
     * $tab - показывает вложенность, например передать -.
     * $values - передать данные для вида, если необходимо, необязательный параметр.
     * $cacheName - по-умолчанию не кэшируется, если надо кэшировать, то передать название кэша, необязательный параметр.
     */
    public static function view($viewName, array $tree, $tab = '', $values = null, $cacheName = '')
    {
        $view = '';
        if ($cacheName && cache()->has($cacheName)) {
            $view = cache()->get($cacheName);

        } else {

            $i = 0;
            if ($tree && view()->exists("tree.{$viewName}")) {
                foreach ($tree as $id => $item) {
                    $i++;
                    $view .= view("tree.{$viewName}", compact('viewName', 'values', 'item', 'tab', 'id', 'i'))->render();
                }
            }

            if ($cacheName) {
                cache()->put($cacheName, $view);
            }
        }
        return $view;
    }
}
