<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use App\Models\CardDetails;
use Illuminate\Support\Facades\Crypt;
use App\Services\QrCodeService;

class DashboardController extends BaseController
{
    public function get()
    {
    try {
        $user = Auth::user();
        $contacts = $user->contacts()->with('card')->get();

        $qrCodeService = new QrCodeService(); 

        $contactsWithUserData = $contacts->map(function (CardDetails $contact) use ($user, $qrCodeService) {
            $card = $contact->card;
            $displayValue = '';
            $contactKey = '';

            switch ($contact->type) {
                case 'contact':
                    if ($contact->title === 'email') {
                        $displayValue = $contact->value;
                        $contactKey = 'email';
                    } elseif ($contact->title === 'phone') {
                        $displayValue = $contact->value;
                        $contactKey = 'phone';
                    }
                    break;
                case 'social':
                    if ($contact->title === 'website') {
                        $displayValue = $contact->value;
                        $contactKey = 'website';
                    } elseif ($contact->title === 'social-link') {
                        $displayValue = $contact->value;
                        $host = parse_url($displayValue, PHP_URL_HOST);
                        if ($host) {
                            $contactKey = str_replace(['www.', '.com', '.org', '.net'], '', $host);
                        }
                    }
                    break;
                default:
                    $contactKey = 'other';
                    break;
            }

            $qrcodeUrl = route('cardview.show', ['encryptedId' => Crypt::encrypt($contact->cards_id)]);
            $qrCodeImage = $qrCodeService->generateQrCodeFromUrl($qrcodeUrl, $contact); 

            return [
                'id' => $contact->id,
                'name' => $user->name ?? "",
                'image' => $user->image ? url('storage/' . $user->image) : "",
                'job_title' => $user->job_title ?? "",
                'company_name' => $user->company_name ?? "",
                'phone' => $user->phone ?? "",
                'pronoun' => $card->pronoun ?? "",
                'preferred_name' => $card->preferred_name ?? "",
                'user_id' => $contact->user_id,
                'card_title' => $card ? $card->card_title : "",
                'card_image' => $card && $card->image ? url('storage/' . $card->image) : "",
                'cover_photo' => $card && $card->cover_photo ? url('storage/' . $card->cover_photo) : "",
                $contactKey => $displayValue,
                'qr_code_image' => $qrCodeImage,
                'qrcode_url' => $qrcodeUrl,
            ];
        });

        if ($contactsWithUserData->isEmpty()) {
            $contactsWithUserData = [
                [
                    'id' => null,
                    'name' => $user->name ?? "",
                    'image' => $user->image ? url('storage/' . $user->image) : "",
                    'job_title' => $user->job_title ?? "",
                    'company_name' => $user->company_name ?? "",
                    'phone' => $user->phone ?? "",
                    'pronoun' => "",
                    'preferred_name' => "",
                    'user_id' => null,
                    'card_title' => "",
                    'card_image' => "",
                    'cover_photo' => "",
                    'email' => "",
                    'phone' => "",
                    'website' => "",
                    'qr_code_image' => "",
                    'qrcode_url' => "",
                ]
            ];
            return $this->sendResponse($contactsWithUserData, 'Card details are not available');
        }

        return $this->sendResponse($contactsWithUserData, 'Data retrieved successfully.');
    } catch (\Exception $e) {
        return $this->sendError('Something went wrong.', $e->getMessage());
    }
}


    
public function getdash()
{
    try {
        if (!Auth::check()) {
            return $this->sendError('Unauthorized access.');
        }

        $qrCodeService = new QrCodeService();
        
        $cards = Card::with(['user', 'cardDetails'])
            ->where('user_id', Auth::id())
            ->get(); 

        $cardsWithData = $cards->map(function (Card $card) use ($qrCodeService) {
            $user = $card->user;

            $qrCodeImage = $qrCodeService->generateQrCode($card);

            $cardDetails = $card->cardDetails->map(function (CardDetails $detail) {
                $displayValue = $detail->value;
                $contactKey = '';

                switch ($detail->type) {
                    case 'contact':
                        $contactKey = $detail->title === 'email' ? 'email' : ($detail->title === 'phone' ? 'phone' : '');
                        break;

                    case 'social':
                        if ($detail->title === 'website') {
                            $contactKey = 'website';
                        } elseif (strpos($displayValue, 'facebook.com') !== false) {
                            $contactKey = 'facebook';
                        } elseif (strpos($displayValue, 'instagram.com') !== false) {
                            $contactKey = 'instagram';
                        } elseif (strpos($displayValue, 'linkedin.com') !== false) {
                            $contactKey = 'linkedin';
                        } elseif (strpos($displayValue, 'twitter.com') !== false) {
                            $contactKey = 'twitter';
                        }
                        break;

                    default:
                        $contactKey = 'other';
                        break;
                }

                return [
                    'title' => $detail->title,
                    'type' => $detail->type,
                    'key' => $contactKey,  
                    'value' => $displayValue, 
                ];
            })->toArray();

            $cardDetails = !empty($cardDetails) ? $cardDetails : [
                [
                    'title' => "",
                    'type' => "",
                    'key' => "",
                    'value' => "",
                ],
            ];

            return [
                'id' => $card->id,
                'name' => $user->name ?? "",
                'image' => $user->image ? url('storage/' . $user->image) : "",
                'job_title' => $user->job_title ?? "",
                'company_name' => $user->company_name ?? "",
                'phone' => $user->phone ?? "",
                'pronoun' => $card->pronoun ?? "",
                'preferred_name' => $card->preferred_name ?? "",
                'card_title' => $card->card_title ?? "",
                'card_image' => $card->image ? url('storage/' . $card->image) : "",
                'cover_photo' => $card->cover_photo ? url('storage/' . $card->cover_photo) : "",
                'qr_code_image' => $qrCodeImage,
                'qrcode_url' => $qrCodeService->generateQrCodeData($card), 
                'card_details' => $cardDetails, 
            ];
        });

        if ($cards->isEmpty()) {
            $user = Auth::user();
            $defaultCardData = [
                'id' => "",
                'name' => $user->name ?? "",
                'image' => $user->image ? url('storage/' . $user->image) : "",
                'job_title' => $user->job_title ?? "",
                'company_name' => $user->company_name ?? "",
                'phone' => $user->phone ?? "",
                'pronoun' => "",
                'preferred_name' => "",
                'card_title' => "",
                'card_image' => "",
                'cover_photo' => "",
                'qr_code_image' => "",
                'qrcode_url' => "",
                'card_details' => [
                    [
                        'title' => "",
                        'type' => "",
                        'key' => "",
                        'value' => "",
                    ],
                ],
            ];
            
            return $this->sendResponse([$defaultCardData], 'No card data available');
        }

        return $this->sendResponse($cardsWithData, 'Data retrieved successfully.');
    } catch (\Exception $e) {
        return $this->sendError('Something went wrong.', $e->getMessage());
    }
}


public function getDashById($id)
{
    try {
        if (!Auth::check()) {
            return $this->sendError('Unauthorized access.');
        }

        $qrCodeService = new QrCodeService();

        $card = Card::with(['user', 'cardDetails'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$card) {
            return $this->sendError('Card not found.');
        }

        $user = $card->user;
        $qrCodeImage = $qrCodeService->generateQrCode($card);

        $cardDetails = $card->cardDetails->map(function (CardDetails $detail) {
            $displayValue = $detail->value;
            $contactKey = '';

            switch ($detail->type) {
                case 'contact':
                    if ($detail->title === 'email') {
                        $contactKey = 'email';
                    } elseif ($detail->title === 'phone') {
                        $contactKey = 'phone';
                    }
                    break;

                case 'social':
                    if ($detail->title === 'website') {
                        $contactKey = 'website';
                    } elseif (strpos($displayValue, 'facebook.com') !== false) {
                        $contactKey = 'facebook';
                    } elseif (strpos($displayValue, 'instagram.com') !== false) {
                        $contactKey = 'instagram';
                    } elseif (strpos($displayValue, 'linkedin.com') !== false) {
                        $contactKey = 'linkedin';
                    } elseif (strpos($displayValue, 'twitter.com') !== false) {
                        $contactKey = 'twitter';
                    }
                    break;

                default:
                    $contactKey = 'other';
                    break;
            }

            return [
                'title' => $detail->title,
                'type' => $detail->type,
                'key' => $contactKey,
                'value' => $displayValue,
            ];
        });

        return $this->sendResponse([
            'id' => $card->id,
            'name' => $user->name ?? "",
            'image' => $user->image ? url('storage/' . $user->image) : "",
            'job_title' => $user->job_title ?? "",
            'company_name' => $user->company_name ?? "",
            'phone' => $user->phone ?? "",
            'pronoun' => $card->pronoun ?? "",
            'preferred_name' => $card->preferred_name ?? "",
            'card_title' => $card->card_title ?? "",
            'card_image' => $card->image ? url('storage/' . $card->image) : "",
            'cover_photo' => $card->cover_photo ? url('storage/' . $card->cover_photo) : "",
            'qr_code_image' => $qrCodeImage,
            'qrcode_url' => $qrCodeService->generateQrCodeData($card),
            'card_details' => $cardDetails,
        ], 'Data retrieved successfully.');
        
    } catch (\Exception $e) {
        return $this->sendError('Something went wrong.', $e->getMessage());
    }
}





    public function getDashboardStats()
    {
        try {
            if (!auth()->user()->hasPermissionTo('get-dashboard-stats')) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $totalCards = Card::count();
            $activeCardCount = Card::where('status', 1)->count();
            $inactiveCardCount = Card::where('status', 5)->count();
            $activeuserCount = User::where('status', 1)->count();
            $inactiveuserCount = User::where('status', 5)->count();
            $userCount = User::count();
            return $this->sendResponse([
                'totalCards' => $totalCards,
                'activeCardCount' => $activeCardCount,
                'inactiveCardCount' => $inactiveCardCount,
                'userCount' => $userCount,
                'activeuserCount' => $activeuserCount,
                'inactiveuserCount' => $inactiveuserCount,

            ], 'Dashboard Stats Retrieved');
           
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong..', $e->getMessage());
        }
    }
    
}
