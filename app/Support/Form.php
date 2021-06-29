<?php


namespace App\Support;

use App\Contracts\Form as FormInterface;
use Illuminate\Support\Facades\Lang;

class Form implements FormInterface
{
    /**
     *
     * Разметка для input
     * @return string
     *
     * Переводы из языкового файла s.php.
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
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null, $append = null)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class($label ? null : 'sr-only');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Main div
            return html()->div($label . $input . $append)->class('form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для textarea
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = null, $class = null)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class($label ? null : 'sr-only');

            // Textarea
            $input = html()->textarea($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Main div
            return html()->div($label . $input)->class('form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для select
     * @return string
     *
     * Переводы из языкового файла s.php.
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
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = null, $class = null, $disabled = null, $optionValueFromId = false)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class($label ? null : 'sr-only');

            // Options
            $html = '';
            if ($options) {
                if (is_object($options) || is_array($options)) {
                    foreach ($options as $key => $option) {
                        $val = $optionValueFromId ? $key : $option;
                        $html .= "<option value='{$val}'";
                        // Selected
                        $html .= $value == $val ? ' selected' : null;
                        // Disabled
                        $html .= $disabled == $val ? ' disabled' : null;
                        $html .= ">{$option}</option>";
                    }
                } elseif (is_string($options)) {
                    $html = $options;
                }
            }

            // Select
            $input = html()->select($name, [], $value)->id($id)->class('form-control')->attributes($attrs)->html($html);

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Main div
            return html()->div($label . $input)->class('form-group ' . $class);
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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param bool $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     */
    public static function checkbox($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null, $checked = false)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class('custom-control-label');

            // Main div
            return html()->div($input . $label)->class('mb-3 custom-control custom-checkbox ' . $class);
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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param bool $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     */
    public static function switch($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null, $checked = false)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class('custom-control-label');

            // Main div
            return html()->div($input . $label)->class('mb-3 custom-control custom-switch' . $class);
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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param bool $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     */
    public static function radio($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null, $checked = false)
    {
        if ($name) {

            // Id
            if (isset($attrs['id'])) {
                $id = $attrs['id'];
                unset($attrs['id']);
            } else {
                $id = $name;
            }

            // Placeholder
            $placeholder = ($label && Lang::has('s.' . $label) ? __('s.' . $label) : __('s.' . $name));

            // input
            $input = html()->radio($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            if ($required) {
                $input = $input->required();
            }

            // Label
            $label = html()->label($placeholder . ($required ? html()->element('sup')->text('*') : null), $id)->class('custom-control-label');

            // Main div
            return html()->div($input . $label)->class('mb-3 custom-control custom-radio' . $class);
        }
        return null;
    }
}
