<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Schema, DB};

class LastData extends Model
{
    use HasFactory;

    // Массив c исключениями колонок
    public static $exception = [
        'id',
        'img',
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'password_confirmation',
    ];

    // Массив c исключениями, которые не сравниваем и не сохраняем
    /*public static $exception = [
        'password',
        'password_confirmation',
    ];*/

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'last_data';



    // Связь имеет один
    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }



    /**
     *
     * Сохраняет данные элемента в отдельную таблицу, данные берутся из объекта Request.
     * Если передаём элемент через объект, то переданые данные сравниваются с данными в БД и если есть различия, то данные сохраняются.
     *
     * @return object
     * Возвращает объект созданного элемента или null.
     *
     * @param int $elementId - id элемента, который нужно сохранить.
     * @param string $table - название таблицы элемнта.
     * @param object|string $dataOrTable - объект с данными элемента, который нужно сохранить или название таблицы.
     */
    public static function saveData(int $elementId, string $table)
    {
        if ($elementId && Schema::hasTable($table)) {

            // Данные из БД
            $last = DB::table($table)->find($elementId);

            // Новые данные из запроса
            $data = request()->all();

            // Удалим исключения
            $data = array_diff_key($data, array_flip(self::$exception));

            // Проверяем были ли данные изменены
            if (self::compare($last, $data)) {

                // Массив для сохранения
                $save = [
                    'user_id' => auth()->check() ? auth()->user()->id : 0,
                    'element_id' => $elementId,
                    'table' => $table,
                    'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                ];

                // Создаём объект этой модели
                $self = new self();

                // Сохраняем прошлые данные в JSON
                return $self::create($save);
            }
        }
        return null;
    }


    /**
     *
     * @return bool
     * Проверяет данные из объекта $last с данными массива $data. Если данные $last отличаются, то возвращает true.
     *
     * @param object $last - данные из БД.
     * @param array $data - новые данные из запроса.
     */
    private static function compare(object $last,array $data)
    {
        if ($last && $data) {
            foreach ($data as $k => $v) {
                if (
                    !in_array($k, self::$exception) // Ключ не должен быть в массиве $exception
                    && property_exists($last, $k) // Ключ существует в массиве $last
                    && $last->$k != $v // Значения массивов $data и $last не равны
                ) {
                    return true;
                }
            }
        }
        return false;
    }
}
