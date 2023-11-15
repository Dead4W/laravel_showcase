<?php

namespace App\User\Controllers;

use App\Common\Http\Resources\ResponseResource;
use Illuminate\Support\Facades\Auth;

class UserController
{

    public function self(): ResponseResource {
        $user = Auth::user();

        return new ResponseResource($user);
    }

}
