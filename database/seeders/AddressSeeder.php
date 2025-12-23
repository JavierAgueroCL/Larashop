<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure user 1 exists, usually created by UserSeeder
        $user = User::find(1);

        if (!$user) {
            return;
        }

        // Shipping Address
        Address::create([
            'user_id' => $user->id,
            'address_type' => 'shipping',
            'alias' => 'Casa',
            'first_name' => $user->first_name ?? 'Admin',
            'last_name' => $user->last_name ?? 'User',
            'address_line_1' => 'Av. Providencia 1234',
            'region_id' => 7, // RM
            'comuna_id' => 96, // Providencia
            'country_code' => 'CL',
            'phone' => '+56912345678',
            'is_default' => true,
        ]);

        // Billing Address
        Address::create([
            'user_id' => $user->id,
            'address_type' => 'billing',
            'alias' => 'Oficina',
            'first_name' => $user->first_name ?? 'Admin',
            'last_name' => $user->last_name ?? 'User',
            'company' => 'LaraShop SpA',
            'address_line_1' => 'Moneda 999',
            'address_line_2' => 'Oficina 303',
            'region_id' => 7, // RM
            'comuna_id' => 86, // Santiago
            'country_code' => 'CL',
            'phone' => '+56222222222',
            'is_default' => true,
        ]);
    }
}