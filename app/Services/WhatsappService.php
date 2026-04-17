<?php

namespace App\Services;

use App\Models\Payment;
use App\Support\Phone;

class WhatsappService
{
    public function paymentMessage(Payment $payment): string
    {
        $user = $payment->user;

        $phone = Phone::toWhatsapp($user->phone);

        $msg = "Salam {$user->name}, "
            . "Aapki payment Rs. {$payment->amount} successfully receive ho chuki hai. "
            . "Shukriya - Society";

        return "https://wa.me/{$phone}?text=" . urlencode($msg);
    }
}
