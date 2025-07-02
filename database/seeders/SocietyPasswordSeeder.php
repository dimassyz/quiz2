<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SocietyPasswordSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('societies')->where('password', '121212')->update([
            'password' => Hash::make('121212')
        ]);
        $this->command->info('Societies passwords have been hashed successfully.');
    }
}
