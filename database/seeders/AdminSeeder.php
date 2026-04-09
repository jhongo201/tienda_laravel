<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@mitienda.com',
            'password' => Hash::make('password123'),
        ]);

        $admin->assignRole('admin');

        $cliente = User::create([
            'name'     => 'Cliente Demo',
            'email'    => 'cliente@mitienda.com',
            'password' => Hash::make('password123'),
        ]);

        $cliente->assignRole('cliente');
    }
}