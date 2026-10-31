<?php

namespace Tests\Feature;

use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Test\AuthenticationTesting;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class PostFeatureTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;
    use AuthenticationTesting;

    protected $refresh = true; // setUp時にデータベースをリフレッシュ
    protected $namespace = ['App', 'CodeIgniter\Shield', 'CodeIgniter\Settings']; // マイグレーションファイルの名前空間 CodeIgniter Shieldを動かすためにいくつか追加

    protected function setUp(): void
    {
        parent::setUp();
        helper(['auth', 'setting']);
    }

    public function testIndexゲストユーザーはloginにリダイレクト()
    {   
        $response = $this->get('/posts')
                        ->assertRedirect('/login');
    }

    public function testIndexログインユーザーは普通に閲覧できる()
    {
        $user = fake(UserModel::class);

        $response = $this->actingAs($user)->get('/posts')
                        ->assertOK();
    }
}