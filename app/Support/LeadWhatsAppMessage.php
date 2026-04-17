<?php

namespace App\Support;

use App\Models\Lead;

final class LeadWhatsAppMessage
{
    private static function footer(): string
    {
        return "\n\n"
            . "----------------\n"
            . '*Farooka Brothers Welfare Society*' . "\n"
            . '*(FBWS)*';
    }

    /** Quick message from leads list (eye row WhatsApp button). */
    public static function listOutreach(Lead $lead): string
    {
        $name = $lead->name;
        $id = $lead->id;

        $body = <<<MSG
*FBWS* - Application update

Assalam o Alaikum *{$name}*

We are reaching out regarding your *Become a Member* application.

*Reference*
Lead ID: *#{$id}*

We will keep you updated on the next steps.
MSG;

        return $body . self::footer();
    }

    /** After admin saves status / note from lead modal. */
    public static function statusUpdate(Lead $lead, string $status, string $adminNote): string
    {
        $name = $lead->name;
        $id = $lead->id;
        $statusLabel = ucfirst($status);
        $noteLine = trim($adminNote) !== '' ? trim($adminNote) : '-';

        $body = <<<MSG
*FBWS* - Application updated

Assalam o Alaikum *{$name}*

Your application record has been updated in our system.

*Details*
Lead ID: *#{$id}*
Status: *{$statusLabel}*

*Message from team*
{$noteLine}

Thank you for your patience.
MSG;

        return $body . self::footer();
    }
}
