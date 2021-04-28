<?php

namespace App\Http\Controllers\staff;

use App\Enums\BooleanType;
use App\Enums\DayType;
use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Timezone;
use Illuminate\Http\Request;
use App\Models\StaffSchedule;
use App\Models\StaffScheduleDay;
use App\Models\StaffScheduleDayHour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StaffScheduleController extends Controller
{

    public function index()
    {
        $user = auth()->guard(GuardType::STAFF)->user();
        $schedules = $user->schedules;
        return view('staff.schedule.index', compact('schedules'));
    }

    public function create()
    {
        $booleanTypes = BooleanType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();

        return view('staff.schedule.create', compact('booleanTypes', 'timezones'));
    }

    public function edit(StaffSchedule $staffSchedule)
    {

        $staffScheduleDay=StaffScheduleDay::where('staff_schedule_id',$staffSchedule->id)->get();
        foreach ($staffScheduleDay as $day)
        $staffSchedulesDaysHours=StaffScheduleDayHour::all();
        $booleanTypes = BooleanType::getItems();
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $data = [
            'booleanTypes' => $booleanTypes,
            'timezones' => $timezones,
            'staffSchedule' => $staffSchedule,
            'staffScheduleDay'=>$staffScheduleDay,
            'staffSchedulesDayHour'=>$staffSchedulesDaysHours
          ];
          return view('staff.schedule.edit', $data);

    }

    public function store(Request $request)
    {
        $staff = auth()->guard(GuardType::STAFF)->user();

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('staff_schedules')->where('staff_id', $staff->id)],
            'timezone' => ['required', 'string']
        ])->validate();

        $data = $request->all();
        $data['staff_id'] = $staff->id;

        $staffSchedule = StaffSchedule::create($data);

        if (!$staffSchedule) return redirect()->route('staff.Schedule.create')->withInput();

        foreach (DayType::getItems() as $day)
        {
            StaffScheduleDay::create([
                'staff_schedule_id' => $staffSchedule->id,
                'day' => $day
            ]);
        }

        return redirect()->route('staff.schedule.edit', $staffSchedule->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, StaffSchedule $staffSchedule)
    {
        $staff = auth()->guard(GuardType::STAFF)->user();

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('staff_schedules')->where('staff_id', $staff->id)->ignoreModel($staffSchedule)],
            'timezone' => ['required', 'string']
        ])->validate();

        $staffSchedule->update($request->all());

        return redirect()->route('staff.schedule.edit', $staffSchedule->id)->with('success', trans('messages.itemUpdated'));
    }

    public function makeDefault(StaffSchedule $staffSchedule)
    {
        $staff = auth()->guard(GuardType::STAFF)->user()->id;
        StaffSchedule::where('id', $staff)->update(["is_default" => "no"]);
        $staffSchedule->update(["is_default" => "yes"]);

        return redirect()->route('staff.schedule.edit', $staffSchedule->id)->with('success', trans('messages.itemUpdated'));
    }

    public function changeDayStatus(StaffScheduleDay $staffScheduleDay)
    {
        $status = ($staffScheduleDay->status == StatusType::ACTIVE) ? StatusType::DISABLED : StatusType::ACTIVE;
        $staffScheduleDay->update(['status' => $status]);
        return redirect()->route('staff.schedule.edit', $staffScheduleDay->staff_schedule_id)->with('success', trans('messages.itemUpdated'));
    }

    public function addHour(Request $request,$staffSchedule)
    {


        $request->validate([
            'staff_schedule_day_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);


        $staffSchedulesDaysHours=new StaffScheduleDayHour();
        $staffSchedulesDaysHours->staff_schedule_day_id = $request->staff_schedule_day_id;
        $staffSchedulesDaysHours->start_time =$request->start_time;
        $staffSchedulesDaysHours->end_time =$request->end_time;
        $staffSchedulesDaysHours->save();


            $staffScheduleDay = StaffScheduleDay::where('id',$staffSchedule)->first();

            $staffSchedule=StaffSchedule::where('id',$staffScheduleDay->staff_schedule_id)->first();

            $staffSchedulesDaysHours = StaffScheduleDayHour::where('staff_schedule_day_id',$staffScheduleDay->id)->get();


            $timezones = Timezone::orderBy("region", 'asc')->get();
            $data = [
                'staffSchedule' => $staffSchedule,
                'staffScheduleDay' => $staffScheduleDay,
                'staffSchedulesDayHour' => $staffSchedulesDaysHours,
                'timezones' => $timezones
            ];


            return view('staff.schedule.edit', $data)->with('success', trans('messages.itemUpdated'));
    }

   public function updateHour(Request $request,$dayId)
    {            $data=$request->validate([

                    'start_time' => 'required',
                    'end_time' => 'required'
                     ]);


        StaffScheduleDayHour::where('staff_schedule_day_id',$dayId)->update($data);

                 $staffScheduleDayHour1=StaffScheduleDayHour::where('staff_schedule_day_id',$dayId)->get();


                 $staffScheduleDays=StaffScheduleDay::where('id',$dayId)->first();

                 $staffSchedule = StaffSchedule::where('id', $staffScheduleDays->staff_schedule_id)->first();

                $timezones = Timezone::orderBy("region", 'asc')->get();
                $data = [
                    'staffSchedule' => $staffSchedule,
                    'staffScheduleDay' => $staffScheduleDays,
                    'staffSchedulesDayHour' => $staffScheduleDayHour1,
                    'timezones' => $timezones
                ];

                return view('staff.schedule.edit', $data)->with('success', trans('messages.itemUpdated'));
     }

}
