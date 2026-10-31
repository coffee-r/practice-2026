<?php

namespace App\Packages\User\Domain;

class User
{
    public readonly UserId $userId;
    public UserName $userName;
    public UserNameFurigana $userNameFurigana;
    public UserGender $userGender;
    public UserBirthday $userBirthday;
    public UserEmail $userEmail;
    public UserPassword $userPassword;
    public UserPostalCode $userPostalCode;
    public UserAddress $userAddress;
    public UserTel $userTel;
    public UserEmailMagazineSubscription $userEmailMagazineSubscription;

    public function __construct(
        UserId $userId,
        Username $userName,
        UserNameFurigana $userNameFurigana,
        UserGender $userGender,
        UserBirthday $userBirthday,
        UserEmail $userEmail,
        UserPassword $userPassword,
        UserPostalCode $userPostalCode,
        UserAddress $userAddress,
        UserTel $userTel,
        UserEmailMagazineSubscription $userEmailMagazineSubscription)
        {
            $this->userId = $userId;
            $this->userName = $userName;
            $this->userNameFurigana = $userNameFurigana;
            $this->userGender = $userGender;
            $this->userBirthday = $userBirthday;
            $this->userEmail = $userEmail;
            $this->userPassword = $userPassword;
            $this->userPostalCode = $userPostalCode;
            $this->userAddress = $userAddress;
            $this->userTel = $userTel;
            $this->userEmailMagazineSubscription = $userEmailMagazineSubscription;
        }
}
