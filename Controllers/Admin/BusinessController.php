<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LanguageType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessOwner;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:business-list|business-create|business-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:business-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:business-edit', ['only' => ['edit', 'update']]);
    }

    public function create(BusinessOwner $businessOwner)
    {
        if ($businessOwner->business()->exists()) return redirect()->route('admin.businessOwner.index');
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $languages = LanguageType::getItems();
        $statusTypes = StatusType::getItems();
        return view('admin.business.create', compact('timezones', 'languages', 'statusTypes', 'businessOwner'));
    }

    public function store(BusinessOwner $businessOwner, Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10', 'unique:businesses,code'],
            'language' => ['required', 'string', Rule::in(LanguageType::getItems())],
            'logo' => ['nullable', 'image']
        ])->validate();

        $data = $request->all();
        $data['code'] = (empty($data['code'])) ? Str::random(10) : $data['code'];
        $data['business_owner_id'] = $businessOwner->id;

        if ($request->hasFile('logo')) $data['logo'] = Storage::putFile('/', $request->file('logo'), 'public');

        $business = Business::create($data);

        if (!$business) return redirect()->route('admin.business.create', $businessOwner->id)->withInput();

        return redirect()->route('admin.business.edit', $business->id)->with('success', trans('messages.itemCreated'));
    }

    public function edit(Business $business)
    {
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $languages = LanguageType::getItems();
        $statusTypes = StatusType::getItems();
        return view('admin.business.edit', compact('business', 'timezones', 'languages', 'statusTypes'));
    }

    public function update(Business $business, Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10', Rule::unique('businessess')->ignoreModel($business)],
            'language' => ['required', 'string', Rule::in(LanguageType::getItems())]
        ])->validate();

        $data = $request->all();

        if ($request->hasFile('logo')) {
            Storage::delete($business->logo);
            $data['logo'] = Storage::putFile('/', $request->file('logo'), 'public');
        }

        $business->update($data);

        return redirect()->route('admin.business.edit', $business->id)->with('success', trans('messages.itemUpdated'));
    }
}
