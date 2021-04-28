<?php

namespace App\Http\Controllers\Business;

use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessLocationController extends Controller
{
    public function create()
    {
        $statusTypes = StatusType::getItems();
        return view('business.businessLocation.create', compact('statusTypes'));
    }

    public function edit(BusinessLocation $businessLocation)
    {
        $statusTypes = StatusType::getItems();
        return view('business.businessLocation.edit', compact('businessLocation', 'statusTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'   => ['required', 'string', 'max:255', Rule::unique('business_locations')->where("business_id", $user->business->id)],
            'code'   => ['nullable', 'string', 'max:10', 'unique:business_locations,code'],
            'status' => ['required', 'string', Rule::in(StatusType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['business_id'] = $user->business->id;
        $data['code'] = (empty($data['code'])) ? Str::random(10) : $data['code'];

        $businessLocation = BusinessLocation::create($data);

        if (!$businessLocation) return redirect()->route('business.businessLocation.create')->withInput();

        return redirect()->route('business.businessLocation.edit', $businessLocation->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessLocation $businessLocation)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'   => ['required', 'string', 'max:255', Rule::unique('business_locations')->where("business_id", $user->business->id)->ignoreModel($businessLocation)],
            'code'   => ['required', 'string', 'max:10', Rule::unique('business_locations')->ignoreModel($businessLocation)],
            'status' => ['required', 'string', Rule::in(StatusType::getItems())]
        ])->validate();

        $businessLocation->update($request->all());

        return redirect()->route('business.businessLocation.edit', $businessLocation->id)->with('success', trans('messages.itemUpdated'));
    }
}
