<?php
namespace App\Services;

use App\Models\Card;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class QrCodeService
{
    public function generateQrCode(Card $card)
    {
        $filename = 'qr_codes/' . md5($card->id . uniqid()) . '.svg';
        
        $qrCode = QrCode::format('svg')->size(200)->generate($this->generateQrCodeData($card));
        Storage::disk('public')->put($filename, $qrCode);

        return url('storage/' . $filename);
    }

    public function generateQrCodeData(Card $card)
    {
        $encryptedId = Crypt::encrypt($card->id);
        $contactUrl = route('cardview.show', ['encryptedId' => $encryptedId]);
    
        return $contactUrl;
    }
    
}
