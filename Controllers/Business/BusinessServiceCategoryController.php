<?php

namespace App\Http\Controllers\Business;

use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BusinessServiceCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        $servicesCategories = $user->business->serviceCategories;
        return view('business.businessServiceCategory.index', compact('servicesCategories'));
    }

    public function create()
    {
        $statusTypes = StatusType::getItems();
        return view('business.businessServiceCategory.create', compact('statusTypes'));
    }

    public function edit(BusinessServiceCategory $businessServiceCategory)
    {
        $statusTypes = StatusType::getItems();
        return view("business.businessServiceCategory.edit", compact('statusTypes', 'businessServiceCategory'));
    }

    public function store(Request $request)
    {
        $business = auth()->guard(GuardType::BUSINESS)->user()->business;

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique("business_service_categories")->where("business_id", $business->id)],
            'status' => ['required', Rule::in(StatusType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['business_id'] = $business->id;

        $businessServiceCategory = BusinessServiceCategory::create($data);

        if (!$businessServiceCategory) return redirect()->route('business.businessServiceCategory.create')->withInput();

        return redirect()->route('business.businessServiceCategory.edit', $businessServiceCategory->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessServiceCategory $businessServiceCategory)
    {
        $business = auth()->guard(GuardType::BUSINESS)->user()->business;

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique("business_service_categories")->where("business_id", $business->id)->ignoreModel($businessServiceCategory)],
            'status' => ['required', Rule::in(StatusType::getItems())]
        ])->validate();

        $businessServiceCategory->update($request->all());

        return redirect()->route('business.businessServiceCategory.edit', $businessServiceCategory->id)->with('success', trans('messages.itemUpdated'));
    }
}
