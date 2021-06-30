<?php


namespace App\Support\Admin;


use App\Support\Func;

class Html
{
    /**
     *
     * @return string
     *
     * Возвращает начало модального окна.
     * @param string $id - передать связующее id.
     * @param string $title - Передать название, перевод будет взять из /resources/lang/en/e.php, необязательный параметр.
     * @param string $class - к примеру modal-lg, будет большое окно, необязательный параметр.
     * @param string $attrs - если нужны дополнительные атрибуты, необязательный параметр.
     */
    public static function modal($id, $title = null, $class = null, $attrs = null)
    {
        $titleLang = Func::__($title);
        $title = $titleLang ? "<h4 class=\"modal-title mb-2\">{$titleLang}</h4>" : null;

        return <<<S
<div id="{$id}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{$id}" aria-hidden="true" {$attrs}>
    <div class="modal-dialog {$class}" role="document">
        <div class="modal-content px-0 px-lg-3">
            <div class="modal-header border-0 mt-2 pb-0 position-relative">
                $title
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mb-4">
S;
    }


    /**
     *
     * @return string
     *
     * Возвращает конец модального окна.
     *
     * @param bool $footer - если нужно разместить футер модального окна, передайте true. И в коде перед футером закройте </div>.
     */
    public static function modalEnd($footer = null)
    {
        $footer = $footer ? null : '</div>';
        return <<<S
            $footer
        </div>
    </div>
</div>
S;
    }


    /**
     *
     * @return string
     *
     * Возвращает html блок smallBox.
     *
     * @param string $color - класс цвета от Bootstrap.
     * @param string $icon - классы иконки от Fontawesome.
     * @param string|int $count - кол-во.
     * @param string $title - название.
     * @param string $route - ссылка (маршрут).
     */
    public static function smallBox($color, $icon, $count, $title, $route, $routeParams = null)
    {
        $more_info = __('a.more_info');
        $title = Func::__($title, 'a');
        $route = Func::route($route, $routeParams);
        return <<<S
<div class="col-lg-3 col-6">
    <div class="small-box bg-{$color}">
        <div class="inner">
            <h3>{$count}</h3>
            <p>{$title}</p>
        </div>
        <div class="icon">
            <i class="{$icon}"></i>
        </div>
        <a href="{$route}" class="small-box-footer">{$more_info} <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
S;
    }


    /**
     *
     * @return string
     *
     * Возвращает html блок infoBox.
     *
     * @param string $color - класс цвета от Bootstrap.
     * @param string $icon - классы иконки от Fontawesome.
     * @param string $title - название.
     * @param string $titleLink - название для ссылки, например подробнее.
     * @param string $route - название маршрута для метода route().
     * @param string $routeParams - второй параметр для метода route(), необязательный параметр.
     * @param string $classLink - класс для ссылки, необязательный параметр.
     * @param string $colClass - классы колонки от Bootstrap, по-умолчанию col-lg-3 col-md-6, необязательный параметр.
     */
    public static function infoBox($color, $icon, $title, $titleLink, $route, $routeParams = null, $classLink = null, $colClass = 'col-lg-3 col-md-6')
    {
        $title = Func::__($title, 'a');
        $titleLink = Func::__($titleLink, 'a');
        $route = Func::route($route, $routeParams);
        return <<<S
<div class="{$colClass} mt-2 mb-3">
    <div class="info-box h-100">
        <span class="info-box-icon bg-{$color}">
            <i class="{$icon}"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text text-wrap">{$title}</span>
            <a href="{$route}" class="info-box-number {$classLink}">{$titleLink}</a>
        </div>
    </div>
</div>
S;

    }
}
