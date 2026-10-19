<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Evento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@eventhub.com',
            'password' => Hash::make('password'), 'role' => 'admin']);

        $orgA = User::create(['name' => 'Organizador A', 'email' => 'orga@eventhub.com',
            'password' => Hash::make('password'), 'role' => 'organizador']);

        User::create(['name' => 'Organizador B', 'email' => 'orgb@eventhub.com',
            'password' => Hash::make('password'), 'role' => 'organizador']);

        User::create(['name' => 'Asistente', 'email' => 'asistente@eventhub.com',
            'password' => Hash::make('password'), 'role' => 'asistente']);

        Evento::create(['user_id' => $orgA->id, 'titulo' => 'Laravel Conf 2026',
            'descripcion' => 'Conferencia sobre Laravel', 'cupo_maximo' => 100]);
    }
}
