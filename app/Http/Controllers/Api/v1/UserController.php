<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;

class UserController extends ApiController
{
    use ApiResponser;

    public function __invoke(Request $request)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return $this->validationErrorResponse('No user logon');
        }

        if (! Auth::check()) {
            return $this->validationErrorResponse('Invalid token');
        }    
    }
}