<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Animal;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Animal::truncate();
        
        $animals = [
            [
                'id' => 1,
                'name' => 'ねこ',
                'language' => 'にゃんやっしニャンヤッシ〜ー！？🐱'
            ],
            [
                'id' => 2,
                'name' => 'いぬ',
                'language' => 'わんうおくぅっワンウオクッゥ〜ー！？🐶'
            ],
            [
                'id' => 3,
                'name' => 'ごりら',
                'language' => 'うほっおぉウホッオォ〜ー！？🦍🍌'
            ],
            [
                'id' => 4,
                'name' => 'ひよこ',
                'language' => 'ぴよょピヨョ！？🐥',
            ],
            [
                'id' => 5,
                'name' => 'ぞう',
                'language' => 'ぱおぉんっパオォンッ〜ー！？🐘'
            ],
            [
                'id' => 6,
                'name' => 'かえる',
                'language' => 'けろんげこぉっケロンゲコォッ〜ー！？🐸'
            ],
        ];

        foreach ($animals as $key => $animal) {
            Animal::create([
                'id' => $animal['id'],
                'name' => $animal['name'],
                'language' => $animal['language']
            ]);
        }

    }
}
