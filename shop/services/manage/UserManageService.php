<?php

namespace shop\services\manage;

use shop\entities\User;

use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;
use shop\repositories\UserRepository;

class UserManageService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $user->save();

        return $user;
    }


    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->getById($id);

        $user->edit(
            $form->username,
            $form->email
        );

        $user->save($user);
    }

    public function remove($id): void
    {
        $user = $this->repository->getById($id);

        $this->repository->remove($user);
    }
}
