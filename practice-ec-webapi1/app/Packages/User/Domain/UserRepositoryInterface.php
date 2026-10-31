<?php

namespace App\Packages\User\Domain;

interface UserRepositoryInterface
{
    public function findById(UserId $id);
    public function findByEmail(UserEmail $email);
    public function save(User $user);
}