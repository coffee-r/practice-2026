<?php

namespace App\Packages\User\Usecase;

use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\User;
use App\Packages\User\Domain\UserName;
use App\Packages\User\Domain\UserAddress;
use App\Packages\User\Domain\UserBirthday;
use App\Packages\User\Domain\UserEmail;
use App\Packages\User\Domain\UserEmailMagazineSubscription;
use App\Packages\User\Domain\UserGender;
use App\Packages\User\Domain\UserId;
use App\Packages\User\Domain\UserNameFurigana;
use App\Packages\User\Domain\UserPassword;
use App\Packages\User\Domain\UserPostalCode;
use App\Packages\User\Domain\UserRepositoryInterface;
use App\Packages\User\Domain\UserService;
use App\Packages\User\Domain\UserTel;
use Illuminate\Support\Facades\DB;

class UserUpdateAction
{
    private readonly UserRepositoryInterface $userRepository;
    private readonly UserService $userService;


    public function __construct(UserRepositoryInterface $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function __invoke(UserUpdateCommand $userUpdateCommand)
    {
        $user = DB::transaction(function () use($userUpdateCommand) {

            $user = $this->userRepository->findById(new UserId($userUpdateCommand->userId));
        
            if($user == null) {
                throw new UsecaseException('ID:'.$userUpdateCommand->userId.' のユーザーは存在しません', 404);
            }

            $user->userName = new UserName($userUpdateCommand->userName);
            $user->userNameFurigana = new UserNameFurigana($userUpdateCommand->userNameFurigana);
            $user->userGender = new UserGender($userUpdateCommand->userGender);
            $user->userBirthday = new UserBirthday($userUpdateCommand->userBirthdayYear, $userUpdateCommand->userBirthdayMonth);
            $user->userEmail = new UserEmail($userUpdateCommand->userEmail);
            $user->userPassword = new UserPassword($userUpdateCommand->userPassword);
            $user->userPostalCode = new UserPostalCode($userUpdateCommand->userPostalCode);
            $user->userAddress = new UserAddress($userUpdateCommand->userAddressPrefectures, $userUpdateCommand->userAddressMunicipalities, $userUpdateCommand->userAddressOthers);
            $user->userTel = new UserTel($userUpdateCommand->userTel);
            $user->userEmailMagazineSubscription = new UserEmailMagazineSubscription($userUpdateCommand->userEmailMagazineSubscription);

            if ($this->userService->isExists($user)){
                throw new UsecaseException("このメールアドレスは既に使用されています");
            }

            $this->userRepository->save($user);

            return $user;
        });

        return new UserData($user);
    }
}