<?php

namespace App\Http\Controllers;

use App\Support\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, File, Schema};
use Illuminate\Support\Str;

class ExController extends Controller
{
    private $code = 'edwg5waiggixioft'; // Написать код в обих сайтах, который будет передаваться в параметр URL


    /**
     *
     * @return string JSON
     * Возвращает данные из переданной таблицы в JSON.
     * https://site.ru/ex/json/edwg5waiggixioft/pages
     *
     * @param string $code_enter - передать определённый код в $code.
     * @param string $table - передать название таблицы, из которой передать данные.
     */
    public function json(Request $request, $code_enter, $table)
    {
        if ($code_enter === $this->code && Schema::hasTable($table)) {
            return DB::table($request->table)->get();
        }
        return null;
    }


    /**
     *
     * @return string JSON
     * Получает данные.
     * Если нужно изменить название моделе, то передать название в гет параметре model.
     * http://127.0.0.1:8000/ex/get/edwg5waiggixioft/site.ru/pages
     *
     * @param string $code_enter - передать определённый код в $code.
     * @param string $table - передать название таблицы, из которой получить данные.
     */
    public function get(Request $request, $code_enter, $domain, $table)
    {
        $modelsPath = config('add.models_path');

        // Формируем нужный URL
        $url = "http://{$domain}/ex/json/{$code_enter}/{$table}";

        // Название модели
        $model = $request->model ?: Str::ucfirst(Str::singular($table));

        if ($code_enter === $this->code && Schema::hasTable($table)) {

            // Получаем данные
            $content = Api::getQuery($url);
            //$content = file_get_contents($url);
            if ($content) {

                // Декодируем JSON
                $content = json_decode($content);
                if ($content) {
                    foreach ($content as $item) {

                        // Приводим данные к массиву
                        $item = is_array($item) ? $item : (array) $item;

                        // Если есть модель
                        if (File::exists($modelsPath . "/{$model}.php")) {
                            // Создаём экземкляр модели
                            $values = app()->make(config('add.models') . '\\' . $model);

                            // Заполняем модель новыми данными
                            $values->fill($item);

                            // Сохраняем элемент
                            $values->save();

                        } else {
                            $items[] = $item;
                        }
                    }

                    // Если нет модели, то сохраним таким способом
                    if (!empty($items)) {
                        DB::table($table)->insert($items);
                    }
                }
                return 'Success fill';
            }
        }
        return null;
    }
}
