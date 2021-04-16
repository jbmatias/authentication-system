<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\User\IUserRepository;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $user) {
        return $this->user->create($user);
    }

    public function where($attribute, $value) {
        return $this->user->where($attribute, $value);
    }
}
