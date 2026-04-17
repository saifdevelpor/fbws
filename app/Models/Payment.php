<?php

namespace App\Models;

use App\Support\Phone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changer_id');
    }

    // ✅ WhatsApp Link (per payment row)
    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->relationLoaded('user')) {
            $this->load('user');
        }

        if (!$this->user || empty($this->user->phone_number)) {
            return null;
        }

        $phone = \App\Support\Phone::toWhatsapp($this->user->phone_number);

        $name   = $this->user->name ?? 'Member';
        $amount = number_format((float)$this->amount, 0);
        $month  = $this->month;
        $date   = $this->date ? date('d M Y', strtotime($this->date)) : date('d M Y');

        $msg = "السلام علیکم معزز رکن سوسائٹی {$name} 🌹\n\n"
            . "آپ کو مطلع کیا جاتا ہے کہ ماہ {$month} کا فنڈ "
            . "({$amount} روپے) فروکہ برادرز ویلفیئر سوسائٹی کے اکاؤنٹ میں کامیابی سے جمع ہو چکا ہے۔\n\n"
            . "اللہ پاک آپ کے رزق میں مزید برکتیں اور وسعتیں عطا فرمائے۔\n"
            . "فروکہ برادرز ویلفیئر سوسائٹی کے ساتھ تعاون کرنے کا بہت بہت شکریہ، "
            . "اللہ پاک آپ کو اجر عظیم عطا فرمائے۔\n"
            . "آمین ثم آمین یا رب العالمین 🤲\n\n"
            . "منجانب:\n"
            . "*محمد اسامہ ارشد فروکہ*\n"
            . "فنانس سیکرٹری\n"
            . "*فروکہ برادرز ویلفیئر سوسائٹی*";

        return "https://wa.me/{$phone}?text=" . rawurlencode($msg);
    }
}
