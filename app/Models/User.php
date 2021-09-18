<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Mail\SendServiceMail;

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



    // Связь один ко многим
    /*public function forms()
    {
        return $this->hasMany(Form::class);
    }*/

    // Связь многие ко многим для любых моделей
    /*public function file()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withTimestamps(); // Добавить, чтобы записывать в БД created_at updated_at;
    }*/



    // Меняем шаблон письма при сбросе пароля
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app()->make(SendServiceMail::class, [
            'title' => __('s.link_to_change_password'),
            'body' => html()->a(route('password.reset', $token), __('s.reset_password')),
        ]));
    }



    // Записать IP текущего пользователя.
    public function saveIp()
    {
        $this->ip = request()->ip();
        $this->update();
    }
}
