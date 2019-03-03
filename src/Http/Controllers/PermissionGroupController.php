<?php

namespace Jagat\Jax\Http\Controllers;


use Illuminate\Http\Request;
use Jagat\Jax\Http\Requests\PermissionGroup\CreateOrUpdateRequest;
use Jagat\Jax\Models\PermissionGroup;
use Jagat\Jax\Models\Permission;
use Jagat\Jax\Resources\PermissionGroupCollection;
use Jagat\Jax\Resources\PermissionGroup as PermissionGroupResource;

class PermissionGroupController extends Controller
{
    /**
     * @author Jagat<jagat.kc34@gmail.com>
     * @param Request $request
     * @return PermissionGroupCollection
     */
    public function index(Request $request)
    {
        $permissionGroups = tap(PermissionGroup::latest(), function ($query) {
            $query->where(request_intersect(['name']));
        })->paginate();

        return new PermissionGroupCollection($permissionGroups);
    }

    /**
     * @param $guardName
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardNameForPermissions($guardName)
    {
        $permissionGroups = PermissionGroup::query()
            ->with(['permission' => function ($query) use ($guardName) {
                $query->where('guard_name', $guardName);
            }])
            ->get()->filter(function($item)  {
                return count($item->permission) > 0;
            });

        return response()->json([
            'data' => array_values($permissionGroups->toArray())
        ]);
    }

    /**
     * @author Jagat<jagat.kc34@gmail.com>
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        PermissionGroup::create(request_intersect(['name']));

        return $this->created();
    }

    /**
     * @author Jagat<jagat.kc34@gmail.com>
     * @param $id
     * @return PermissionGroupResource
     */
    public function show($id)
    {
        return new PermissionGroupResource(PermissionGroup::findOrFail($id));
    }

    /**
     * @author Jagat<jagat.kc34@gmail.com>
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        PermissionGroup::findOrFail($id)->update(request_intersect([
            'name'
        ]));

        return $this->noContent();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);

        if (Permission::query()->where('pg_id', $permissionGroup->id)->count()) {
            return $this->unprocesableEtity([
                'pg_id' => 'Please move or delete the vesting permission.'
            ]);
        }

        $permissionGroup->delete();

        return $this->noContent();
    }
}