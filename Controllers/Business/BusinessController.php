<?php

namespace App\Http\Controllers\Business;

use App\Enums\DayType;
use App\Enums\GuardType;
use App\Enums\LanguageType;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function edit(Business $business)
    {
        $languages = LanguageType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $locations = auth()->guard(GuardType::BUSINESS)->user()->business->locations;
        return view('business.edit', compact('business', 'languages', 'timezones', 'locations'));
    }

    public function create()
    {
        $languages = LanguageType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        return view('business.create', compact('languages', 'timezones'));
    }

    public function store(Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        if ($user->business()->exists()) return redirect()->route('business.business.home');

        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'logo'     => ['nullable', 'image'],
            'language' => ['required', 'string', Rule::in(LanguageType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['code'] = Str::random(10);
        $data['business_owner_id'] = $user->id;

        if ($request->hasFile('logo')) $data['logo'] = Storage::putFile('/', $request->file('logo'), 'public');

        $business = Business::create($data);

        if (!$business) return redirect()->route('business.business.create')->withInput();

        return redirect()->route('business.business.edit', $business->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Business $business, Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        if (!$user->business()->exists()) return redirect()->route('business.business.create');

        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'logo'     => ['nullable', 'image'],
            'language' => ['required', 'string', Rule::in(LanguageType::getItems())]
        ])->validate();

        $data = $request->all();

        if ($request->hasFile('logo')) {
            Storage::delete($business->logo);
            $data['logo'] = Storage::putFile('/', $request->file('logo'), 'public');
        }

        $business->update($data);

        return redirect()->route('business.business.edit', $business->id)->with('success', trans('messages.itemUpdated'));
    }
}
