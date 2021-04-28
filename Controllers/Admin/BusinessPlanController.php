<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BooleanType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessPlanController extends Controller
{
    public function __construct()
    {
        if (auth()->check()) {
            $this->middleware('permission:business-plan-list|business-plan-create|business-plan-edit', ['only' => ['index','store']]);
            $this->middleware('permission:business-plan-create', ['only' => ['create','store']]);
            $this->middleware('permission:business-plan-edit', ['only' => ['edit','update']]);
        }
    }

    public function index()
    {
        $businessPlans = BusinessPlan::all();
        return view('admin.businessPlan.index', compact('businessPlans'));
    }

    public function create()
    {
        $statusTypes = StatusType::getItems();
        $booleanTypes = BooleanType::getItems();
        return view('admin.businessPlan.create', compact('statusTypes', 'booleanTypes'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:business_plans,name'],
            'code' => ['nullable', 'string', 'max:10', 'unique:business_plans,code'],
            'status' => ['required', 'string', Rule::in(StatusType::getItems())],
            'can_add_staff_members' => ['required', 'string', Rule::in(BooleanType::getItems())],
            'staff_member_limit' => ['required', 'numeric', 'min:1'],
            'location_limit' => ['required', 'numeric', 'min:1'],
            'categories_limit' => ['required', 'numeric', 'min:1'],
            'services_limit' => ['required', 'numeric', 'min:1'],
            'signup_form_limit' => ['required', 'numeric', 'min:1']
        ])->validate();

        $data = $request->all();
        $data['code'] = (!empty($data['code'])) ? $data['code'] : Str::random(10);

        $businessPlan = BusinessPlan::create($data);

        if (!$businessPlan) return redirect()->route('businessPlan.create')->withInput();

        return redirect()->route('admin.businessPlan.edit', $businessPlan->id)->with('success', trans('messages.itemCreated'));
    }

    public function edit(BusinessPlan $businessPlan)
    {
        $statusTypes = StatusType::getItems();
        $booleanTypes = BooleanType::getItems();
        return view('admin.businessPlan.edit', compact('businessPlan', 'statusTypes', 'booleanTypes'));
    }

    public function update(Request $request, BusinessPlan $businessPlan)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('business_plans')->ignoreModel($businessPlan)],
            'code' => ['required', 'string', 'max:10', Rule::unique('business_plans')->ignoreModel($businessPlan)],
            'status' => ['required', 'string', Rule::in(StatusType::getItems())],
            'can_add_staff_members' => ['required', 'string', Rule::in(BooleanType::getItems())],
            'staff_member_limit' => ['required', 'numeric'],
            'location_limit' => ['required', 'numeric', 'min:1'],
            'categories_limit' => ['required', 'numeric', 'min:1'],
            'services_limit' => ['required', 'numeric', 'min:1'],
            'signup_form_limit' => ['required', 'numeric', 'min:1']
        ])->validate();

        $businessPlan->update($request->all());

        return redirect()->route('admin.businessPlan.edit', $businessPlan->id)->with('success', trans('messages.itemUpdated'));
    }
}
