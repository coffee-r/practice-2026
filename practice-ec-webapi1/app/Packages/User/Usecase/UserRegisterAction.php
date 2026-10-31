<?php

namespace App\Packages\User\Usecase;

use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\User;
use App\Packages\User\Domain\UserName;
use App\Packages\User\Domain\UserAddress;
use App\Packages\User\Domain\UserBirthday;
use App\Packages\User\Domain\UserEmail;
use App\Packages\User\Domain\UserEmailMagazineSubscription;
use App\Packages\User\Domain\UserFactoryInterface;
use App\Packages\User\Domain\UserGender;
use App\Packages\User\Domain\UserNameFurigana;
use App\Packages\User\Domain\UserPassword;
use App\Packages\User\Domain\UserPostalCode;
use App\Packages\User\Domain\UserRepositoryInterface;
use App\Packages\User\Domain\UserService;
use App\Packages\User\Domain\UserTel;
use Illuminate\Support\Facades\DB;

class UserRegisterAction
{
    private readonly UserRepositoryInterface $userRepository;
    private readonly UserFactoryInterface $userFactory;
    private readonly UserService $userService;


    public function __construct(UserRepositoryInterface $userRepository, UserFactoryInterface $userFactory, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userService = $userService;
    }

    public function __invoke(UserRegisterCommand $userRegisterCommand)
    {
        $user = DB::transaction(function () use($userRegisterCommand) {
            $user = $this->userFactory->create(
                new UserName($userRegisterCommand->userName),
                new UserNameFurigana($userRegisterCommand->userNameFurigana),
                new UserGender($userRegisterCommand->userGender),
                new UserBirthday($userRegisterCommand->userBirthdayYear, $userRegisterCommand->userBirthdayMonth),
                new UserEmail($userRegisterCommand->userEmail),
                new UserPassword($userRegisterCommand->userPassword),
                new UserPostalCode($userRegisterCommand->userPostalCode),
                new UserAddress($userRegisterCommand->userAddressPrefectures, $userRegisterCommand->userAddressMunicipalities, $userRegisterCommand->userAddressOthers),
                new UserTel($userRegisterCommand->userTel),
                new UserEmailMagazineSubscription($userRegisterCommand->userEmailMagazineSubscription)
            );
    
            if ($this->userService->isExists($user)){
                throw new UsecaseException("このメールアドレスは既に使用されています");
            }

            $this->userRepository->save($user);

            return $user;
        });

        return new UserData($user);
    }
}