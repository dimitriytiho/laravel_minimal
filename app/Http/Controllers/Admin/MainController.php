<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\App;
use App\Support\Func;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MainController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->info = $this->info();
    }


    public function index()
    {
        // Название вида
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->info['view']}";

        $title = __('a.dashboard');
        return view($view, compact('title'));
    }


    // Записывает в куку локаль
    public function locale($locale)
    {
        if (in_array($locale, config('admin.locales') ?: [])) {
            return redirect()->back()->withCookie(Str::slug(config('app.name')) . '_loc', $locale);
        }
        Func::getError("Invalid locale {$locale}", __METHOD__);
    }


    // Записывает в куку
    public function getCookie(Request $request)
    {
        if (csrf_token() === $request->token && $request->key && $request->val) {
            return redirect()->back()->withCookie($request->key, $request->val);
        }
        return redirect()->back();
    }


    // Записывает в сессию
    public function getSession(Request $request)
    {
        if (csrf_token() === $request->token && $request->key && $request->val) {
            session()->put($request->key, $request->val);
        }
        return redirect()->back();
    }



    public function getSlug(Request $request)
    {
        if ($request->ajax()) {
            return App::cyrillicToLatin($request->slug);
        }
        Func::getError('Request No Ajax', __METHOD__);
    }


    public function sidebarMini(Request $request)
    {
        $val = $request->input('val');
        $values = ['mini', 'full'];
        if ($val && in_array($val, $values)) {
            return redirect()->back()->withCookie('sidebar_mini', $val);
        }
        Func::getError('Request to', __METHOD__);
    }
}
