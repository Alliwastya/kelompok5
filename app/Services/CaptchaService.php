<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CaptchaService
{
    /**
     * Generate CAPTCHA token dan simpan di cache
     */
    public static function generate()
    {
        $token = Str::random(32);
        $code = rand(100000, 999999);
        
        Cache::put('captcha_' . $token, $code, now()->addMinutes(5));
        
        return [
            'token' => $token,
            'code' => $code // For testing/development only
        ];
    }

    /**
     * Verify CAPTCHA token dan code
     */
    public static function verify($token, $code)
    {
        $cacheKey = 'captcha_' . $token;
        $storedCode = Cache::get($cacheKey);

        if (!$storedCode) {
            return false;
        }

        if ((string)$storedCode === (string)$code) {
            Cache::forget($cacheKey);
            return true;
        }

        return false;
    }

    /**
     * Generate simple math CAPTCHA
     */
    public static function generateMath()
    {
        $num1 = rand(1, 50);
        $num2 = rand(1, 50);
        $operators = ['+', '-', '*'];
        $operator = $operators[array_rand($operators)];

        $token = Str::random(32);
        
        if ($operator === '+') {
            $answer = $num1 + $num2;
        } elseif ($operator === '-') {
            $answer = $num1 - $num2;
        } else {
            $answer = $num1 * $num2;
        }

        // Store in cache with 10 minute expiry
        $cacheKey = 'captcha_math_' . $token;
        Cache::put($cacheKey, [
            'answer' => $answer,
            'question' => "$num1 $operator $num2"
        ], now()->addMinutes(10));

        \Log::info('[CAPTCHA] Generated', [
            'token' => $token,
            'question' => "$num1 $operator $num2",
            'answer' => $answer
        ]);

        return [
            'token' => $token,
            'question' => "$num1 $operator $num2",
            'answer' => $answer // For testing only
        ];
    }

    /**
     * Verify math CAPTCHA
     */
    public static function verifyMath($token, $answer)
    {
        $cacheKey = 'captcha_math_' . $token;
        $captchaData = Cache::get($cacheKey);

        \Log::info('[CAPTCHA] Verifying', [
            'token' => $token,
            'userAnswer' => $answer,
            'storedData' => $captchaData
        ]);

        if (!$captchaData) {
            \Log::warning('[CAPTCHA] Token not found or expired', ['token' => $token]);
            return false;
        }

        $correctAnswer = $captchaData['answer'] ?? null;

        if ((int)$answer === (int)$correctAnswer) {
            Cache::forget($cacheKey);
            \Log::info('[CAPTCHA] Verification successful');
            return true;
        }

        \Log::warning('[CAPTCHA] Answer mismatch', [
            'userAnswer' => $answer,
            'correctAnswer' => $correctAnswer
        ]);
        return false;
    }
}
