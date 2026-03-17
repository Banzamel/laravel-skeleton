<?php

namespace Auth\Models;

use Laravel\Passport\HasApiTokens;
use Administration\Models\User as BaseUser;

class User extends BaseUser
{
    use HasApiTokens;
}
