<?php


namespace App\Services\Auth;


class Role
{
    /**
     *
     * @var string
     * Имя роли Admin.
     */
    public const ADMIN_NAME = 'admin';

    /**
     *
     * @var string
     * Имя роли User.
     */
    public const USER_NAME = 'user';
    
    /**
     *
     * @var array
     * Имена ролей с доступом в admin панель, перечислить роли в массиве.
     */
    public const ADMIN_PANEL_NAMES = [
        'admin',
        'editor',
    ];
}
