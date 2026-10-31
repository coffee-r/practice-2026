<?php

namespace App\Packages\User\Infrastructure;

use App\Models\User as ModelsUser;
use App\Packages\User\Domain\User;
use App\Packages\User\Domain\UserAddress;
use App\Packages\User\Domain\UserBirthday;
use App\Packages\User\Domain\UserEmail;
use App\Packages\User\Domain\UserEmailMagazineSubscription;
use App\Packages\User\Domain\UserGender;
use App\Packages\User\Domain\UserId;
use App\Packages\User\Domain\UserName;
use App\Packages\User\Domain\UserNameFurigana;
use App\Packages\User\Domain\UserPassword;
use App\Packages\User\Domain\UserPostalCode;
use App\Packages\User\Domain\UserRepositoryInterface;
use App\Packages\User\Domain\UserTel;

class UserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id)
    {
        $modelUser = ModelsUser::find($id->value);
        
        if($modelUser == null) {return null;}
    
        return new User(
            new UserId($modelUser->id),
            new UserName($modelUser->name),
            new UserNameFurigana($modelUser->name_furigana),
            new UserGender($modelUser->gender),
            new UserBirthday($modelUser->birthday_year, $modelUser->birthday_month),
            new UserEmail($modelUser->email),
            new UserPassword($modelUser->password),
            new UserPostalCode($modelUser->postal_code),
            new UserAddress($modelUser->address_prefectures, $modelUser->address_municipalities, $modelUser->address_others),
            new UserTel($modelUser->tel),
            new UserEmailMagazineSubscription($modelUser->email_magazine_subscription)
        );
    }
    public function findByEmail(UserEmail $email)
    {
        $modelUser = ModelsUser::where('email', $email->value)->first();

        if($modelUser == null) {return null;}

        return new User(
            new UserId($modelUser->id),
            new UserName($modelUser->name),
            new UserNameFurigana($modelUser->name_furigana),
            new UserGender($modelUser->gender),
            new UserBirthday($modelUser->birthday_year, $modelUser->birthday_month),
            new UserEmail($modelUser->email),
            new UserPassword($modelUser->password),
            new UserPostalCode($modelUser->postal_code),
            new UserAddress($modelUser->address_prefectures, $modelUser->address_municipalities, $modelUser->address_others),
            new UserTel($modelUser->tel),
            new UserEmailMagazineSubscription($modelUser->email_magazine_subscription)
        );
    }

    public function save(User $user)
    {
        $modelUser = ModelsUser::find($user->userId->value);

        if($modelUser == null){
            $modelUser = new ModelsUser();
        }
        
        $modelUser->name = $user->userName->value;
        $modelUser->name_furigana = $user->userNameFurigana->value;
        $modelUser->email = $user->userEmail->value;
        $modelUser->password = $user->userPassword->value;
        $modelUser->postal_code = $user->userPostalCode->value;
        $modelUser->address_prefectures = $user->userAddress->prefectures;
        $modelUser->address_municipalities = $user->userAddress->municipalities;
        $modelUser->address_others = $user->userAddress->others;
        $modelUser->tel = $user->userTel->value;
        $modelUser->birthday_year = $user->userBirthday->year;
        $modelUser->birthday_month = $user->userBirthday->month;
        $modelUser->gender = $user->userGender->value;
        $modelUser->email_magazine_subscription = $user->userEmailMagazineSubscription->value;

        $modelUser->save();
    }
    
}