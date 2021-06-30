<?php


namespace App\Services\Form;

use App\Contracts\Form as FormInterface;
use App\Support\Func;
use Illuminate\Support\Facades\Lang;

class FormAdmin implements FormInterface
{
    // Название файла для переводов из resources/lang/en, без .php
    private static $langFile = 'a';


    /**
     *
     * Разметка для input
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = true, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для textarea
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = true, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // Textarea
            $input = html()->textarea($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для select
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param string|array|object $options - передать options: строкой, массивом или объектом (если $value будет равно одному из значений $options, то этот option будет selected).
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $disabled - передать значения, для которого установить атрибут disabled.
     * @param bool $optionValueFromId - передайте true, если передаёте массив или объект $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     */
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = true, $class = null, $disabled = null, $optionValueFromId = false)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // Options
            $html = '';
            if ($options) {
                if (is_object($options) || is_array($options)) {
                    foreach ($options as $key => $option) {
                        // Value
                        $val = $optionValueFromId ? $key : $option;
                        // Start option
                        $html .= "<option value='{$val}'";
                        // Selected
                        if (is_object($value)) {
                            $html .= isset($value[$val]) || $value->contains($val) || isset($value[$option]) || $value->contains($option) ? ' selected' : null;
                        } elseif (is_array($value)) {
                            $html .= isset($value[$val]) || in_array($val, $value) || isset($value[$option]) || in_array($val, $option) ? ' selected' : null;
                        } else {
                            $html .= $value == $val ? ' selected' : null;
                        }
                        // Disabled
                        $html .= $disabled == $val ? ' disabled' : null;
                        // Translation
                        $option = Func::__($option, 'a');
                        // End option
                        $html .= ">{$option}</option>";
                    }
                } elseif (is_string($options)) {
                    $html = $options;
                }
            }

            // Select
            $input = html()->select($name, [], $value)->id($id)->class('form-control')->attributes($attrs)->html($html);

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для checkbox
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function checkbox($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($label . $input, 'mb-3 custom-control custom-checkbox ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для toggle
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * Дополнительные атрибуты (эти атрибуты по-умолчанию):
     * data-on-color="primary" - цвет, когда включен,
     * data-off-color="default" - цвет, когда выключен,
     * data-on-text="on" - текст, когда выключен,
     * data-off-text="off" - текст, когда выключен,
     *
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function toggle($name, array $attrs = [], $checked = false, $value = null, $required = false, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Дата атрибуты по-умолчанию
            if (!isset($attrs['data-on-color'])) {
                $attrs['data-on-color'] = 'primary';
            }
            if (!isset($attrs['data-off-color'])) {
                $attrs['data-off-color'] = 'default';
            }
            if (isset($attrs['data-on-text'])) {
                $attrs['data-on-text'] = Func::__($attrs['data-on-text'], self::$langFile);
            } else {
                $attrs['data-on-text'] = Func::__('on', self::$langFile);
            }
            if (isset($attrs['data-off-text'])) {
                $attrs['data-off-text'] = Func::__($attrs['data-off-text'], self::$langFile);
            } else {
                $attrs['data-off-text'] = Func::__('off', self::$langFile);
            }

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs)->attribute('data-toggle', 'switch');

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'bootstrap-switch-label mt-2');

            // Wrap div
            return self::getWrap($label . $input, 'mb-3 ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для radio
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function radio($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->radio($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($label . $input, 'mb-3 custom-control custom-radio ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для input
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $append - html код, который нужно вывести после input, по-умолчанию null, необязательный параметр.
     */
    public static function inputGroup($name, array $attrs = [], $value = null, $required = true, $label = true, $class = null, $append = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Input-group
            $div = self::getWrap($input . $append, 'input-group');

            // Wrap div
            return self::getWrap($label . $div, $class);
        }
        return null;
    }



    // СЛУЖЕБНЫЕ МЕТОДЫ

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
     * Возвращает элемент, взависимости педедан ли он в массиве атрибутов.
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
        if ($label && Lang::has(self::$langFile . '.' . $label)) {
            return __(self::$langFile . '.' . $label);
        } elseif ($label && is_string($label)) {
            return $label;
        } elseif (Lang::has(self::$langFile . '.' . $name)) {
            return __(self::$langFile . '.' . $name);
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
     * @param string $label
     * @param string $placeholder
     * @param string $class
     */
    private static function getLabel($id, $required, $label, $placeholder, $class)
    {
        if ($id && $placeholder) {
            return html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class($class);
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
