<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class QrCodeGenerator
{
    public static function generateQrCode()
    {
        return 'SMARTDOORZ-' . strtoupper(Str::random(12)) . '-' . time();
    }

    public static function getQrImageUrl($qrCode)
    {
        // Menggunakan QR Code API dari qr-server.com
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCode);
    }
}
