<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermissionGroup::query()->upsert([
            [
                'id'   => 1,
                'name' => 'Nhân viên',
            ],
        ], ['id'], ['name']);

        Permission::query()->upsert([
            [
                'id'          => 1,
                'name'        => 'view_admin',
                'title'       => 'Xem nhân viên',
                'guard_name'  => 'web',
                'description' => 'view',
                'group_id'    => 1
            ], [
                'id'          => 2,
                'name'        => 'add_admin',
                'title'       => 'Thêm nhân viên',
                'guard_name'  => 'web',
                'description' => 'add',
                'group_id'    => 1
            ], [
                'id'          => 3,
                'name'        => 'edit_admin',
                'title'       => 'Cập nhật nhân viên',
                'guard_name'  => 'web',
                'description' => 'edit',
                'group_id'    => 1
            ], [
                'id'          => 4,
                'name'        => 'delete_admin',
                'title'       => 'Xoá nhân viên',
                'guard_name'  => 'web',
                'description' => 'delete',
                'group_id'    => 1
            ], [
                'id'          => 5,
                'name'        => 'sync_permission',
                'title'       => 'Phân quyền',
                'guard_name'  => 'web',
                'description' => 'delete',
                'group_id'    => 1
            ]
        ], ['id'], ['name','guard_name', 'description', 'title', 'group_id']);
    }
}
