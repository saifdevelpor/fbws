<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\Item;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\Damage;
use App\Models\DeliveryOrder;
use App\Models\SupportRequest;
use App\Models\User;
use App\Models\WelfareMonth;
use App\Models\WelfareTransaction;
use App\Support\Phone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class WebSiteController extends Controller
{
    private function websiteMembersQuery()
    {
        return User::query()->where('position', '!=', 'Legal Advisor');
    }

    private function publicReportPayload(int $month, int $year): array
    {
        $payments = Payment::query()->whereYear('date', $year)->whereMonth('date', $month);
        $orders = Order::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $deliveries = DeliveryOrder::query()->whereYear('delivery_date', $year)->whereMonth('delivery_date', $month);
        $complaints = Complaint::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $damages = Damage::query()->where(function ($q) use ($month, $year) {
            $q->where(function ($sub) use ($month, $year) {
                $sub->whereNotNull('damage_date')->whereYear('damage_date', $year)->whereMonth('damage_date', $month);
            })->orWhere(function ($sub) use ($month, $year) {
                $sub->whereNull('damage_date')->whereYear('created_at', $year)->whereMonth('created_at', $month);
            });
        });
        $gallery = GalleryImage::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $events = Event::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $leads = Lead::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $support = SupportRequest::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);

        $welfareMonth = WelfareMonth::query()->where('month', $month)->where('year', $year)->first();
        $welfareIncome = 0.0;
        $welfareExpense = 0.0;

        if ($welfareMonth) {
            $welfareIncome = (float) WelfareTransaction::query()
                ->where('welfare_month_id', $welfareMonth->id)
                ->where('type', 'income')
                ->sum('amount');

            $welfareExpense = (float) WelfareTransaction::query()
                ->where('welfare_month_id', $welfareMonth->id)
                ->where('type', 'expense')
                ->sum('amount');
        }

        $trendLabels = [];
        $paymentsTrend = [];
        $ordersTrend = [];
        $supportTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::createFromDate($year, $month, 1)->subMonths($i);
            $trendLabels[] = $date->format('M Y');
            $paymentsTrend[] = (float) Payment::query()->whereYear('date', $date->year)->whereMonth('date', $date->month)->sum('amount');
            $ordersTrend[] = (int) Order::query()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $supportTrend[] = (int) SupportRequest::query()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
        }

        $totalMembers = $this->websiteMembersQuery()->count();
        $perUserMonthly = 500;
        $expectedMonthly = $totalMembers * $perUserMonthly;
        $collectedMonthly = (float) (clone $payments)->sum('amount');
        $progress = $expectedMonthly > 0 ? min(100, (int) round(($collectedMonthly / $expectedMonthly) * 100)) : 0;

        $supportHighlights = SupportRequest::query()
            ->with('user:id,name')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->limit(6)
            ->get(['id', 'user_id', 'title', 'priority', 'status', 'created_at']);

        return [
            'summary' => [
                'total_members' => (int) $totalMembers,
                'expected_monthly' => (float) $expectedMonthly,
                'collected_monthly' => $collectedMonthly,
                'progress' => $progress,
                'welfare_income' => $welfareIncome,
                'welfare_expense' => $welfareExpense,
                'welfare_net' => $welfareIncome - $welfareExpense,
                'orders_count' => (int) (clone $orders)->count(),
                'deliveries_count' => (int) (clone $deliveries)->count(),
                'complaints_count' => (int) (clone $complaints)->count(),
                'damages_count' => (int) (clone $damages)->count(),
                'gallery_count' => (int) (clone $gallery)->count(),
                'events_count' => (int) (clone $events)->count(),
                'leads_count' => (int) (clone $leads)->count(),
                'support_count' => (int) (clone $support)->count(),
            ],
            'trend' => [
                'labels' => $trendLabels,
                'payments' => $paymentsTrend,
                'orders' => $ordersTrend,
                'support' => $supportTrend,
            ],
            'highlights' => [
                'payments' => Payment::query()->with('user:id,name')->whereYear('date', $year)->whereMonth('date', $month)->latest('date')->limit(6)->get(['id', 'user_id', 'amount', 'month', 'date']),
                'orders' => Order::query()->with('user:id,name')->whereYear('created_at', $year)->whereMonth('created_at', $month)->latest()->limit(6)->get(['id', 'user_id', 'status', 'created_at']),
                'support' => $supportHighlights,
            ],
        ];
    }

    public function index()
    {
        $leaders = User::select('id', 'name', 'profile_photo', 'position')
            ->whereIn('position', ['President', 'Gernal Secretary', 'Finance Secretary', 'Legal Advisor'])
            ->orderByRaw("FIELD(position, 'President', 'Gernal Secretary', 'Finance Secretary', 'Legal Advisor')")
            ->get();
        $latestItems = Item::latest()->take(3)->get();
        $latestUsers = $this->websiteMembersQuery()
            ->where('position', 'Member')
            ->latest()
            ->take(3)
            ->get();
        $latestEvents = Event::with('media')->latest()->take(3)->get();
        $perUserMonthly = 500;
        $totalUsers = $this->websiteMembersQuery()->count();
        $expectedMonthly = $totalUsers * $perUserMonthly;
        $monthlyCollected = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        // ✅ Progress %
        $progress = $expectedMonthly > 0 ? round(($monthlyCollected / $expectedMonthly) * 100) : 0;
        if ($progress > 100) $progress = 100;
        $latestGallery = GalleryImage::with('user:id,name,profile_photo')->latest()->take(6)->get();
        return view('website.index', compact(
            'latestUsers',
            'leaders',
            'latestEvents',
            'latestItems',
            'totalUsers',
            'perUserMonthly',
            'expectedMonthly',
            'monthlyCollected',
            'progress',
            'latestGallery'
        ));
    }

    public function about()
    {
        $perUserMonthly = 500;
        $totalUsers = $this->websiteMembersQuery()->count();
        $expectedMonthly = $totalUsers * $perUserMonthly;
        $monthlyCollected = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        // ✅ Progress %
        $progress = $expectedMonthly > 0 ? round(($monthlyCollected / $expectedMonthly) * 100) : 0;
        if ($progress > 100) $progress = 100;
        return view('website.about', compact(
            'totalUsers',
            'perUserMonthly',
            'expectedMonthly',
            'monthlyCollected',
            'progress'
        ));
    }

    public function item()
    {

        $latestItems = Item::latest()->paginate(9);
        return view('website.item', compact('latestItems'));
    }

    public function role()
    {
        return view('website.condition');
    }

    public function team()
    {
        $users = $this->websiteMembersQuery()
            ->latest()
            ->paginate(9);

        return view('website.team', compact('users'));
    }


    public function contact()
    {
        return view('website.contacts');
    }


    public function social()
    {
        $events = Event::with('media')->latest()->paginate(6);
        return view('website.event', compact('events'));
    }

    public function account(Request $request)
    {
        $user = $request->user(); // logged-in user
        return view('website.profile', compact('user'));
    }

    public function donate()
    {
        // Get current English month
        $englishMonth = Carbon::now()->format('F');

        // English to Urdu month mapping
        $months = [
            'January'   => 'جنوری',
            'February'  => 'فروری',
            'March'     => 'مارچ',
            'April'     => 'اپریل',
            'May'       => 'مئی',
            'June'      => 'جون',
            'July'      => 'جولائی',
            'August'    => 'اگست',
            'September' => 'ستمبر',
            'October'   => 'اکتوبر',
            'November'  => 'نومبر',
            'December'  => 'دسمبر',
        ];

        // Convert to Urdu month
        $currentMonth = $months[$englishMonth];

        // Fetch payments
        $payments = Payment::whereNotNull('amount')
            ->where('month', $currentMonth)
            ->whereHas('user', function ($query) {
                $query->where('position', '!=', 'Legal Advisor');
            })
            ->with('user')
            ->paginate(9);

        return view('website.donute', compact('payments'));
    }
    public function paymentPage()
    {
        $memberCount = $this->websiteMembersQuery()->count();
        $currentMonthCollection = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $paymentChannels = [
            [
                'name' => 'JazzCash',
                'type' => 'Mobile Wallet',
                'account' => '03012704423',
                'holder' => 'Muhammad Usama Arshad',
                'logo' => 'website/images/jazzcash.png',
            ],
            [
                'name' => 'EasyPaisa',
                'type' => 'Mobile Wallet',
                'account' => '03012704423',
                'holder' => 'Muhammad Usama Arshad',
                'logo' => 'website/images/easypaisa.png',
            ],
            [
                'name' => 'BOP Bank',
                'type' => 'Bank Transfer',
                'account' => '6300342619100011',
                'holder' => 'Muhammad Usama Arshad',
                'logo' => 'website/images/bop.png',
            ],
            [
                'name' => 'HBL Bank',
                'type' => 'Bank Transfer',
                'account' => '0017207905946003',
                'holder' => 'Muhammad Usama Arshad',
                'logo' => 'website/images/hbl.png',
            ],
        ];

        return view('website.payment', compact('memberCount', 'currentMonthCollection', 'paymentChannels'));
    }
    public function monthlyReport(Request $request)
    {
        $now = Carbon::now();
        $useCustomPeriod = $request->boolean('filter');

        $month = $useCustomPeriod ? (int) $request->query('month', $now->month) : (int) $now->month;
        $year = $useCustomPeriod ? (int) $request->query('year', $now->year) : (int) $now->year;

        if ($month < 1 || $month > 12) {
            $month = (int) $now->month;
        }

        if ($year < 2024 || $year > 2100) {
            $year = (int) $now->year;
        }

        return view('website.monthly-report', [
            'month' => $month,
            'year' => $year,
            'payload' => $this->publicReportPayload($month, $year),
            'yearOptions' => [2026, 2027, 2028, 2029, 2030],
        ]);
    }

    public function show()
    {
        return view('website.become-part');
    }

    public function submitForm(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:15',
            'address'     => 'required|string',
            'message'     => 'required|string',
            'father_name' => 'required|string|max:255',
            'id_card'     => 'required|string|max:20',
        ]);

        $lead = Lead::create([
            'name' => $data['name'],
            'father_name' => $data['father_name'],
            'id_card' => $data['id_card'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'message' => $data['message'],
            'status' => 'new',
        ]);

        // Admin phone number (without +)
        $adminPhone = Phone::toWhatsapp((string) env('ADMIN_WHATSAPP_NUMBER', '923012704423'));
        $userPhone = Phone::toWhatsapp((string) $data['phone']);

        // ✅ Clean message (NO ICONS) + fields on new lines
        $msg =
            "*New Membership Request*\n"
            . "Farooka Brothers Welfare Society\n\n"
            . "A new user has applied to become a member. Details are below:\n\n"

            . "Name:\n{$data['name']}\n\n"
            . "Father Name:\n{$data['father_name']}\n\n"
            . "ID Card:\n{$data['id_card']}\n\n"
            . "Phone:\n{$data['phone']}\n\n"
            . "Email:\n{$data['email']}\n\n"
            . "Address:\n{$data['address']}\n\n"
            . "Message:\n{$data['message']}\n\n"
            . "Lead ID:\n#{$lead->id}\n\n"

            . "Please review this request and contact the applicant if required.\n\n"
            . "— Farooka Brothers Welfare Society Website";

        $waLink = "https://wa.me/{$adminPhone}?text=" . rawurlencode($msg);

        $userMsg =
            "*Membership Request Received*\n"
            . "Farooka Brothers Welfare Society\n\n"
            . "Dear {$data['name']},\n"
            . "Thank you! Your request has been submitted successfully.\n\n"
            . "Lead ID: #{$lead->id}\n"
            . "Our admin team will review your details and contact you soon.\n\n"
            . "— FBWS";

        $userWaLink = "https://wa.me/{$userPhone}?text=" . rawurlencode($userMsg);

        return redirect()->back()
            ->with('success', 'Your information has been submitted!')
            ->with('admin_wa_link', $waLink)
            ->with('user_wa_link', $userWaLink);
    }

    public function gallery()
    {
        $galleryImages = GalleryImage::with('user:id,name,profile_photo')->latest()->paginate(9);
        return view('website.gallery', compact('galleryImages'));
    }

    public function helpCenter()
    {
        return view('website.help-center');
    }

    // Contact submit data
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // =====================
        // WHATSAPP TO ADMINS (2 numbers)
        // =====================
        $adminPhones = [
            "923012704423",
            // "923251295352", // 2nd admin number (optional)
        ];

        // ✅ Clean message (NO ICONS) + fields on new lines
        $msgText =
            "*New Contact Inquiry*\n"
            . "Farooka Brothers Welfare Society\n\n"
            . "A new message has been received through the website contact form.\n\n"

            . "Name:\n{$data['name']}\n\n"
            . "Email:\n{$data['email']}\n\n"
            . "Phone:\n{$data['phone']}\n\n"
            . "Message:\n{$data['message']}\n\n"

            . "Please review and respond to the sender at your earliest convenience.\n\n"
            . "— Farooka Brothers Welfare Society Website Contact Form";

        $waLinks = [];

        foreach ($adminPhones as $phone) {

            // If helper exists use it, otherwise digits only
            if (class_exists(\App\Support\Phone::class)) {
                $phone = \App\Support\Phone::toWhatsapp($phone);
            } else {
                $phone = preg_replace('/\D/', '', $phone);
            }

            $waLinks[] = "https://wa.me/{$phone}?text=" . rawurlencode($msgText);
        }

        return redirect()->back()
            ->with('contact_wa_links', $waLinks);
    }
}
