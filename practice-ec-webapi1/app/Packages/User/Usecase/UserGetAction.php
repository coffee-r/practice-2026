<?php

namespace App\Packages\User\Usecase;

use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\UserId;
use App\Packages\User\Domain\UserRepositoryInterface;


class UserGetAction
{
    private readonly UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $userId)
    {
        $user = $this->userRepository->findById(new UserId($userId));
        
        if($user == null) {
            throw new UsecaseException('ID:'.$userId.' のユーザーは存在しません', 404);
        }

        return new UserData($user);
    }
}