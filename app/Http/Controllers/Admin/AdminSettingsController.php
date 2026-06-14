<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $groups = SiteSetting::orderBy('group_name')->orderBy('sort_order')
            ->get()->groupBy('group_name');

        $activeGroup = 'general';
        return view('admin.settings.index', compact('groups', 'activeGroup'));
    }

    public function group(string $group)
    {
        $settings    = SiteSetting::where('group_name', $group)->orderBy('sort_order')->get();
        $groupValues = $settings->pluck('value', 'key')->toArray();
        $activeGroup = $group;

        $groups = SiteSetting::orderBy('group_name')->orderBy('sort_order')
            ->get()->groupBy('group_name');

        return view('admin.settings.group', compact('settings', 'groupValues', 'group', 'activeGroup', 'groups'));
    }

    public function update(Request $request)
    {
        // The form sends flat key=value pairs (e.g. name="site_name")
        // or nested settings[key]=value — handle both
        $raw = $request->except(['_token', '_method', 'group']);

        // Unpack nested settings[] array if present
        $toSave = isset($raw['settings']) && is_array($raw['settings'])
            ? $raw['settings']
            : $raw;

        foreach ($toSave as $key => $value) {
            // Normalise booleans submitted as '0'/'1'
            $valueToStore = is_array($value) ? json_encode($value) : (string) $value;

            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $valueToStore]
            );

            Cache::forget("setting_{$key}");
        }

        Cache::forget('settings_public');
        Cache::flush(); // flush all caches so changes are visible immediately

        $group    = $request->input('group', '');
        $redirect = $group
            ? redirect()->route('admin.settings.group', $group)
            : redirect()->route('admin.settings.index');

        return $redirect->with('success', 'Settings saved successfully.');
    }

    // ── Profile ───────────────────────────────────────────────────────────

    public function profile()
    {
        $admin = auth('admin')->user();
        return view('admin.settings.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'avatar'           => 'nullable|image|max:2048',
            'current_password' => 'nullable|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (! Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
        }

        unset($data['current_password'], $data['password_confirmation']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $admin->update(array_filter($data, fn($v) => $v !== null));

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    // ── Cache ─────────────────────────────────────────────────────────────

    public function clearCache()
    {
        Cache::flush();
        return redirect()->back()->with('success', 'Application cache cleared successfully.');
    }
}
