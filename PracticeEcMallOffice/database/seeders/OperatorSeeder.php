<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operator::insert([
            [
                'name' => 'こーひーあーる1',
                'password' => 'test1111',
            ],
            [
                'name' => 'こーひーあーる2',
                'password' => 'test2222',
            ],
        ]);
    }
}
