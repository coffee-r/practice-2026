<?php

namespace App\Packages\User\Usecase;

use App\Packages\User\Domain\User;

class UserData
{
    public readonly int $userId;
    public readonly string $userName;
    public readonly string $userNameFurigana;
    public readonly string $userGender;
    public readonly int $userBirthdayYear;
    public readonly int $userBirthdayMonth;
    public readonly string $userEmail;
    public readonly string $userPassword;
    public readonly string $userPostalCode;
    public readonly string $userAddressPrefectures;
    public readonly string $userAddressMunicipalities;
    public readonly string $userAddressOthers;
    public readonly string $userTel;
    public readonly bool $userEmailMagazineSubscription;

    public function __construct(User $user)
    {
        $this->userId = $user->userId->value;
        $this->userName = $user->userName->value;
        $this->userNameFurigana = $user->userNameFurigana->value;
        $this->userGender = $user->userGender->value;
        $this->userBirthdayYear = $user->userBirthday->year;
        $this->userBirthdayMonth = $user->userBirthday->month;
        $this->userEmail = $user->userEmail->value;
        $this->userPassword = $user->userPassword->value;
        $this->userPostalCode = $user->userPostalCode->value;
        $this->userAddressPrefectures = $user->userAddress->prefectures;
        $this->userAddressMunicipalities = $user->userAddress->municipalities;
        $this->userAddressOthers = $user->userAddress->others;
        $this->userTel = $user->userTel->value;
        $this->userEmailMagazineSubscription = $user->userEmailMagazineSubscription->value;
    }
}