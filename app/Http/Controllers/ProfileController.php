<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }
}
