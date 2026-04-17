<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\AuditLog; // ✅ ADD
use App\Support\Phone;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private function ensureAuthenticated(): void
    {
        abort_unless(auth()->check(), 403);
    }

    private function decryptId(string $encryptedId): int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    private function isAdmin($user): bool
    {
        return strtolower((string) ($user?->role ?? '')) === 'admin';
    }

    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Payments',
            'action' => $action,
            'auditable_type' => Payment::class,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    public function index()
    {
        $this->ensureAuthenticated();

        $authUser = auth()->user();

        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $baseQuery = Payment::query()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear);

        if (!$this->isAdmin($authUser)) {
            $baseQuery->where('user_id', $authUser->id);
        }

        $paymentStats = [
            'total_payments' => (clone $baseQuery)->count(),
            'total_amount' => (int) ((clone $baseQuery)->sum('amount')),
            'paid_members' => (clone $baseQuery)->distinct('user_id')->count('user_id'),
        ];

        if ($this->isAdmin($authUser)) {

            // Current month me jin users ki payment already ho chuki hai
            $paidUserIds = Payment::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->pluck('user_id');

            // Un users ko dropdown se hata do
            $users = User::whereNotIn('id', $paidUserIds)
                ->orderBy('name')
                ->get();

            $payments = Payment::with('user')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->latest()
                ->paginate(8)
                ->withQueryString();
        } else {

            $users = collect();

            $payments = Payment::with('user')
                ->where('user_id', $authUser->id)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->latest()
                ->paginate(8)
                ->withQueryString();
        }

        return view('payment.index', compact('payments', 'users', 'paymentStats'));
    }

    public function save(Request $request)
    {
        // ✅ Admin only (payment add)
        abort_unless(auth()->check() && $this->isAdmin(auth()->user()), 403);

        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|exists:users,id',
            'amount'         => 'required|numeric|min:0',
            'month'          => 'required|string|max:20',
            'date'           => 'required|date',
            'time'           => 'required',
            'receipt_number' => 'nullable|string|max:100|unique:payments,receipt_number',
            'picture'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payment = new Payment();
        $payment->user_id        = $request->user_id;
        $payment->amount         = $request->amount;
        $payment->month          = $request->month;
        $payment->date           = $request->date;
        $payment->time           = $request->time;
        $payment->receipt_number = $request->receipt_number;

        if ($request->hasFile('picture')) {
            $path = public_path('uploads/payment_receipts');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->picture->extension();
            $request->picture->move($path, $imageName);
            $payment->picture = 'uploads/payment_receipts/' . $imageName;
        }

        $payment->save();

        // ✅ AUDIT LOG (CREATE)
        $this->audit('created', 'Payment Created', $payment->id, null, $payment->toArray());

        // ✅ WhatsApp link generate (FREE)
        $user = User::find($payment->user_id);

        $waLink = null;
        if ($user && !empty($user->phone_number)) {
            $phone = Phone::toWhatsapp($user->phone_number);

            $name   = $user->name ?? 'Member';
            $amount = number_format((float)$payment->amount, 0);
            $month  = $payment->month;
            $date   = date('d M Y', strtotime($payment->date));

            $msg = "السلام علیکم معزز رکن سوسائٹی\n\n"
                . "*{$name}*\n\n"
                . "آپ کو مطلع کیا جاتا ہے کہ ماہ {$month} کا فنڈ "
                . "({$amount} روپے) فروکہ برادرز ویلفیئر سوسائٹی کے اکاؤنٹ میں کامیابی سے جمع ہو چکا ہے۔\n\n"
                . "اللہ پاک آپ کے رزق میں مزید برکتیں اور وسعتیں عطا فرمائے۔\n"
                . "فروکہ برادرز ویلفیئر سوسائٹی کے ساتھ تعاون کرنے کا بہت بہت شکریہ، "
                . "اللہ پاک آپ کو اجر عظیم عطا فرمائے۔\n\n"
                . "منجانب:\n"
                . "*محمد اسامہ ارشد فروکہ*\n"
                . "فنانس سیکرٹری\n"
                . "*فروکہ برادرز ویلفیئر سوسائٹی*";

            $waLink = "https://wa.me/{$phone}?text=" . rawurlencode($msg);
        }

        return redirect()->back()
            ->with('success', 'Payment saved successfully!')
            ->with('wa_link', $waLink);
    }

    public function update(Request $request, $id)
    {
        // ✅ Admin only (payment update)
        abort_unless(auth()->check() && $this->isAdmin(auth()->user()), 403);

        $payment = Payment::findOrFail($this->decryptId($id));

        // ✅ OLD snapshot
        $old = $payment->toArray();

        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|exists:users,id',
            'amount'         => 'required|numeric|min:0',
            'month'          => 'required|string|max:20',
            'date'           => 'required|date',
            'time'           => 'required',
            'receipt_number' => 'nullable|string|max:100|unique:payments,receipt_number,' . $payment->id,
            'picture'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payment->user_id        = $request->user_id;
        $payment->amount         = $request->amount;
        $payment->month          = $request->month;
        $payment->date           = $request->date;
        $payment->time           = $request->time;
        $payment->receipt_number = $request->receipt_number;

        if ($request->hasFile('picture')) {
            if ($payment->picture && file_exists(public_path($payment->picture))) {
                unlink(public_path($payment->picture));
            }

            $path = public_path('uploads/payment_receipts');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->picture->extension();
            $request->picture->move($path, $imageName);
            $payment->picture = 'uploads/payment_receipts/' . $imageName;
        }

        $payment->save();

        // ✅ AUDIT LOG (UPDATE)
        $this->audit('updated', 'Payment Updated', $payment->id, $old, $payment->fresh()->toArray());

        return redirect()->back()->with('success', 'Payment updated successfully!');
    }

    public function delete($id)
    {
        // ✅ Admin only (payment delete)
        abort_unless(auth()->check() && $this->isAdmin(auth()->user()), 403);

        $payment = Payment::findOrFail($this->decryptId($id));

        // ✅ OLD snapshot before delete
        $old = $payment->toArray();
        $paymentId = $payment->id;

        if ($payment->picture && file_exists(public_path($payment->picture))) {
            unlink(public_path($payment->picture));
        }

        $payment->delete();

        // ✅ AUDIT LOG (DELETE)
        $this->audit('deleted', 'Payment Deleted', $paymentId, $old, null);

        return redirect()->back()->with('success', 'Payment deleted successfully!');
    }

    // Payment history (list all payments with user)
    public function history(Request $request)
    {
        $this->ensureAuthenticated();

        $authUser = $request->user();
        $isAdmin = $this->isAdmin($authUser);

        $month = $request->get('month'); // "01".."12" or null
        $year  = $request->get('year');  // 2026..3036 or null

        $query = Payment::query()
            ->with([
                'user:id,name,profile_photo',
                'changer:id,name', // optional relation if exists
            ])
            ->latest();

        // User restriction
        if (!$isAdmin) {
            $query->where('user_id', $authUser->id);
        }

        // Year filter (only allow 2026-3036)
        if (!empty($year)) {
            $year = (int) $year;
            if ($year >= 2026 && $year <= 3036) {
                $query->whereYear('date', $year);
            }
        }

        // Month filter (01-12)
        if (!empty($month)) {
            $month = (int) $month; // convert "01" -> 1
            if ($month >= 1 && $month <= 12) {
                $query->whereMonth('date', $month);
            }
        }

        $histories = $query->get();

        return view('payment.history', compact('histories', 'isAdmin'));
    }
    public function printReceipt($id)
    {
        $this->ensureAuthenticated();

        $payment = Payment::with('user')->findOrFail($this->decryptId($id));

        if (!$this->isAdmin(auth()->user())) {
            abort_unless((int) $payment->user_id === (int) auth()->id(), 403);
        }

        return view('payment.print', compact('payment'));
    }
}
