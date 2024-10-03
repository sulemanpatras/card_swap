<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AdminController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admin,email',
            'user_type' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|exists:roles,name', 
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $admin = Admin::create($input);
        $admin->assignRole($input['role']);

        return $this->sendResponse(['admin' => $admin], 'Admin registered successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:admin,email,' . $request->id,
            'user_type' => 'nullable|string|max:255',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $admin = Admin::find($request->id);
        if (!$admin) {
            return $this->sendError('Admin not found.', [], 404);
        }

        if ($request->has('name')) {
            $admin->name = $request->name;
        }
        if ($request->has('email')) {
            $admin->email = $request->email;
        }
        if ($request->has('user_type')) {
            $admin->user_type = $request->user_type;
        }
        if ($request->has('role')) {
            $admin->syncRoles($request->role);
        }

        if (!auth()->user()->hasPermissionTo('update-admin')) {
            return $this->sendError('Forbidden', [], 403);
        }

        $admin->save();
        return $this->sendResponse(['admin' => $admin], 'Admin updated successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return $this->sendError('Unauthenticated.', ['error' => 'Invalid credentials']);
        }

        $role = $admin->roles->first();
        $permissions = $admin->getPermissionsViaRoles();

        return $this->sendResponse([
            'token' => $admin->createToken('AdminAccess')->plainTextToken,
            'name' => $admin->name,
            'user_type' => $admin->user_type,
            'role_id' => optional($role)->id,
            'role_name' => optional($role)->name,
            'permissions' => $permissions->pluck('name')->toArray(),
        ], 'Login successful.');
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,5',
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $card = Card::find($request->id);
        if (!$card) {
            return $this->sendError('Card not found.', [], 404);
        }

        if (!auth()->user()->hasPermissionTo('update-card-status')) {
            return $this->sendError('Forbidden', [], 403);
        }

        $card->status = $request->status;
        $card->save();

        return $this->sendResponse(['card' => $card], 'Status updated successfully.');
    }

    public function updateUserStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,5',
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $user = User::find($request->id);
        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        if (!auth()->user()->hasPermissionTo('update-user-status')) {
            return $this->sendError('Forbidden', [], 403);
        }

        $user->status = $request->status;
        $user->save();

        return $this->sendResponse(['User' => $user], 'Status updated successfully.');
    }

    public function requestOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:admin,email',
    ]);

    if ($validator->fails()) {
        return $this->sendValidateError($validator->errors());
    }

    $admin = Admin::where('email', $request->email)->first();

    if ($admin->otp_expires_at && now()->isBefore($admin->otp_expires_at)) {
        return $this->sendError('OTP request failed.', ['error' => 'Please wait until the current OTP expires.']);
    }

    $otp = random_int(100000, 999999);
    $expiresAt = now()->addMinutes(3);

    $admin->verification_code = $otp;
    $admin->otp_expires_at = $expiresAt;
    $admin->save();

    Mail::to($request->email)->send(new OtpMail($otp));

    return $this->sendResponse([], 'OTP sent to your email.');
}


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|integer',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        $admin = Admin::where('verification_code', $request->otp)->first();
        if (!$admin) {
            return $this->sendError('Invalid OTP.', []);
        }

        if (now()->isAfter($admin->otp_expires_at)) {
            return $this->sendError('OTP has expired. Please request a new one.', []);
        }

        if (Hash::check($request->new_password, $admin->password)) {
            return $this->sendError('The new password must be different from the current password.', [], 400);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->email_verified_at = now();
        $admin->save();

        return $this->sendResponse([], 'Password updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $admin = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!$admin->hasPermissionTo('change-password-admin')) {
            return $this->sendError('Forbidden', [], 403);
        }

        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return $this->sendError('Current password is incorrect.', []);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return $this->sendResponse([], 'Password changed successfully.');
    }
}
