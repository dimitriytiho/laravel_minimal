<?php


namespace App\Services\Form;

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
     *
     * Создадим или сохраним данные пользователя, например при заполнении формы.
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

            // Для Admin не обновляем пароль
            if (request()->password && !$issetUser->hasRole(UserModel::getRolesAdminPanel())) {
                $data['password'] = Hash::make(request()->password);
            }

            // Сохраним прошлые данные
            LastData::saveData($issetUser->id, 'users');

            // Если пользователь был удалён, то вернём его
            if ($issetUser->deleted_at) {
                $issetUser->restore();
            }

            $issetUser->fill($data);

            // Статус повторно
            $issetUser->status = config('add.user_statuses')['1'] ?? 'info';

            $issetUser->update();
            return $issetUser;

        } else {

            $password = Str::random(8);
            $data['password'] = Hash::make($password);

            // Создадим нового пользователя
            $user = app()->make(UserModel::class);
            $user->fill($data);
            $user->save();

            // По умолчанию добавим роль user
            $user->assignRole(UserModel::getRoleUser());

            // Отправить письмо о регистрации пользователя
            $body = html()->element('p')->text('Login: ' . $user->email);
            $body .= html()->element('p')->text('Password: ' . $password);
            $body .= html()->a(route('login', __('s.login')));
            SendMail::get($user->email, __('s.you_success_registered'), $body);

            return $user;
        }
    }
}
