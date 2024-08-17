<?php

namespace App\V1\CMS\Controllers;

use App\V1\CMS\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke()
    {
        return Auth::check() ? new UserResource(Auth::user()) : null;
    }
}
