<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserAddress
{
    public readonly string $prefectures;
    public readonly string $municipalities;
    public readonly string $others;

    public function __construct(string $prefectures, string $municipalities, string $others)
    {
        if (in_array($prefectures, self::PREFECTURES) === false){
            throw new DomainException($prefectures.'は存在しない都道府県です');
        }

        if (empty($municipalities) || preg_replace('/( |　)/', '', $municipalities) == ''){
            throw new DomainException('市区町村は必須です');
        }

        if (preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', $municipalities)){
            throw new DomainException('市区町村に絵文字は入力できません');
        }

        if (mb_strlen($municipalities) > 30){
            throw new DomainException('市区町村は30文字以内です');
        }

        if (empty($others) || preg_replace('/( |　)/', '', $others) == ''){
            throw new DomainException('番地・建物名・部屋番号は必須です');
        }

        if (preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', $others)){
            throw new DomainException('番地・建物名・部屋番号に絵文字は入力できません');
        }

        if (mb_strlen($others) > 30){
            throw new DomainException('番地・建物名・部屋番号は30文字以内です');
        }

        $this->prefectures = $prefectures;
        $this->municipalities = $municipalities;
        $this->others = $others;
    }

    private const PREFECTURES = [
        '北海道',
        '青森県',
        '岩手県',
        '宮城県',
        '秋田県',
        '山形県',
        '福島県',
        '茨城県',
        '栃木県',
        '群馬県',
        '埼玉県',
        '千葉県',
        '東京都',
        '神奈川県',
        '新潟県',
        '富山県',
        '石川県',
        '福井県',
        '山梨県',
        '長野県',
        '岐阜県',
        '静岡県',
        '愛知県',
        '三重県',
        '滋賀県',
        '京都府',
        '大阪府',
        '兵庫県',
        '奈良県',
        '和歌山県',
        '鳥取県',
        '島根県',
        '岡山県',
        '広島県',
        '山口県',
        '徳島県',
        '香川県',
        '愛媛県',
        '高知県',
        '福岡県',
        '佐賀県',
        '長崎県',
        '熊本県',
        '大分県',
        '宮崎県',
        '鹿児島県',
        '沖縄県',
    ];
}