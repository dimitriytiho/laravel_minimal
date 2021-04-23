<?php


namespace App\Helpers\Admin;

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


    public static function excludeCurrentLocale()
    {
        $self = new self();
        $currentLocale = $self->currentLocale;
        $locales = $self->locales;
        if (in_array($currentLocale, $locales)) {
            unset($locales[array_search($currentLocale, $locales)]);
        }
        return array_values($locales);
    }


    public static function setLocaleFromCookie($request)
    {
        $self = new self();
        $currentLocale = $self->currentLocale;
        $locales = $self->locales;
        $locale = $request->cookie(Str::slug(config('add.name')) . '_loc');
        if ($locale && $locale !== $currentLocale && in_array($locale, $locales)) {
            app()->setLocale($locale);
        }
    }
}
