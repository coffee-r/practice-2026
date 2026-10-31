<?php

namespace Tests\Feature;

use App\Libraries\PostDeleteAction;
use App\Models\Posts;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use Exception;

class PostDeleteActionTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true; // setUp時にデータベースをリフレッシュ
    protected $namespace = 'App'; // マイグレーションファイルの名前空間

    /**
     * @doesNotPerformAssertions
     */
    public function test正常系()
    {
        $fabricator = new Fabricator(Posts::class);
        $fabricator->setOverrides(['user_id' => 1, 'message' => 'Post Message']);
        $fabricator->create();

        $postModel = model(Posts::class);
        $postDeleteAction = new PostDeleteAction();
        $postDeleteAction($postModel, 1, 1);
    }

    public function test存在しない投稿()
    {
        $postModel = model(Posts::class);
        $postDeleteAction = new PostDeleteAction();
        $this->expectException(Exception::class);
        $postDeleteAction($postModel, 9999, 1);
    }

    public function test他人の投稿は削除できない()
    {
        $fabricator = new Fabricator(Posts::class);
        $fabricator->setOverrides(['user_id' => 1, 'message' => 'Post Message']);
        $fabricator->create();
        
        $postModel = model(Posts::class);
        $postDeleteAction = new PostDeleteAction();
        $this->expectException(Exception::class);
        $postDeleteAction($postModel, 1, 2);
    }
}