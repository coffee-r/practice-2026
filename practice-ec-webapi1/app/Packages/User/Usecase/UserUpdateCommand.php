<?php

namespace App\Packages\User\Usecase;

class UserUpdateCommand
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

    public function __construct(
        int $userId,
        string $userName,
        string $userNameFurigana,
        string $userGender,
        int $userBirthdayYear,
        int $userBirthdayMonth,
        string $userEmail,
        string $userPassword,
        string $userPostalCode,
        string $userAddressPrefectures,
        string $userAddressMunicipalities,
        string $userAddressOthers,
        string $userTel,
        bool $userEmailMagazineSubscription,
    )
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userNameFurigana = $userNameFurigana;
        $this->userGender = $userGender;
        $this->userBirthdayYear = $userBirthdayYear;
        $this->userBirthdayMonth = $userBirthdayMonth;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
        $this->userPostalCode = $userPostalCode;
        $this->userAddressPrefectures = $userAddressPrefectures;
        $this->userAddressMunicipalities = $userAddressMunicipalities;
        $this->userAddressOthers = $userAddressOthers;
        $this->userTel = $userTel;
        $this->userEmailMagazineSubscription = $userEmailMagazineSubscription;
    }
}