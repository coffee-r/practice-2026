<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => '99991',
                'message' => 'testmessage1',
            ],
            [
                'user_id' => '99992',
                'message' => 'testmessage2',
            ],
            [
                'user_id' => '99993',
                'message' => 'testmessage3',
            ]
        ];

        $this->db->table('posts')->insertBatch($data);

    }
}