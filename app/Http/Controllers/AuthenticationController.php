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
            'username' => 'required|unique:host_details,username',
            'service_type' => 'required|exists:service_types,uuid',
            'meet_location' => 'required|max:100',
            'meet_timezone' => 'required|numeric'
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
            'email' => 'required|email|exists:users,email'
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

    public function verifyOtp()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|exists:otps,otp'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();

        if ($this->authenticationService->verifyOtp($payload)) {
            return ResponseFormatter::success([
                'is_sent' => true,
            ]);
        }

        return ResponseFormatter::error(400, 'Invalid OTP');
    }

    public function verifyRegister()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|exists:otps,otp'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();
        
        if ($token = $this->authenticationService->verifyRegister($payload)) {
            return ResponseFormatter::success([
                'token' => $token,
            ]);
        }

        return ResponseFormatter::error(400, 'Invalid OTP');
    }
}
