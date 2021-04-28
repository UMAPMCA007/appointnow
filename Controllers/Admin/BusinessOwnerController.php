<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LanguageType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessOwner;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessOwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:business-owner-list|business-owner-create|business-owner-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:business-owner-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:business-owner-edit', ['only' => ['edit', 'update']]);
    }

    public function index()
    {
        $businessOwners = BusinessOwner::with('business')->get();
        return view('admin.businessOwner.index', compact('businessOwners'));
    }

    public function show(BusinessOwner $businessOwner)
    {
        return view('admin.businessOwner.show', compact('businessOwner'));
    }

    public function create()
    {
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $languages = LanguageType::getItems();
        $statusTypes = StatusType::getItems();
        return view('admin.businessOwner.create', compact('timezones', 'languages', 'statusTypes'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email:rfc', 'max:255', Rule::unique('business_owners')->where("deleted_at", null)],
            'code'      => ['nullable', 'string', 'max:10', 'unique:business_owners,code'],
            'status'    => ['required', 'string', Rule::in(StatusType::getItems())],
            'password'  => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'language'  => ['required', 'string', Rule::in(LanguageType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['email']    = strtolower($data['email']);
        $data['password'] = bcrypt($data['password']);
        $data['code']     = (empty($data['code'])) ? Str::random(10) : $data['code'];
        unset($data['password_confirmation']);

        $businessOwner = BusinessOwner::create($data);

        if (!$businessOwner) return redirect()->route('admin.business.create')->withInput();

        return redirect()->route('admin.businessOwner.edit', $businessOwner)->with('success', trans('messages.itemCreated'));
    }

    public function edit(BusinessOwner $businessOwner)
    {
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $languages = LanguageType::getItems();
        $statusTypes = StatusType::getItems();
        return view('admin.businessOwner.edit', compact('businessOwner', 'timezones', 'languages', 'statusTypes'));
    }

    public function update(BusinessOwner $businessOwner, Request $request)
    {
        Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email:rfc', 'max:255', Rule::unique('business_owners')->where("deleted_at", null)->ignoreModel($businessOwner)],
            'code'      => ['nullable', 'string', 'max:10', Rule::unique('business_owners')->ignoreModel($businessOwner)],
            'status'    => ['required', 'string', Rule::in(StatusType::getItems())],
            'password'  => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'language'  => ['required', 'string', Rule::in(LanguageType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['email'] = strtolower($data['email']);
        if (empty($data['password'])) {
            unset($data['password']);
            unset($data['password_confirmation']);
        }
        else {
            $data['password'] = bcrypt($data['password']);
            unset($data['password_confirmation']);
        }
        $data['code'] = (empty($data['code'])) ? Str::random(10) : $data['code'];

        $businessOwner->update($data);

        return redirect()->route('admin.businessOwner.edit', $businessOwner->id)->with('success', trans('messages.itemUpdated'));
    }
}
