<?php

namespace Database\Seeders;

use App\Models\Identity\Permission;
use App\Models\Identity\Role;
use Illuminate\Database\Seeder;

class GaragePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'garage.company.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.company.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.company.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.company.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.branch.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.branch.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.branch.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.branch.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.department.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.department.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.department.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.department.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.employee.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.employee.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.employee.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.employee.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.customer.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.customer.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.customer.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.customer.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.vehicle.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.vehicle.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.vehicle.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.vehicle.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.appointment.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.appointment.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.appointment.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.appointment.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.repair.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.repair.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.repair.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.repair.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.repair.diagnose', 'module' => 'garage', 'action' => 'diagnose'],
            ['name' => 'garage.repair.estimate', 'module' => 'garage', 'action' => 'estimate'],
            ['name' => 'garage.repair.approve', 'module' => 'garage', 'action' => 'approve'],
            ['name' => 'garage.inventory.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.inventory.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.inventory.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.inventory.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.purchase_order.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.purchase_order.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.purchase_order.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.purchase_order.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.invoice.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.invoice.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.invoice.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.invoice.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.payment.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.payment.create', 'module' => 'garage', 'action' => 'create'],
            ['name' => 'garage.payment.update', 'module' => 'garage', 'action' => 'update'],
            ['name' => 'garage.payment.delete', 'module' => 'garage', 'action' => 'delete'],
            ['name' => 'garage.report.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.report.export', 'module' => 'garage', 'action' => 'export'],
            ['name' => 'garage.settings.view', 'module' => 'garage', 'action' => 'view'],
            ['name' => 'garage.settings.update', 'module' => 'garage', 'action' => 'update'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        $garageOwnerRole = Role::where('name', 'GARAGE_OWNER')->first();
        if ($garageOwnerRole) {
            $garageOwnerRole->permissions()->sync(Permission::where('module', 'garage')->pluck('id'));
        }

        $adminRole = Role::where('name', 'ADMIN')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::all()->pluck('id'));
        }
    }
}
