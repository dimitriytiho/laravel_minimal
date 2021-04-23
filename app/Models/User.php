<?php

namespace App\Models;

use App\Mail\SendServiceMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;


    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    // Связь один ко многим для любых моделей
    public function file()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withTimestamps(); // Добавить, чтобы записывать в БД created_at updated_at;
    }



    // Меняем шаблон письма при сбросе пароля
    public function sendPasswordResetNotification($token)
    {
        $title = __('s.link_to_change_password');
        $values = [
            'title' => __('s.you_forgot_password'),
            'btn' => __('s.reset_password'),
            'link' => route('password.reset', $token),
        ];
        $this->notify(new SendServiceMail($title , null, $values, 'service'));
    }



    // Записать IP текущего пользователя.
    public function saveIp()
    {
        $this->ip = request()->ip();
        $this->update();
    }


    // Возвращает имя роли User
    public static function getRoleUser()
    {
        return 'user';
    }


    // Возвращает имя роли Admin
    public static function getRoleAdmin()
    {
        return 'admin';
    }
}
