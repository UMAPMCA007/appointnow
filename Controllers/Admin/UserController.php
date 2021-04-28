<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LanguageType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Timezone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
    }

    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $statusTypes = StatusType::getItems();
        $roles = Role::pluck('name')->all();
        $languages = LanguageType::getItems();
        return view('admin.user.create', compact('timezones', 'statusTypes', 'roles', 'languages'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email:rfc', 'max:255', Rule::unique('users')->where("deleted_at", null)],
            'code'      => ['nullable', 'string', 'max:10', 'unique:users,code'],
            'status'    => ['required', 'string', Rule::in(StatusType::getItems())],
            'password'  => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'language'  => ['required', 'string', Rule::in(LanguageType::getItems())],
            'user_role' => ['required', 'string']
        ])->validate();

        $data = $request->all();
        $data['email']    = strtolower($data['email']);
        $data['password'] = bcrypt($data['password']);
        $data['code']     = (empty($data['code'])) ? Str::random(10) : $data['code'];
        unset($data['password_confirmation']);

        $user = User::create($data);

        if (!$user) return redirect()->route('user.create')->withInput();

        $user->assignRole($data['user_role']);

        return redirect()->route('admin.user.edit', $user->id)->with('success', trans('messages.itemCreated'));
    }

    public function edit(User $user)
    {
        $timezones = Timezone::orderBy("region", 'asc')->get();
        $statusTypes = StatusType::getItems();
        $roles = Role::pluck('name')->all();
        $languages = LanguageType::getItems();
        $userRole = $user->roles->first()->name;
        return view('admin.user.edit', compact('user', 'timezones', 'statusTypes', 'roles', 'languages', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email:rfc', 'max:255', Rule::unique('users')->where("deleted_at", null)->ignoreModel($user)],
            'code'      => ['nullable', 'string', 'max:10', Rule::unique('users')->ignoreModel($user)],
            'status'    => ['required', 'string', Rule::in(StatusType::getItems())],
            'password'  => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'language'  => ['required', 'string', Rule::in(LanguageType::getItems())],
            'user_role' => ['required', 'string']
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

        $user->update($data);
        $user->syncRoles($data['user_role']);

        return redirect()->route('admin.user.edit', $user->id)->with('success', trans('messages.itemUpdated'));
    }
}
