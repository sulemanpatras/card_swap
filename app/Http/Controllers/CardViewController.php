<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardDetails;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Crypt;
use Exception;

class CardViewController extends Controller
{
    public function show($encryptedId)
    {
        try {
            // Decrypt the ID to get the card ID
            $card_id = Crypt::decrypt($encryptedId);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid ID.'], 400);
        }

        // Fetch the card with associated user and card details
        $card = Card::with(['user', 'cardDetails'])->find($card_id);

        if (!$card) {
            return response()->json(['error' => 'Card not found.'], 404);
        }

        $qrCodeService = new QrCodeService();

        // Generate the QR code
        $qrCodeImage = $qrCodeService->generateQrCode($card);

        // Initialize an array to hold social links
        $socialLinks = [];

        // Map card details and handle social links
        $cardDetails = $card->cardDetails->map(function (CardDetails $detail) use (&$socialLinks) {
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
                    } elseif ($detail->title === 'social-link') {
                        // Extract the host from the URL
                        $host = parse_url($displayValue, PHP_URL_HOST);
                        if ($host) {
                            // Set the contact key based on the social link
                            if (strpos($displayValue, 'facebook.com') !== false) {
                                $contactKey = 'facebook';
                                $socialLinks['facebook'] = $displayValue; // Store Facebook link
                            } elseif (strpos($displayValue, 'instagram.com') !== false) {
                                $contactKey = 'instagram';
                                $socialLinks['instagram'] = $displayValue; // Store Instagram link
                            } elseif (strpos($displayValue, 'linkedin.com') !== false) {
                                $contactKey = 'linkedin';
                                $socialLinks['linkedin'] = $displayValue; // Store LinkedIn link
                            } elseif (strpos($displayValue, 'twitter.com') !== false) {
                                $contactKey = 'twitter';
                                $socialLinks['twitter'] = $displayValue; // Store Twitter link
                            } else {
                                // Default key for any other social link
                                $contactKey = str_replace(['www.', '.com', '.org', '.net'], '', $host);
                            }
                        }
                    }
                    break;

                default:
                    $contactKey = 'other';
                    break;
            }

            return [
                'title' => $detail->title,
                'type' => $detail->type,
                'key' => $contactKey,  // Added key to be returned in the array
                'value' => $displayValue,
            ];
        });

        // Prepare data for the view
        $cardData = [
            'id' => $card->id,
            'name' => $card->user->name ?? "",
            'image' => $card->user->image ? url('storage/' . $card->user->image) : "",
            'job_title' => $card->user->job_title ?? "",
            'company_name' => $card->user->company_name ?? "",
            'phone' => $card->user->phone ?? "",
            'pronoun' => $card->pronoun ?? "",
            'preferred_name' => $card->preferred_name ?? "",
            'card_title' => $card->card_title ?? "",
            'card_image' => $card->image ? url('storage/' . $card->image) : "",
            'cover_photo' => $card->cover_photo ? url('storage/' . $card->cover_photo) : "",
            'qr_code_image' => $qrCodeImage,
            'qrcode_url' => $qrCodeService->generateQrCodeData($card),
            'card_details' => $cardDetails,
            'social_links' => $socialLinks, // Add social links to card data
        ];

        // Return the view with the card data
        return view('cardview.show', ['cardData' => $cardData]);
    }
}