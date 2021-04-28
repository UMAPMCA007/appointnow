<?php

namespace App\Http\Controllers;

use App\Enums\GuardType;
use App\Enums\LanguageType;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request, $language)
    {
        if (!in_array($language, LanguageType::getItems())) {
            if ($request->fullUrl() === redirect()->back()->getTargetUrl()) {
                if (auth()->guard(GuardType::BUSINESS)->check()) return redirect()->route('business.home');
                if (auth()->guard(GuardType::STAFF)->check()) return redirect()->back('staff.home');
                return redirect()->route('admin.home');
            }
        }
        app()->setLocale($language);
        session()->put('language', $language);
        if ($request->fullUrl() === redirect()->back()->getTargetUrl()) {
            if (auth()->guard(GuardType::BUSINESS)->check()) return redirect()->route('business.home');
            if (auth()->guard(GuardType::STAFF)->check()) return redirect()->back('staff.home');
            return redirect()->route('admin.home');
        }
        return redirect()->back();
    }
}
