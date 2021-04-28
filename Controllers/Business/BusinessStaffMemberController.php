<?php

namespace App\Http\Controllers\Business;

use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessStaffMember;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessStaffMemberController extends Controller
{
    public function index()
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        $staffMembers = $user->business->staffMembers;
        return view('business.staffMember.index', compact('staffMembers'));
    }

    public function create()
    {
        $statusTypes = StatusType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        return view('business.staffMember.create', compact('statusTypes', 'timezones'));
    }

    public function edit(BusinessStaffMember $businessStaffMember)
    {
        $statusTypes = StatusType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        return view('business.staffMember.edit', compact('statusTypes', 'timezones', 'businessStaffMember'));
    }

    public function store(Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email:rfc', 'max:255', Rule::unique('business_staff_members')->where('business_id', $user->business->id)],
            'code'     => ['nullable', 'string', 'max:10', 'unique:business_staff_members,code'],
            'timezone' => ['required', 'string'],
            'status'   => ['required', 'string', Rule::in(StatusType::getItems())],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed']
        ])->validate();

        $data = $request->all();
        $data['email']    = strtolower($data['email']);
        $data['password'] = bcrypt($data['password']);
        $data['code'] = (empty($data['code'])) ? Str::random(10) : $data['code'];
        $data['business_id'] = $user->business->id;
        unset($data['password_confirmation']);

        $businessStaffMember = BusinessStaffMember::create($data);

        if (!$businessStaffMember) return redirect()->route('business.businessStaffMember.create')->withInput();

        return redirect()->route('business.businessStaffMember.edit', $businessStaffMember->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessStaffMember $businessStaffMember)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email:rfc', 'max:255', Rule::unique('business_staff_members')->where('business_id', $user->business->id)->ignoreModel($businessStaffMember)],
            'code'     => ['required', 'string', 'max:10', Rule::unique("business_staff_members")->ignoreModel($businessStaffMember)],
            'timezone' => ['required', 'string'],
            'status'   => ['required', 'string', Rule::in(StatusType::getItems())],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed']
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

        $businessStaffMember->update($data);

        return redirect()->route('business.businessStaffMember.edit', $businessStaffMember->id)->with('success', trans('messages.itemUpdated'));
    }
}
