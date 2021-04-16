<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\IBaseRepository;
use Illuminate\Http\Request;

interface IUserRepository extends IBaseRepository
{
    public function create(array $user);

    public function where($attribute, $value);
}
