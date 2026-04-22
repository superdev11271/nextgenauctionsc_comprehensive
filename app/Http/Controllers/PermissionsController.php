<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\StorePermissionsRequest;
use App\Http\Requests\Website\UpdatePermissionsRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:view_permissions'])->only('index');
        $this->middleware(['permission:add_permissions'])->only(['create', 'store']);
        $this->middleware(['permission:edit_permissions'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_permissions'])->only('destroy');
    }


    /**
     * Display a listing of Permission.
     *
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $approved = null;
        $data = Permission::select('*');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $data->where('name', 'like', '%' . $sort_search . '%');
        }
        $data = $data->paginate(15);

        return view('backend.permission.index', compact('data', 'sort_search'));
    }

    /**
     * Show the form for creating new Permission.
     *
     */
    public function create()
    {
        return view('backend.permission.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\Admin\StorePermissionsRequest  $request
     */
    public function store(StorePermissionsRequest $request)
    {
        if ($permission = Permission::create($request->all())) {
            // $role = Role::findById(1);
            // $role->syncPermissions([$permission->id]);;
            flash(translate('Permission has been inserted successfully'))->success();
            return redirect()->back();
        }
        flash(translate('Something went wrong'))->error();
        return redirect()->back();
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     */
    public function edit(Permission $permission)
    {
        return view('backend.permission.edit', compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdatePermissionsRequest  $request
     * @param  int  $id
     */
    public function update(UpdatePermissionsRequest $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100|unique:permissions,name,' . $permission->id,
        ]);

        if ($permission->update($request->all())) {
            flash(translate('Permission has been updated successfully'))->success();
            return redirect()->back();
        }
        flash(translate('Something went wrong'))->error();
        return redirect()->back();
    }

    public function show(Permission $permission)
    {
        //
    }


    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     */
    public function destroy(Request $request)
    {

        $permission = Permission::find(decrypt($request->id));

        if ($permission && $permission->delete()) {
            flash(translate('Permission has been deleted successfully'))->success();
            return redirect()->back();
        }
        flash(translate('Something went wrong'))->error();
        return redirect()->back();
    }
}
