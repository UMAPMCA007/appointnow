<?php

namespace App\Http\Controllers\Business;

use App\Enums\FieldType;
use App\Enums\GuardType;
use App\Enums\StatusType;
use App\Models\BusinessServiceSignupForm;
use App\Models\BusinessServiceSignupFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class BusinessServiceSignupFormController extends Controller
{
    public function index()
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        $serviceSignupForms = $user->business->serviceSignupForms;
        return view('business.businessServiceSignupForm.index', compact('serviceSignupForms'));
    }

    public function create()
    {
        $statusTypes = StatusType::getItems();
        return view('business.businessServiceSignupForm.create', compact('statusTypes'));
    }

    public function edit(BusinessServiceSignupForm $businessServiceSignupForm)
    {
        $data = [
            'statusTypes' => StatusType::getItems(),
            'businessServiceSignupForm' => $businessServiceSignupForm,
            'fieldTypes' => FieldType::getItems()
        ];
        return view('business.businessServiceSignupForm.edit', $data);
    }

    public function store(Request $request)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'   => ['required', 'string', 'max:255', Rule::unique('business_service_signup_forms')->where("business_id", $user->business->id)],
            'status' => ['required', Rule::in(StatusType::getItems())]
        ])->validate();

        $data = $request->all();
        $data['business_id'] = $user->business->id;

        $businessServiceSignupForm = BusinessServiceSignupForm::create($data);

        if (!$businessServiceSignupForm) return redirect()->route('business.businessServiceSignupForm.create')->withInput();

        return redirect()->route('business.businessServiceSignupForm.edit', $businessServiceSignupForm->id)->with('success', trans('messages.itemCreated'));
    }

    public function update(Request $request, BusinessServiceSignupForm $businessServiceSignupForm)
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();

        Validator::make($request->all(), [
            'name'   => ['required', 'string', 'max:255', Rule::unique('business_service_signup_forms')->where("business_id", $user->business->id)->ignoreModel($businessServiceSignupForm)],
            'status' => ['required', Rule::in(StatusType::getItems())]
        ])->validate();

        $businessServiceSignupForm->update($request->all());

        return redirect()->route('business.businessServiceSignupForm.edit', $businessServiceSignupForm->id)->with('success', trans('messages.itemUpdated'));
    }

    public function field(Request $request)
    {
        $data = $request->all();
        $data['options'] = (!empty($data['options'])) ? json_encode($data['options']) : null;
        if ($data['fieldMethod'] == "create")
        {
            BusinessServiceSignupFormField::create($data);
            return redirect()->route('business.businessServiceSignupForm.edit', $data['signup_form_id'])->with('success', trans('messages.itemCreated'));
        }
        else
        {
            $signupFormId = BusinessServiceSignupFormField::find($data['business_service_signup_form_field_id']);
            $signupFormId->update($data);

            return redirect()->route('business.businessServiceSignupForm.edit', $data['signup_form_id'])->with('success', trans('messages.itemUpdated'));
        }
    }

    public function deleteField(BusinessServiceSignupFormField $businessServiceSignupFormField)
    {
        $signupFormId = $businessServiceSignupFormField->signup_form_id;
        $businessServiceSignupFormField->delete();
        return redirect()->route('business.businessServiceSignupForm.edit', $signupFormId)->with('success', trans('messages.itemDeleted'));
    }
}
