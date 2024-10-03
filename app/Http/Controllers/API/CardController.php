<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use App\Models\Card;
use App\Models\CardDetails;
use App\Models\UserCards;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Services\QrCodeService;



class CardController extends BaseController

{
    

    public function store(Request $request, QrCodeService $qrCodeService)
{
    $validator = Validator::make($request->all(), [
        'card_title' => 'nullable|string|max:255',
        'color_theme' => 'nullable|string|max:50',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'preferred_name' => 'nullable|string|max:255',
        'pronoun' => 'nullable|string|max:50',
        'details' => 'nullable|array',
        'details.*.title' => 'nullable|in:email,phone,website,social-link',
        'details.*.type' => 'nullable|in:contact,social',
        'details.*.value' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return $this->sendValidateError($validator->errors());
    }

    $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
    $coverPhotoPath = $request->hasFile('cover_photo') ? $request->file('cover_photo')->store('images', 'public') : null;

    $card = Card::create([
        'user_id' => Auth::id(),
        'card_title' => $request->card_title ?? 'Default Card Title',
        'color_theme' => $request->color_theme ?? '#ffffff',
        'image' => $imagePath ?? 'images/default-image.jpg',
        'cover_photo' => $coverPhotoPath ?? 'images/black_cover.jpeg',
        'preferred_name' => $request->preferred_name,
        'pronoun' => $request->pronoun,
    ]);

    $qrCodeUrl = $qrCodeService->generateQrCode($card);
    $card->qr_code_url = $qrCodeUrl;
    $card->save();

    if (!empty($request->details)) {
        foreach ($request->details as $detail) {
            $detailValidator = Validator::make($detail, [
                'title' => 'nullable|in:email,phone,website,social-link',
                'type' => 'nullable|in:contact,social',
                'value' => 'nullable|string|max:255',
            ]);

            if ($detail['title'] === 'email' && $detail['type'] !== 'contact') {
                return $this->sendError('The title "email" must have type "contact".');
            }

            if ($detail['title'] === 'phone' && $detail['type'] !== 'contact') {
                return $this->sendError('The title "phone" must have type "contact".');
            }

            if ($detail['title'] === 'website' && $detail['type'] !== 'social') {
                return $this->sendError('The title "website" must have type "social".');
            }

            if ($detail['title'] === 'social-link' && $detail['type'] !== 'social') {
                return $this->sendError('The title "social-link" must have type "social".');
            }

            if ($detail['title'] === 'email') {
                $detailValidator->after(function ($validator) use ($detail) {
                    if (!filter_var($detail['value'], FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add('value', 'The value must be a valid email address.');
                    }
                });
            }

            if ($detail['title'] === 'phone') {
                $detailValidator->after(function ($validator) use ($detail) {
                    if (!preg_match('/^\+?[0-9]{10,13}$/', $detail['value'])) {
                        $validator->errors()->add('value', 'The value must be a valid phone number.');
                    }
                });
            }

            if (in_array($detail['title'], ['website', 'social-link'])) {
                $detailValidator->after(function ($validator) use ($detail) {
                    if ($detail['title'] === 'social-link') {
                        $allowedDomains = ['facebook.com', 'instagram.com', 'linkedin.com', 'twitter.com'];
                        $url = (strpos($detail['value'], 'http://') === false && strpos($detail['value'], 'https://') === false)
                            ? 'http://' . $detail['value']
                            : $detail['value'];

                        $domain = parse_url($url, PHP_URL_HOST);
                        $domainWithoutWww = preg_replace('/^www\./', '', $domain);

                        if (!in_array($domainWithoutWww, $allowedDomains)) {
                            $validator->errors()->add('value', 'The social-links must be valid (Facebook, Instagram, LinkedIn, Twitter).');
                        }
                    } else {
                        $validator->addRules(['value' => 'regex:/^(http[s]?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/\S*)?$/']);
                    }
                });
            }

            if ($detailValidator->fails()) {
                return $this->sendValidateError($detailValidator->errors());
            }

            $existingDetail = CardDetails::where('user_id', Auth::id())
                ->where('cards_id', $card->id)
                ->where('value', $detail['value'])
                ->first();

            if ($existingDetail) {
                return response()->json(['error' => 'This entry already exists for this user.'], 409);
            }

            CardDetails::create([
                'user_id' => Auth::id(),
                'cards_id' => $card->id,
                'title' => $detail['title'],
                'type' => $detail['type'],
                'value' => $detail['value'],
            ]);
        }
    }

    return $this->sendResponse($card, 'Card and details created successfully');
}


public function update(Request $request)
{
    $card = Card::find($request->id);

    if (!$card || $card->user_id !== Auth::id()) {
        return $this->sendError('Card not found or unauthorized.');
    }

    if ($request->hasFile('image')) {
        $card->image = $request->file('image')->store('images', 'public');
    }

    if ($request->hasFile('cover_photo')) {
        $card->cover_photo = $request->file('cover_photo')->store('images', 'public');
    }

    $card->card_title = $request->card_title ?? $card->card_title;
    $card->color_theme = $request->color_theme ?? $card->color_theme;
    $card->preferred_name = $request->preferred_name ?? $card->preferred_name; 
    $card->pronoun = $request->pronoun ?? $card->pronoun; 
    $card->save();

    if (!empty($request->details)) {
        foreach ($request->details as $detail) {
            $detailValidator = Validator::make($detail, [
                'title' => 'required|in:email,phone,website,social-link',
                'type' => 'required|in:contact,social',
                'value' => 'required|string|max:255',
            ])->after(function ($validator) use ($detail) {
                if ($detail['title'] === 'social-link') {
                    if (!preg_match('/^(https?:\/\/)?(www\.)?(linkedin\.com|instagram\.com|facebook\.com|twitter\.com)(\/.*)?$/', $detail['value'])) {
                        $validator->errors()->add('value', 'The social-link must be a valid URL for LinkedIn, Instagram, Facebook, or Twitter.');
                    }
                }
            });

            if ($detailValidator->fails()) {
                return $this->sendValidateError($detailValidator->errors());
            }

            $exists = CardDetails::where('user_id', Auth::id())
                ->where('cards_id', $card->id)
                ->where('title', $detail['title'])
                ->where('value', $detail['value'])
                ->exists();

            if (!$exists) {
                CardDetails::create([
                    'user_id' => Auth::id(),
                    'cards_id' => $card->id,
                    'title' => $detail['title'],
                    'type' => $detail['type'],
                    'value' => $detail['value'],
                ]);
            } else {
                return $this->sendError('Detail with this title and value already exists.');
            }
        }
    }

    return $this->sendResponse($card, 'Card and details updated successfully');
}


public function usercardupdate(Request $request)
{
    $card = Card::find($request->id);

    if (!$card || $card->user_id !== Auth::id()) {
        return $this->sendError('Card not found or unauthorized.');
    }

    if ($request->hasFile('image')) {
        $card->image = $request->file('image')->store('images', 'public');
    }

    if ($request->hasFile('cover_photo')) {
        $card->cover_photo = $request->file('cover_photo')->store('images', 'public');
    }

    $card->card_title = $request->card_title ?? $card->card_title;
    $card->color_theme = $request->color_theme ?? $card->color_theme;
    $card->preferred_name = $request->preferred_name ?? $card->preferred_name; 
    $card->pronoun = $request->pronoun ?? $card->pronoun; 
    $card->save();

    $user = Auth::user();
    
    if ($request->has('name')) {
        $user->name = $request->name;
    }
    if ($request->has('job_title')) {
        $user->job_title = $request->job_title;
    }
    if ($request->has('company_name')) {
        $user->company_name = $request->company_name;
    }
    if ($request->has('phone')) {
        $user->phone = $request->phone;
    }
    $user->save();

    if (!empty($request->details)) {
        foreach ($request->details as $detail) {
            $detailValidator = Validator::make($detail, [
                'id' => 'required|integer|exists:card_details,id', 
                'title' => 'required|in:email,phone,website,social-link',
                'type' => 'required|in:contact,social',
                'value' => 'required|string|max:255',
            ])->after(function ($validator) use ($detail) {
                if ($detail['title'] === 'social-link') {
                    if (!preg_match('/^(https?:\/\/)?(www\.)?(linkedin\.com|instagram\.com|facebook\.com|twitter\.com)(\/.*)?$/', $detail['value'])) {
                        $validator->errors()->add('value', 'The social-link must be a valid URL for LinkedIn, Instagram, Facebook, or Twitter.');
                    }
                }
            });

            if ($detail['title'] === 'email' && $detail['type'] !== 'contact') {
                return $this->sendError('The title "email" must have type "contact".');
            }

            if ($detail['title'] === 'phone' && $detail['type'] !== 'contact') {
                return $this->sendError('The title "phone" must have type "contact".');
            }

            if ($detail['title'] === 'website' && $detail['type'] !== 'social') {
                return $this->sendError('The title "website" must have type "social".');
            }

            if ($detail['title'] === 'social-link' && $detail['type'] !== 'social') {
                return $this->sendError('The title "social-link" must have type "social".');
            }

            if ($detail['title'] === 'email') {
                $detailValidator->after(function ($validator) use ($detail) {
                    if (!filter_var($detail['value'], FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add('value', 'The value must be a valid email address.');
                    }
                });
            }

            if ($detail['title'] === 'phone') {
                $detailValidator->after(function ($validator) use ($detail) {
                    if (!preg_match('/^\+?[0-9]{10,13}$/', $detail['value'])) {
                        $validator->errors()->add('value', 'The value must be a valid phone number.');
                    }
                });
            }

            if ($detailValidator->fails()) {
                return $this->sendValidateError($detailValidator->errors());
            }

            $cardDetail = CardDetails::find($detail['id']);

            if ($cardDetail && $cardDetail->cards_id === $card->id) {
                $cardDetail->type = $detail['type'];
                $cardDetail->value = $detail['value'];
                $cardDetail->save();
            }
        }
    }

   $card->cardDetails;

    $responseData = [
        'user' => $user,
        'card' => $card,
    ];

    return $this->sendResponse($responseData, 'Card, user, and details updated successfully');
}





public function getWebsites(): JsonResponse
    {
        $values = CardDetails::where('value', 'regexp', '^(https?://)?([a-z0-9-]+\\.)+[a-z]{2,}(/.*)?$')
            ->pluck('value'); 

        return $this->sendResponse($values, 'Data Retrieved');
    }


    public function getCards() {
        $users = User::with(['cards' => function($query) {
            $query->with(['user' => function($q) {
                $q->select('id', 'name', 'job_title','image'); 
            }]);
        }, 'contacts'])->get();

        if (!auth()->user()->hasPermissionTo('all-users-cards')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    
    
        return $this->sendResponse($users, 'Cards Retrieved');
    }
    
    
    
}



    
    

