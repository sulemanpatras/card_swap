<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Services\QrCodeService;

class UserController extends BaseController
{

    public function index()
{
    if (!auth()->user()->hasPermissionTo('all-users')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $users = User::withCount('cards') 
        ->orderBy('name', 'desc')
        ->get();
    $users = $users->map(function ($user) {
        if ($user->image) {
            $user->image = url('storage/' . $user->image);
        }
        $user->totalCards = $user->cards_count; 
        unset($user->cards_count);
        return $user;
    });

    return $this->sendResponse($users, 'Users Data Retrieved');
}

public function register(Request $request, QrCodeService $qrCodeService)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'job_title' => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'gender' => 'nullable|string|max:15',
        'phone' => 'required|unique:users,phone',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
    ]);

    if ($validator->fails()) {
        return $this->sendValidateError($validator->errors());       
    }

    $input = $request->all();
    $input['otp'] = 12345; 
    // $input['otp'] = rand(10000, 99999);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        $input['image'] = $imagePath;
    } else {
        $input['image'] = 'images/default-image.jpg'; 
    }

    $messages = [];

    if (User::where('name', $input['name'])->exists()) {
        $messages[] = 'The name already exists.';
    }
    
    if (User::where('phone', $input['phone'])->exists()) {
        $messages[] = 'The phone has already been taken.';
    }

    if (!empty($messages)) {
        return response()->json([
            'success' => false,
            'message' => implode(', ', $messages) 
        ], 422);
    }

    $contact = User::create($input);

    $card = Card::create([
        'user_id' => $contact->id, 
        'card_title' => 'Default Card', 
        'color_theme' => '#000000', 
        'image' => 'images/default-image.jpg', 
        'cover_photo' => 'images/black_cover.jpeg', 
    ]);

    $qrCodeUrl = $qrCodeService->generateQrCode($card);
    $card->qr_code_url = $qrCodeUrl;
    $card->save();

    $responseData = $contact->makeHidden(['otp']); 

    return $this->sendResponse($responseData, 'User Successfully Registered');
}



public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|max:15',
        'otp' => 'required|numeric|digits:5',
    ]);

    if ($validator->fails()) {
        return $this->sendValidateError($validator->errors());       
    }

    $contact = User::where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->first();

    if ($contact) {
        if ($contact->status == 1) {
            $token = $contact->createToken('Chat')->plainTextToken;

            return $this->sendResponse([
                'user' => $contact,
                'token' => $token,
            ], 'Login Successful');
        } elseif ($contact->status == 5) {
            return $this->sendError('Your account is not active. Please contact support.'); 
        }
    }

    return $this->sendError('Invalid OTP or phone not found. Please check and try again.');
}




    public function edit(Request $request)
    {
        $contact = User::findOrFail($request->id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'job_title' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return $this->sendValidateError($validator->errors());       
        }

        if (User::where('name', $request['name'])->exists()) {
            return response()->json(['error' => 'The name already exists.'], 422);
        }
        if (User::where('phone', $request['phone'])->exists()) {
            return response()->json(['error' => 'The phone already exists.'], 422);
        }
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $contact->image = $imagePath; 
        }else {
            $input['image'] = 'images/default-image.jpg'; 
        }
    
        $contact->update($request->only([
            'name', 'job_title', 'company_name', 'phone', 'card_title', 'color_theme'
        ]));
    
        if ($contact->image) {
            $contact->image = url('storage/' . $contact->image);
        }
    
        return $this->sendResponse([
            'user' => $contact,
        ], 'User Details Updated Suceessfully');
    }

    public function verifyOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|max:15',
        'otp' => 'required|numeric|digits:5',
    ]);

    if ($validator->fails()) {
        return $this->sendValidateError($validator->errors());       
    }

    $contact = User::where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->first();

    if ($contact) {
        if ($contact->status == 1) {
            $token = $contact->createToken('Chat')->plainTextToken;

            return $this->sendResponse([
                'user' => $contact,
                'token' => $token,
            ], 'OTP Verified Successfully');
        } elseif ($contact->status == 5) {
            return $this->sendError('Your account is not active. Please contact support.'); 
        }
    }

    return $this->sendError('Invalid OTP or phone not found. Please check and try again.');
}

}
