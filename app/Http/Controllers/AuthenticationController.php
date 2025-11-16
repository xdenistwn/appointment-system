<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseFormatter;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    protected $authenticationService;

    public function __construct(AuthenticationService $service)
    {
        $this->authenticationService = $service;
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed',
            'username' => 'required|email|unique:users,email',
            'service_type' => 'required|exists:services_types,id',
            'meet_location' => 'required|max:100',
            'meet_timezone' => 'required|max:2'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();
        $this->authenticationService->registerUser($payload);

        return ResponseFormatter::success([
            'is_sent' => true,
        ]);
    }

    public function resendOtp()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();
        $this->authenticationService->resendOtp($payload);

        return ResponseFormatter::success([
            'is_sent' => true,
        ]);
    }
}
