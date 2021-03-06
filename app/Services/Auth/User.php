<?php


namespace App\Services\Auth;

use App\Mail\SendMail;
use App\Models\LastData;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;
use Illuminate\Support\Str;

class User
{
    /**
     *
     * @return \App\Models\User object
     * Возвращает объект пользователя.
     *
     * Создадим или сохраним данные пользователя (если пользователь существует), например при заполнении формы.
     * Используем объект Request: name, email, tel, address, accept, password.
     */
    public static function saveUser()
    {
        // Данные
        $data['name'] = strip_tags(request()->name);
        $data['email'] = strip_tags(request()->email);
        $data['tel'] = strip_tags(request()->tel);
        $data['address'] = strip_tags(request()->address);

        // В чекбокс запишем 1
        $data['accept'] = request()->accept ? '1' : '0';
        $data['ip'] = request()->ip();

        // Проверяем существует ли такой пользователь
        $issetUser = UserModel::withTrashed()->where('email', $data['email'])->first();

        // Если пользователь существует
        if ($issetUser) {

            // Для пользователей админ панели не обновляем пароль
            if (request()->password && !$issetUser->hasRole(Role::ADMIN_PANEL_NAMES)) {
                $data['password'] = Hash::make(request()->password);
            }

            // Сохраним прошлые данные
            LastData::saveData($issetUser->id, 'users');

            // Если пользователь был удалён, то вернём его
            if ($issetUser->deleted_at) {
                $issetUser->restore();
            }

            // Заполняем модель новыми данными
            $issetUser->fill($data);

            // Статус повторно
            $issetUser->status = config('add.user_statuses')['1'] ?? 'info';

            // Обновим пользователя
            $issetUser->update();
            return $issetUser;

        } else {

            $password = Str::random(8);
            $data['password'] = Hash::make($password);

            // Создадим нового пользователя
            $user = app()->make(UserModel::class);

            // Заполняем модель новыми данными
            $user->fill($data);

            // Сохраним пользователя
            $user->save();

            // По умолчанию добавим роль User
            $user->assignRole(Role::USER_NAME);

            // Отправить письмо о регистрации пользователя
            $body = html()->element('p')->text('Login: ' . $user->email);
            $body .= html()->element('p')->text('Password: ' . $password);
            $body .= html()->a(route('login', __('s.login')));
            SendMail::get($user->email, __('s.you_success_registered'), $body);

            return $user;
        }
    }
}
