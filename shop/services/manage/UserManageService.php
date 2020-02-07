<?php

namespace shop\services\manage;

use shop\entities\User;

use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;
use shop\repositories\UserRepository;
use shop\services\RoleManager;
use shop\services\TransactionManager;

class UserManageService
{
    private $repository;
    private $roles;
    private $transaction;

    public function __construct(UserRepository $repository, RoleManager $roles, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user, $form) {
            $user->save();
            $this->roles->assign($user->id, $form->role);
        });

        return $user;
    }


    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->getById($id);

        $user->edit(
            $form->username,
            $form->email
        );

        $this->transaction->wrap(function () use ($user, $form) {
            $user->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function remove($id): void
    {
        $user = $this->repository->getById($id);

        $this->repository->remove($user);
    }
}
