<?php


namespace App\Support\Admin;

use Illuminate\Support\Str;

class Locale
{
    private $currentLocale;
    private $locales;

    public function __construct()
    {
        $this->currentLocale = app()->getLocale();
        $this->locales = config('admin.locales') ?: [];
    }


    /**
     *
     * @return array
     *
     * Возвращает массив все локалей, кроме выбранной.
     */
    public static function excludeCurrentLocale()
    {
        $self = new self();
        $locales = $self->locales;
        if (in_array($self->currentLocale, $locales)) {
            unset($locales[array_search($self->currentLocale, $locales)]);
        }
        return array_values($locales);
    }


    /**
     *
     * @return void
     *
     * Получить из куки название локали и установить её.
     *
     * @param object $request
     */
    public static function setLocaleFromCookie($request)
    {
        $self = new self();
        $locale = $request->cookie(Str::slug(config('app.name')) . '_loc');
        if ($locale && $locale !== $self->currentLocale && in_array($locale, $self->locales)) {
            app()->setLocale($locale);
        }
    }
}
