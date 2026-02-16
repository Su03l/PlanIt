<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Enums\GroupRole;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * تسجيل مستخدم جديد + إنشاء مساحة عمل تلقائياً
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // 1. إنشاء المستخدم
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => strtolower($data['first_name']) . rand(1000, 9999), // يوزرنيم مؤقت
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // 2. تحديد اسم المجموعة (لو ما كتبه، نخليه "فريق [الاسم]")
            $groupName = $data['team_name'] ?? $user->first_name . "'s Workspace";

            // 3. إنشاء المجموعة (لاحظ استخدمنا create لأننا ضفنا trait HasSlug في الموديل)
            $group = Group::create([
                'name' => $groupName,
                'owner_id' => $user->id,
            ]);

            // 4. ربط المستخدم بالمجموعة بصفة ADMIN
            $user->groups()->attach($group->id, [
                'role' => GroupRole::ADMIN->value
            ]);

            // 5. إصدار التوكن
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'group' => $group,
                'token' => $token,
            ];
        });
    }

    /**
     * تسجيل الدخول
     */
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null; // فشل الدخول
        }

        $user = User::where('email', $credentials['email'])->first();

        // إرسال تنبيه أمني
        $user->notify(new SystemNotification(
            "New Login Detected",
            "A new login to your account was detected at " . now()->format('H:i'),
            "security",
            "/profile/settings"
        ));

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    /**
     * تسجيل الخروج
     */
    public function logout($user)
    {
        // حذف التوكن الحالي فقط
        $user->currentAccessToken()->delete();
        return true;
    }
}
