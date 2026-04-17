<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Complaint;
use App\Models\DeliveryOrder;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SupportRequest;
use App\Support\Phone;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    private function isAdmin($user): bool
    {
        return strtolower(trim((string) ($user->role ?? ''))) === 'admin';
    }

    private function decryptId(string $encryptedId): int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function index()
    {
        $auth = auth()->user();

        if (!$auth) {
            return redirect()->route('login');
        }

        $isAdmin = $this->isAdmin($auth);

        // Sirf admin allow
        if (!$isAdmin) {
            return redirect()->back()->with('error', 'Unauthorized: Only Admin can See User Page.');
        }

        // Admin → ALL users
        // Normal user → only own created users
        $users = $isAdmin
            ? User::latest()->get()
            : User::where('created_by', $auth->id)   // ⚠️ make sure column name correct
            ->latest()
            ->get();

        return view('users.list', compact('users'));
    }

    public function save(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'id_card'       => 'nullable|string|max:50',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image',
            'email'         => 'required|email|unique:users,email',
            'role'          => 'required|in:admin,user',
            'password'      => 'required|string|min:8',
            'position'      => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Plain password save for WhatsApp message
        $plainPassword = $request->password;

        // Create user
        $user = new User();
        $user->name         = $request->name;
        $user->id_card      = $request->id_card;
        $user->phone_number = $request->phone_number;
        $user->address      = $request->address;
        $user->email        = $request->email;
        $user->role         = $request->role;
        $user->position     = $request->position;
        $user->password     = Hash::make($plainPassword);
        $user->user_id      = auth()->id();

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            $dir = public_path('uploads/profile_photos');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move($dir, $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        // ✅ WhatsApp welcome link
        $waLink = null;

        if (!empty($user->phone_number)) {

            $phone = \App\Support\Phone::toWhatsapp($user->phone_number);

            $msg = "معزز رکن سوسائٹی {$user->name} 🌹\n\n"
                . "ہم آپ کو *فروکہ برادرز ویلفیئر سوسائٹی* میں شامل ہونے پر خوش آمدید کہتے ہیں۔\n"
                . "ہم آپ کی اس کاوش کو سراہتے ہیں اور دعاگو ہیں کہ اللہ پاک آپ کو استقامت عطا فرمائے\n"
                . "اور آپ کے رزق میں مزید برکتیں اور وسعتیں عطا فرمائے۔\n"
                . "آمین ثم آمین یا رب العالمین 🤲\n\n"
                . "آپ کے لاگ ان کی معلومات درج ذیل ہیں:\n"
                . "📧 ای میل: {$user->email}\n"
                . "🔑 پاس ورڈ: {$plainPassword}\n\n"
                . "براہ کرم پہلی بار لاگ ان کے بعد پاس ورڈ تبدیل کر لیں۔\n\n"
                . "منجانب:\n"
                . "ارکین فروکہ برادرز ویلفیئر سوسائٹی";

            $waLink = "https://wa.me/{$phone}?text=" . rawurlencode($msg);
        }

        return redirect()->back()
            ->with('success', 'User saved successfully!')
            ->with('wa_link', $waLink);
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->check() && $this->isAdmin(auth()->user()), 403);

        $user = User::findOrFail($this->decryptId($id));

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'id_card'       => 'nullable|string|max:50',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'position'      => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role'          => 'required|in:admin,user',
            'password'      => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name         = $request->name;
        $user->id_card      = $request->id_card;
        $user->phone_number = $request->phone_number;
        $user->position     = $request->position;
        $user->address      = $request->address;
        $user->email        = $request->email;
        $user->role         = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 🔹 Profile Photo Update
        if ($request->hasFile('profile_photo')) {

            // old image delete
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_photos'), $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }


    public function delete($id)
    {
        abort_unless(auth()->check() && $this->isAdmin(auth()->user()), 403);

        $user = User::findOrFail($this->decryptId($id));
        $user->delete();
        return redirect()->back()->with('success', 'User deleted and email sent.');
    }

    // Profile Methods

    public function profile()
    {
        $user = Auth::user();

        // user ki payments (latest first) + pagination
        $payments = $user->payment()
            ->latest('date')
            ->latest('time')
            ->paginate(10);

        // optional summary
        $totalPaid = $user->payment()->sum('amount');

        // ✅ har user ko apne hi orders + last 5
        $lastOrders = DeliveryOrder::with(['user', 'items.item']) // ✅ items ke saath item bhi
            ->where('user_id', $user->id)       // ✅ user ko sirf usi ke orders
            ->orderByDesc('delivery_date')      // ✅ latest delivery date
            ->orderByDesc('delivery_time')      // ✅ latest time
            ->take(5)
            ->get();


        return view('profile', compact('user', 'payments', 'totalPaid', 'lastOrders'));
    }

    public function showProfile($id)
    {
        $decryptedId = $this->decryptId($id);

        if ((int) $decryptedId !== (int) Auth::id()) {
            return redirect()->route('profile');
        }

        return $this->profile();
    }

    public function membershipCard()
    {
        $cardUser = Auth::user();

        return view('account.e-id-card-compact', [
            'cardUser' => $cardUser,
            'isAdminView' => false,
        ]);
    }

    public function membershipCardForUser($id)
    {
        $auth = Auth::user();
        $isAdmin = $this->isAdmin($auth);

        if (!$isAdmin) {
            return redirect()->route('account.membership-card')
                ->with('error', 'Unauthorized access to member card.');
        }

        $decryptedId = $this->decryptId($id);

        $cardUser = User::findOrFail($decryptedId);

        return view('account.e-id-card-compact', [
            'cardUser' => $cardUser,
            'isAdminView' => true,
        ]);
    }

    public function membershipCardVerify($id)
    {
        $decryptedId = $this->decryptId($id);

        $cardUser = User::findOrFail($decryptedId);

        $payments = Payment::where('user_id', $cardUser->id)
            ->latest('date')
            ->latest('time')
            ->take(6)
            ->get();

        $orders = Order::with('items')
            ->where('user_id', $cardUser->id)
            ->latest()
            ->take(6)
            ->get();

        $deliveries = DeliveryOrder::with('items')
            ->where('user_id', $cardUser->id)
            ->orderByDesc('delivery_date')
            ->orderByDesc('delivery_time')
            ->take(6)
            ->get();

        $complaints = Complaint::where('user_id', $cardUser->id)
            ->latest()
            ->take(6)
            ->get();

        $supportRequests = SupportRequest::where('user_id', $cardUser->id)
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'payments_count' => Payment::where('user_id', $cardUser->id)->count(),
            'paid_months_count' => Payment::where('user_id', $cardUser->id)->distinct('month')->count('month'),
            'payments_total' => Payment::where('user_id', $cardUser->id)->sum('amount'),
            'orders_count' => Order::where('user_id', $cardUser->id)->count(),
            'deliveries_count' => DeliveryOrder::where('user_id', $cardUser->id)->count(),
            'complaints_count' => Complaint::where('user_id', $cardUser->id)->count(),
            'suggestions_count' => Complaint::where('user_id', $cardUser->id)->where('type', 'suggestion')->count(),
            'support_count' => SupportRequest::where('user_id', $cardUser->id)->count(),
        ];

        return view('account.e-id-card-verify-standalone', [
            'cardUser' => $cardUser,
            'payments' => $payments,
            'orders' => $orders,
            'deliveries' => $deliveries,
            'complaints' => $complaints,
            'supportRequests' => $supportRequests,
            'stats' => $stats,
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'phone_number'  => 'nullable|string|max:20',
            'id_card'       => 'nullable|string|max:50',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image',
            'password'      => 'nullable|min:8|confirmed',
            'position'      => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name         = $request->name;
        $user->phone_number = $request->phone_number;
        $user->id_card      = $request->id_card;
        $user->address      = $request->address;
        $user->position     = $request->position;

        // Password update (optional)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Profile photo update
        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_photos'), $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }
}
