<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardViewController;
use App\Models\User;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class,'index']);

Route::get('/qrcode/{encryptedId}', [CardViewController::class, 'show'])->name('cardview.show');

Route::get('/download-contact/{card}', function (\App\Models\Card $card) {
    $user = $card->user;
    $cardDetails = $card->cardDetails()->get();

    $emails = [];
    $phones = [];
    $urls = [];

    foreach ($cardDetails as $detail) {
        if ($detail->title === 'email') {
            $emails[] = $detail->value;
        } elseif ($detail->title === 'phone') {
            $phones[] = $detail->value;
        } elseif ($detail->title === 'website') {
            $urls[] = $detail->value; 
        }
    }

    $vCardContent = "BEGIN:VCARD\n";
    $vCardContent .= "VERSION:3.0\n";
    $vCardContent .= "FN:{$user->name}\n";

    foreach ($emails as $email) {
        $vCardContent .= "EMAIL:{$email}\n";
    }

    foreach ($phones as $phone) {
        $vCardContent .= "TEL:{$phone}\n";
    }

    foreach ($urls as $url) {
        $vCardContent .= "URL:{$url}\n";
    }

    $vCardContent .= "END:VCARD\n"; 

    return response($vCardContent)
        ->header('Content-Type', 'text/vcard')
        ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
})->name('download.contact');
