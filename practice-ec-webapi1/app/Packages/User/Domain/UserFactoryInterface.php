<?php

namespace App\Packages\User\Domain;

interface UserFactoryInterface
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
    );
}