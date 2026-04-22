<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginHistoryController extends Controller
{
    private function isAdmin($user): bool
    {
        return strtolower(trim((string) ($user->role ?? ''))) === 'admin';
    }

    private function applyFilters($query, Request $request)
    {
        $now = Carbon::now();
        $monthParam = $request->query('month');
        $yearParam = $request->query('year');

        // Default to current month/year when not provided
        $month = ($monthParam === null || $monthParam === '') ? (int) $now->month : (int) $monthParam;
        $year = ($yearParam === null || $yearParam === '') ? (int) $now->year : (int) $yearParam;

        if ($year > 0) {
            $query->whereYear('logged_in_at', $year);
        }
        if ($month >= 1 && $month <= 12) {
            $query->whereMonth('logged_in_at', $month);
        }

        return [$query, $month, $year];
    }

    public function index(Request $request)
    {
        $auth = $request->user();

        [$query, $month, $year] = $this->applyFilters(
            LoginHistory::where('user_id', $auth->id),
            $request
        );

        $histories = $query
            ->orderByDesc('logged_in_at')
            ->paginate(20)
            ->withQueryString();

        return view('login-history.index', compact('histories', 'month', 'year'));
    }

    public function admin(Request $request)
    {
        $auth = $request->user();
        abort_unless($auth && $this->isAdmin($auth), 403);

        $selectedUserId = (int) $request->query('user_id', 0);
        $selectedUser = null;

        if ($selectedUserId > 0) {
            $selectedUser = User::select('id', 'name', 'email', 'id_card', 'role', 'profile_photo')->find($selectedUserId);
        }

        $users = User::query()
            ->select('id', 'name', 'email', 'id_card', 'role', 'profile_photo')
            ->where('id', '!=', $auth->id)
            ->orderBy('name')
            ->get();

        // Never show the logged-in admin's own logins here (other admins' logs still show)
        $baseQuery = LoginHistory::with(['user:id,name,email,id_card,role,profile_photo'])
            ->where('user_id', '!=', $auth->id);
        if ($selectedUser) {
            $baseQuery->where('user_id', $selectedUser->id);
        }

        [$query, $month, $year] = $this->applyFilters($baseQuery, $request);

        $histories = $query
            ->orderByDesc('logged_in_at')
            ->get();

        $historySummaries = $histories
            ->groupBy('user_id')
            ->map(function ($rows) {
                $sortedRows = $rows->sortByDesc('logged_in_at')->values();
                $latest = $sortedRows->first();

                return [
                    'user' => $latest?->user,
                    'login_count' => $sortedRows->count(),
                    'latest' => $latest,
                    'rows' => $sortedRows,
                ];
            })
            ->sortByDesc(function ($summary) {
                return optional($summary['latest'])->logged_in_at;
            })
            ->values();

        return view('login-history.admin', compact('users', 'selectedUser', 'selectedUserId', 'histories', 'historySummaries', 'month', 'year'));
    }

    public function devices(Request $request)
    {
        $auth = $request->user();

        $rows = LoginHistory::query()
            ->where('user_id', $auth->id)
            ->orderByDesc('logged_in_at')
            ->get();

        $currentIp = $request->ip();
        $currentAgent = (string) $request->userAgent();

        $devices = $rows
            ->groupBy(function ($row) {
                return md5(($row->ip_address ?? 'na') . '|' . ($row->user_agent ?? 'na'));
            })
            ->map(function ($group) use ($currentIp, $currentAgent) {
                $latest = $group->sortByDesc('logged_in_at')->first();
                $agent = (string) ($latest->user_agent ?? '');

                return [
                    'device_name' => $this->detectDeviceName($agent),
                    'browser' => $this->detectBrowser($agent),
                    'platform' => $this->detectPlatform($agent),
                    'ip_address' => $latest->ip_address ?? 'NA',
                    'first_login_at' => optional($group->sortBy('logged_in_at')->first())->logged_in_at,
                    'last_login_at' => optional($latest)->logged_in_at,
                    'login_count' => $group->count(),
                    'is_current' => ($latest->ip_address ?? null) === $currentIp && $agent === $currentAgent,
                ];
            })
            ->sortByDesc('last_login_at')
            ->values();

        $latestLoginAt = optional($rows->first())->logged_in_at;

        return view('account.devices', compact('devices', 'latestLoginAt'));
    }

    public function logoutAllDevices(Request $request)
    {
        $user = $request->user();

        $user->forceFill([
            'session_version' => (int) ($user->session_version ?? 1) + 1,
        ])->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out from all devices.');
    }

    private function detectBrowser(string $agent): string
    {
        $agent = strtolower($agent);

        return match (true) {
            str_contains($agent, 'edg') => 'Microsoft Edge',
            str_contains($agent, 'opr/') || str_contains($agent, 'opera') => 'Opera',
            str_contains($agent, 'chrome') && !str_contains($agent, 'edg') => 'Google Chrome',
            str_contains($agent, 'firefox') => 'Mozilla Firefox',
            str_contains($agent, 'safari') && !str_contains($agent, 'chrome') => 'Safari',
            default => 'Unknown Browser',
        };
    }

    private function detectPlatform(string $agent): string
    {
        $agent = strtolower($agent);

        return match (true) {
            str_contains($agent, 'windows') => 'Windows',
            str_contains($agent, 'android') => 'Android',
            str_contains($agent, 'iphone') || str_contains($agent, 'ipad') || str_contains($agent, 'ios') => 'iOS',
            str_contains($agent, 'mac os') || str_contains($agent, 'macintosh') => 'macOS',
            str_contains($agent, 'linux') => 'Linux',
            default => 'Unknown OS',
        };
    }

    private function detectDeviceName(string $agent): string
    {
        $agent = strtolower($agent);

        return match (true) {
            str_contains($agent, 'iphone') => 'iPhone',
            str_contains($agent, 'ipad') => 'iPad',
            str_contains($agent, 'android') && str_contains($agent, 'mobile') => 'Android Phone',
            str_contains($agent, 'android') => 'Android Tablet',
            str_contains($agent, 'windows') => 'Windows PC',
            str_contains($agent, 'macintosh') || str_contains($agent, 'mac os') => 'Mac',
            str_contains($agent, 'linux') => 'Linux PC',
            default => 'Unknown Device',
        };
    }
}

