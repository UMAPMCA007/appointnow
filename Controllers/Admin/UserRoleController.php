<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GuardType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function __construct()
    {
        if (auth()->check()) {
            $this->middleware('permission:user-role-list|user-role-create|user-role-edit', ['only' => ['index','store']]);
            $this->middleware('permission:user-role-create', ['only' => ['create','store']]);
            $this->middleware('permission:user-role-edit', ['only' => ['edit','update']]);
        }
    }

    public function index()
    {
        $userRoles = Role::where([["guard_name", GuardType::ADMIN], ['show_on_list', 'yes']])->get();
        return view('admin.role.index', compact('userRoles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', GuardType::ADMIN)->orderBy("group", 'asc')->get();
        return view('admin.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'string', Rule::unique("permissions")->where('guard_name', GuardType::ADMIN)],
            'permission' => ['required', 'array']
        ])->validate();

        $userRole = Role::create([
            'guard_name' => GuardType::ADMIN,
            'name' => $request->name
        ]);

        if ($userRole) {
            $userRole->syncPermissions($request->input('permission'));
        }
        else {
            return redirect()->route('userRole.create')->withInput();
        }

        return redirect()->route('admin.role.edit', $userRole->id)->with('success', trans('messages.itemCreated'));
    }

    public function edit(Role $userRole)
    {
        if ($userRole->guard_name != GuardType::ADMIN || $userRole->name == "Super Admin") return redirect()->route('userRole.index');

        $permissions = Permission::orderBy('name', 'desc')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $userRole->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin.role.edit', compact('userRole', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $userRole)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'string', Rule::unique("permissions")->where('guard_name', GuardType::ADMIN)->ignoreModel($userRole)],
            'permission' => ['required', 'array']
        ])->validate();

        $userRole->update([
            'name' => $request->name
        ]);

        $userRole->syncPermissions($request->input('permission'));

        return redirect()->route('admin.role.edit', $userRole->id)->with('success', trans('messages.itemUpdated'));
    }
}
