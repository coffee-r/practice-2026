<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['id' => 1, 'name' => '大カテゴリ1',      'parent_category_id' => null, 'depth' => 1, 'sort' => 1],
            ['id' => 2, 'name' => '中カテゴリ1-1',    'parent_category_id' => 1,    'depth' => 2, 'sort' => 2],
            ['id' => 3, 'name' => '小カテゴリ1-1-1',  'parent_category_id' => 2,    'depth' => 3, 'sort' => 3],
            ['id' => 4, 'name' => '大カテゴリ2',      'parent_category_id' => null, 'depth' => 1, 'sort' => 4],
            ['id' => 5, 'name' => '中カテゴリ2-1',    'parent_category_id' => 4,    'depth' => 2, 'sort' => 5],
            ['id' => 6, 'name' => '小カテゴリ2-1-1',  'parent_category_id' => 5,    'depth' => 3, 'sort' => 6],
            ['id' => 7, 'name' => '小カテゴリ2-1-2',  'parent_category_id' => 5,    'depth' => 3, 'sort' => 7],
            ['id' => 8, 'name' => '中カテゴリ2-2',    'parent_category_id' => 4,    'depth' => 2, 'sort' => 8],
            ['id' => 9, 'name' => '小カテゴリ2-2-1',  'parent_category_id' => 8,    'depth' => 3, 'sort' => 9],
            ['id' => 10, 'name' => '小カテゴリ2-2-2', 'parent_category_id' => 8,    'depth' => 3, 'sort' => 10],
            ['id' => 11, 'name' => '大カテゴリ3',     'parent_category_id' => null, 'depth' => 1, 'sort' => 11],
            ['id' => 12, 'name' => '中カテゴリ3-1',   'parent_category_id' => 11,   'depth' => 2, 'sort' => 12],
            ['id' => 13, 'name' => '小カテゴリ3-1-1', 'parent_category_id' => 12,   'depth' => 3, 'sort' => 13],
            ['id' => 14, 'name' => '小カテゴリ3-1-2', 'parent_category_id' => 12,   'depth' => 3, 'sort' => 14],
            ['id' => 15, 'name' => '小カテゴリ3-1-3', 'parent_category_id' => 12,   'depth' => 3, 'sort' => 15],
            ['id' => 16, 'name' => '中カテゴリ3-2',   'parent_category_id' => 11,   'depth' => 2, 'sort' => 16],
            ['id' => 17, 'name' => '小カテゴリ3-2-1', 'parent_category_id' => 16,   'depth' => 3, 'sort' => 17],
            ['id' => 18, 'name' => '小カテゴリ3-2-2', 'parent_category_id' => 16,   'depth' => 3, 'sort' => 18],
            ['id' => 19, 'name' => '小カテゴリ3-2-3', 'parent_category_id' => 16,   'depth' => 3, 'sort' => 19],
            ['id' => 20, 'name' => '中カテゴリ3-3',   'parent_category_id' => 11,   'depth' => 2, 'sort' => 20],
            ['id' => 21, 'name' => '小カテゴリ3-3-1', 'parent_category_id' => 20,   'depth' => 3, 'sort' => 21],
            ['id' => 22, 'name' => '小カテゴリ3-3-2', 'parent_category_id' => 20,   'depth' => 3, 'sort' => 22],
            ['id' => 23, 'name' => '小カテゴリ3-3-3', 'parent_category_id' => 20,   'depth' => 3, 'sort' => 23],
        ];

        ProductCategory::insert($data);
    }
}
