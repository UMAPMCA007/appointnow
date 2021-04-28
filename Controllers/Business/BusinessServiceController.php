<?php

namespace App\Http\Controllers\Business;

use App\Enums\CurrencyType;
use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessService;
use App\Models\BusinessStaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessServiceController extends Controller
{
    public function index()
    {
        $businessServices = auth()->guard(GuardType::BUSINESS)->user()->business->services;
        return view('business.businessService.index', compact('businessServices'));
    }

    public function create()
    {
        $data = [
            'statusTypes' => StatusType::getItems(),
            'currencies' => CurrencyType::getItems(),
            'signupForms' => auth()->guard(GuardType::BUSINESS)->user()->business->serviceSignupForms
        ];
        return view('business.businessService.create', $data);
    }

    public function edit(BusinessService $businessService)
    {
        $data = [
            'businessService' => $businessService,
            'statusTypes' => StatusType::getItems(),
            'currencies' => CurrencyType::getItems(),
            'staffMembers' => BusinessStaffMember::orderBy("name", "desc")->get(),
            'selectedStaffMembers' => $businessService->staffMembers()->pluck('business_staff_member_id')->toArray(),
            'signupForms' => auth()->guard(GuardType::BUSINESS)->user()->business->serviceSignupForms,
            'serviceCategories' => auth()->guard(GuardType::BUSINESS)->user()->business->serviceCategories
        ];
        return view('business.businessService.edit', $data);
    }

    public function store(Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255', Rule::unique('business_services')->where("business_id", $user->business->id)],
            'code'     => ['nullable', 'string', 'max:10', 'unique:business_services,code'],
            'status'   => ['required', 'string', Rule::in(StatusType::getItems())],
            'price'    => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', Rule::in(CurrencyType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['business_id'] = $user->business->id;
        $data['code'] = (empty($data['code'])) ? Str::random(10) : $data['code'];

        if ($request->hasFile('image')) $data['image'] = Storage::putFile('/', $request->file('image'), 'public');

        $businessService = BusinessService::create($data);

        if (!$businessService) return redirect()->route('business.businessService.create')->withInput();

        return redirect()->route('business.businessService.edit', $businessService->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessService $businessService)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'   => ['required', 'string', 'max:255', Rule::unique('business_services')->where("business_id", $user->business->id)->ignoreModel($businessService)],
            'code'   => ['required', 'string', 'max:10', Rule::unique("business_services")->ignoreModel($businessService)],
            'status' => ['required', 'string', Rule::in(StatusType::getItems())],
            'price'    => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', Rule::in(CurrencyType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['code'] = (!empty($data['code'])) ? $data['code'] : Str::random(10);

        if ($request->hasFile('image')) {
            Storage::delete($businessService->image);
            $data['image'] = Storage::putFile('/', $request->file('image'), 'public');
        }

        $businessService->update($data);
        $businessService->staffMembers()->sync($request->staff_members);

        return redirect()->route('business.businessService.edit', $businessService->id)->with('success', trans('messages.itemUpdated'));
    }
}
