<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\QrCodeService;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'user_id', 
        'card_title',
        'color_theme',
        'preferred_name',
        'pronoun',
        'image',
        'cover_photo', 
        'qr_code_url',
    ];

    protected $appends = ['qr_code_url'];

    private $qrCodeService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->qrCodeService = new QrCodeService();
    }

    public function cardDetails()
    {
        return $this->hasMany(CardDetails::class, 'cards_id');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getQrCodeUrlAttribute()
    {
        if (!$this->attributes['qr_code_url']) {
            $this->attributes['qr_code_url'] = $this->qrCodeService->generateQrCode($this);
            $this->save();
        }
        return $this->attributes['qr_code_url'];
    }
}
