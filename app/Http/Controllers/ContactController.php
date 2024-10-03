<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function downloadContact()
    {
        $vcard = "BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nTEL:+123456789\nEMAIL:john.doe@example.com\nEND:VCARD";
        
        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
    }
}
