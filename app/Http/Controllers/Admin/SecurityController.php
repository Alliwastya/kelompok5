<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $logs = SecurityLog::query()
            ->when($request->input('ip'), function ($query) use ($request) {
                $query->where('ip_address', $request->input('ip'));
            })
            ->when($request->input('event_type'), function ($query) use ($request) {
                $query->where('event_type', $request->input('event_type'));
            })
            ->when($request->input('is_blocked'), function ($query) use ($request) {
                $query->where('is_blocked', $request->input('is_blocked') === 'true');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $blockedIps = SecurityLog::where('is_blocked', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '>', now());
            })
            ->distinct('ip_address')
            ->count();

        $suspiciousActivities = SecurityLog::where('event_type', 'suspicious_activity')
            ->whereDate('created_at', today())
            ->count();

        return view('admin.security.index', [
            'logs' => $logs,
            'blockedIps' => $blockedIps,
            'suspiciousActivities' => $suspiciousActivities
        ]);
    }

    public function blockIp(Request $request, $ip)
    {
        // Validate IP address format
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return redirect()->back()->with('error', "Format IP address tidak valid: $ip");
        }
        
        $minutes = (int) $request->input('minutes', 60);
        
        // Log the action
        \Log::info("[Security] Admin blocking IP", [
            'ip' => $ip,
            'minutes' => $minutes,
            'admin_user' => auth()->user()->email ?? 'unknown'
        ]);
        
        // Block the specific IP
        $affected = SecurityLog::where('ip_address', $ip)->update([
            'is_blocked' => true,
            'blocked_until' => now()->addMinutes($minutes)
        ]);
        
        \Log::info("[Security] IP blocked successfully", [
            'ip' => $ip,
            'affected_rows' => $affected
        ]);

        return redirect()->back()->with('success', "IP $ip berhasil diblokir selama $minutes menit ($affected record diupdate)");
    }

    public function unblockIp($ip)
    {
        // Validate IP address format
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return redirect()->back()->with('error', "Format IP address tidak valid: $ip");
        }
        
        // Log the action
        \Log::info("[Security] Admin unblocking IP", [
            'ip' => $ip,
            'admin_user' => auth()->user()->email ?? 'unknown'
        ]);
        
        // Unblock the specific IP
        $affected = SecurityLog::where('ip_address', $ip)->update([
            'is_blocked' => false,
            'blocked_until' => null
        ]);
        
        \Log::info("[Security] IP unblocked successfully", [
            'ip' => $ip,
            'affected_rows' => $affected
        ]);

        return redirect()->back()->with('success', "IP $ip berhasil dihapus dari blokir ($affected record diupdate)");
    }

    public function clearLogs(Request $request)
    {
        $days = (int) $request->input('days', 7);
        SecurityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->back()->with('success', "Log keamanan lebih dari $days hari berhasil dihapus");
    }
}
