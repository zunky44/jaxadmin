<?php

namespace Jagat\Jax\Http\Controllers;


use Illuminate\Http\Request;
use Jagat\Jax\Http\Requests\Permission\CreateOrUpdateRequest;
use Jagat\Jax\Resources\PermissionCollection;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Jagat\Jax\Models\Permission;
use Jagat\Jax\Resources\Permission as PermissionResource;
use Auth;

class PermissionController extends Controller
{
    /**
     * @author jagat<jagat.kc34@gmail.com
     * @param Request $request
     * @return PermissionCollection
     */
    public function index(Request $request)
    {
        $permissions =tap(Permission::latest(), function ($query) {
            $query->where(request_intersect([
                'name', 'guard_name', 'pg_id'
            ]));
        })->with('group')->paginate();

        return new PermissionCollection($permissions);
    }

    /**
     * @author jagat<jagat.kc34@gmail.com
     * @param $id
     * @return PermissionResource
     */
    public function show($id)
    {
        return new PermissionResource(Permission::query()->findOrFail($id));
    }

    /**
     * @author jagat<jagat.kc34@gmail.com
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        $attributes = request_intersect([
            'pg_id', 'name', 'guard_name', 'display_name', 'icon', 'sequence', 'description'
        ]);
        $attributes['created_name'] = Auth::user()->name;

        Permission::create($attributes);

        return $this->created();
    }

    /**
     * @author jagat<jagat.kc34@gmail.com
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        $permission = Permission::query()->findOrFail($id);

        $attributes = request_intersect([
            'pg_id', 'name', 'guard_name', 'display_name', 'icon', 'sequence', 'description'
        ]);

        $attributes['updated_name'] = Auth::user()->name;

        $isset = Permission::query()
            ->where(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']])
            ->where('id', '!=', $id)
            ->count();

        if ($isset) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        $permission->update($attributes);

        return $this->noContent();
    }

    /**
     * @author jagat<jagat.kc34@gmail.com
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        permission::query()->findOrFail($id)->delete();

        return $this->noContent();
    }

    /**
     * @author jagat<jagat.kc34@gmail.com
     * @return \Illuminate\Http\JsonResponse
     */
    public function allUserPermission()
    {
        return response()->json(['data' => Auth::user()->getAllPermissions()->pluck('name')]);
    }
}