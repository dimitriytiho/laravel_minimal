<?php


namespace App\Services\Form;

use Illuminate\Support\Facades\Lang;

trait FormTrait
{
    // В классе наследнике укажите файл с этой переменной
    //protected static $langFile = 's';


    /**
     *
     * Иконка для input
     * @return string
     *
     * @param string $icon - классы иконок Fontawesome и любые другие классы.
     * @param string $classMain - класс для основного блока, необязательный параметр.
     * @param string $classText - класс для вложенного блока, необязательный параметр.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     */
    public static function inputGroupAppend($icon, $classMain = null, $classText = null, $attrs = [])
    {
        if ($icon) {

            //Icon
            $icon = html()->i()->class($icon);

            // Input
            $input = html()->span($icon)->class('input-group-text ' . $classText);

            // Wrap div
            return html()->div($input)->class('input-group-append')->addClass($classMain)->attributes($attrs);
        }
        return null;
    }



    /**
     *
     * @return string
     * Возвращает элемент, в зависимости передан ли он в массиве атрибутов.
     *
     * @param string|int|null $element
     * @param array $attrs
     */
    private static function getElementFromAttrs($element, array $attrs)
    {
        return $attrs[$element] ?? null;
    }


    /**
     *
     * @return array
     * Обновляет массив атрибутов, удаляя из него элементы из массива $unset.
     *
     * @param array $attrs
     * @param array $unset
     */
    private static function updateAttr(array $attrs, array $unset = [])
    {
        if ($unset) {
            foreach ($unset as $item) {
                if (isset($attrs[$item])) {
                    unset($attrs[$item]);
                }
            }
        }
        return $attrs;
    }

    /**
     *
     * @return string
     * Возвращает переводную фразу.
     *
     * @param array $attrs
     * @param array $unset
     */
    private static function getPlaceholder($name, $label)
    {
        $langFile = self::$langFile ?? 's';
        if ($label && Lang::has($langFile . '.' . $label)) {
            return __($langFile . '.' . $label);
        } elseif ($label && is_string($label)) {
            return $label;
        } elseif (Lang::has($langFile . '.' . $name)) {
            return __($langFile . '.' . $name);
        }
        return $name;
    }


    /**
     *
     * @return string
     * Возвращает html label.
     *
     * @param string $id
     * @param string $required
     * @param string $placeholder
     * @param string $class
     */
    private static function getLabel($id, $required, $placeholder, $class)
    {
        if ($id && $placeholder) {
            return html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class('form-label ' . $class);
        }
        return null;
    }


    /**
     *
     * @return object.
     * Возвращает html input c или без атрибута required.
     *
     * @param object $input
     * @param string $required
     */
    private static function getRequired(object $input, $required)
    {
        if ($required) {
            return $input->required();
        }
        return $input;
    }


    /**
     *
     * @return string
     * Возвращает html оборачивающего div.
     *
     * @param string $contents
     * @param string $class
     */
    private static function getWrap($contents, $class)
    {
        return html()->div($contents)->class($class);
    }
}
