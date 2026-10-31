<?php

namespace App\Packages\User\Domain;

class UserService
{
    private readonly UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function isExists(User $user)
    {
        $otherUser = $this->userRepositoryInterface->findByEmail($user->userEmail);
        return $otherUser != null;
    }
}