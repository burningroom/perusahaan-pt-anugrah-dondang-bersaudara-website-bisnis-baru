<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_balance","view_any_balance","create_balance","update_balance","restore_balance","restore_any_balance","replicate_balance","reorder_balance","delete_balance","delete_any_balance","force_delete_balance","force_delete_any_balance","view_bill","view_any_bill","create_bill","update_bill","restore_bill","restore_any_bill","replicate_bill","reorder_bill","delete_bill","delete_any_bill","force_delete_bill","force_delete_any_bill","view_configuration","view_any_configuration","create_configuration","update_configuration","restore_configuration","restore_any_configuration","replicate_configuration","reorder_configuration","delete_configuration","delete_any_configuration","force_delete_configuration","force_delete_any_configuration","view_cost","view_any_cost","create_cost","update_cost","restore_cost","restore_any_cost","replicate_cost","reorder_cost","delete_cost","delete_any_cost","force_delete_cost","force_delete_any_cost","view_expense::account","view_any_expense::account","create_expense::account","update_expense::account","restore_expense::account","restore_any_expense::account","replicate_expense::account","reorder_expense::account","delete_expense::account","delete_any_expense::account","force_delete_expense::account","force_delete_any_expense::account","view_finance","view_any_finance","create_finance","update_finance","restore_finance","restore_any_finance","replicate_finance","reorder_finance","delete_finance","delete_any_finance","force_delete_finance","force_delete_any_finance","view_history::transaction","view_any_history::transaction","create_history::transaction","update_history::transaction","restore_history::transaction","restore_any_history::transaction","replicate_history::transaction","reorder_history::transaction","delete_history::transaction","delete_any_history::transaction","force_delete_history::transaction","force_delete_any_history::transaction","view_invoice","view_any_invoice","create_invoice","update_invoice","restore_invoice","restore_any_invoice","replicate_invoice","reorder_invoice","delete_invoice","delete_any_invoice","force_delete_invoice","force_delete_any_invoice","view_ledger","view_any_ledger","create_ledger","update_ledger","restore_ledger","restore_any_ledger","replicate_ledger","reorder_ledger","delete_ledger","delete_any_ledger","force_delete_ledger","force_delete_any_ledger","view_receipt","view_any_receipt","create_receipt","update_receipt","restore_receipt","restore_any_receipt","replicate_receipt","reorder_receipt","delete_receipt","delete_any_receipt","force_delete_receipt","force_delete_any_receipt","view_request::pandu","view_any_request::pandu","create_request::pandu","update_request::pandu","restore_request::pandu","restore_any_request::pandu","replicate_request::pandu","reorder_request::pandu","delete_request::pandu","delete_any_request::pandu","force_delete_request::pandu","force_delete_any_request::pandu","view_rkbm","view_any_rkbm","create_rkbm","update_rkbm","restore_rkbm","restore_any_rkbm","replicate_rkbm","reorder_rkbm","delete_rkbm","delete_any_rkbm","force_delete_rkbm","force_delete_any_rkbm","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_rpkro","view_any_rpkro","create_rpkro","update_rpkro","restore_rpkro","restore_any_rpkro","replicate_rpkro","reorder_rpkro","delete_rpkro","delete_any_rpkro","force_delete_rpkro","force_delete_any_rpkro","view_spk::pandu","view_any_spk::pandu","create_spk::pandu","update_spk::pandu","restore_spk::pandu","restore_any_spk::pandu","replicate_spk::pandu","reorder_spk::pandu","delete_spk::pandu","delete_any_spk::pandu","force_delete_spk::pandu","force_delete_any_spk::pandu","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","view_vessel","view_any_vessel","create_vessel","update_vessel","restore_vessel","restore_any_vessel","replicate_vessel","reorder_vessel","delete_vessel","delete_any_vessel","force_delete_vessel","force_delete_any_vessel","page_EditProfilePage","widget_StatsOverviewWidget"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
