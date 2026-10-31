<?php

namespace App\Packages\User\Infrastructure;

use App\Models\User as ModelsUser;
use App\Packages\User\Domain\User;
use App\Packages\User\Domain\UserAddress;
use App\Packages\User\Domain\UserBirthday;
use App\Packages\User\Domain\UserEmail;
use App\Packages\User\Domain\UserEmailMagazineSubscription;
use App\Packages\User\Domain\UserFactoryInterface;
use App\Packages\User\Domain\UserGender;
use App\Packages\User\Domain\UserId;
use App\Packages\User\Domain\UserName;
use App\Packages\User\Domain\UserNameFurigana;
use App\Packages\User\Domain\UserPassword;
use App\Packages\User\Domain\UserPostalCode;
use App\Packages\User\Domain\UserTel;

class UserFactory implements UserFactoryInterface
{
    public function create(
        Username $userName,
        UserNameFurigana $userNameFurigana,
        UserGender $userGender,
        UserBirthday $userBirthday,
        UserEmail $userEmail,
        UserPassword $userPassword,
        UserPostalCode $userPostalCode,
        UserAddress $userAddress,
        UserTel $userTel,
        UserEmailMagazineSubscription $userEmailMagazineSubscription
    )
    {
        $maxUserId = ModelsUser::max('id');
        $nextUserId = new UserId($maxUserId + 1);

        return new User(
            $nextUserId,
            $userName,
            $userNameFurigana,
            $userGender,
            $userBirthday,
            $userEmail,
            $userPassword,
            $userPostalCode,
            $userAddress,
            $userTel,
            $userEmailMagazineSubscription
        );
    }
}