<?php

namespace App\Services\Solar;

use App\Models\ServiceSlot;

class VerificationCodeGenerator
{
    private const CHARSET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    public function unique(): string
    {
        do {
            $code = '';
            $len = strlen(self::CHARSET);
            for ($i = 0; $i < 6; $i++) {
                $code .= self::CHARSET[random_int(0, $len - 1)];
            }
        } while (ServiceSlot::where('verification_code', $code)->exists());

        return $code;
    }
}
