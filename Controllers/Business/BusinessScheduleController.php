<?php

namespace App\Http\Controllers\Business;

use App\Enums\BooleanType;
use App\Enums\DayType;
use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\BusinessSchedule;
use App\Models\BusinessScheduleDay;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BusinessScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        $schedules = $user->business->schedules;
        return view('business.businessSchedule.index', compact('schedules'));
    }

    public function create()
    {
        $booleanTypes = BooleanType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        return view('business.businessSchedule.create', compact('booleanTypes', 'timezones'));
    }

    public function edit(BusinessSchedule $businessSchedule)
    {
        $booleanTypes = BooleanType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $data = [
            'booleanTypes' => $booleanTypes,
            'timezones' => $timezones,
            'businessSchedule' => $businessSchedule
        ];
        return view('business.businessSchedule.edit', $data);
    }

    public function store(Request $request)
    {
        $business = auth()->guard(GuardType::BUSINESS)->user()->business;

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('business_schedules')->where('business_id', $business->id)],
            'timezone' => ['required', 'string']
        ])->validate();

        $data = $request->all();
        $data['business_id'] = $business->id;

        $businessSchedule = BusinessSchedule::create($data);

        if (!$businessSchedule) return redirect()->route('business.businessSchedule.create')->withInput();

        foreach (DayType::getItems() as $day)
        {
            BusinessScheduleDay::create([
                'business_schedule_id' => $businessSchedule->id,
                'day' => $day
            ]);
        }

        return redirect()->route('business.businessSchedule.edit', $businessSchedule->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessSchedule $businessSchedule)
    {
        $business = auth()->guard(GuardType::BUSINESS)->user()->business;

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('business_schedules')->where('business_id', $business->id)->ignoreModel($businessSchedule)],
            'timezone' => ['required', 'string']
        ])->validate();

        $businessSchedule->update($request->all());

        return redirect()->route('business.businessSchedule.edit', $businessSchedule->id)->with('success', trans('messages.itemUpdated'));
    }

    public function makeDefault(BusinessSchedule $businessSchedule)
    {
        $business = auth()->guard(GuardType::BUSINESS)->user()->business;
        BusinessSchedule::whereIn('id', $business->schedules->pluck("id"))->update(["is_default" => "no"]);
        $businessSchedule->update(["is_default" => "yes"]);

        return redirect()->route('business.businessSchedule.edit', $businessSchedule->id)->with('success', trans('messages.itemUpdated'));
    }

    public function changeDayStatus(BusinessScheduleDay $businessScheduleDay)
    {
        $status = ($businessScheduleDay->status == StatusType::ACTIVE) ? StatusType::DISABLED : StatusType::ACTIVE;
        $businessScheduleDay->update(['status' => $status]);
        return redirect()->route('business.businessSchedule.edit', $businessScheduleDay->business_schedule_id)->with('success', trans('messages.itemUpdated'));
    }
}
