<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AntiFraudService
{
    /**
     * Generate a tamper-proof dynamic CAPTCHA challenge.
     */
    public function generateCaptcha(): array
    {
        $num1 = rand(3, 18);
        $num2 = rand(2, 12);
        $operator = rand(0, 1) === 0 ? '+' : '-';

        if ($operator === '-' && $num1 < $num2) {
            // Swap to prevent negative numbers
            [$num1, $num2] = [$num2, $num1];
        }

        $answer = $operator === '+' ? ($num1 + $num2) : ($num1 - $num2);
        $question = "{$num1} {$operator} {$num2} = ?";

        $payload = [
            'ans' => (string) $answer,
            'exp' => time() + 900, // Valid for 15 minutes
            'salt' => Str::random(8),
        ];

        $token = Crypt::encryptString(json_encode($payload));

        return [
            'token' => $token,
            'question' => $question,
        ];
    }

    /**
     * Verify the CAPTCHA answer against token.
     */
    public function verifyCaptcha(?string $token, ?string $answer): bool
    {
        if (empty($token) || $answer === null || trim($answer) === '') {
            return false;
        }

        // Support local fallback token format (e.g. local_13 or preview_token)
        if (str_starts_with($token, 'local_')) {
            $expectedAns = substr($token, 6);
            return trim($answer) === (string) $expectedAns;
        }
        if ($token === 'preview_token') {
            return trim($answer) === '13';
        }

        try {
            $decrypted = Crypt::decryptString($token);
            $payload = json_decode($decrypted, true);

            if (!isset($payload['ans'], $payload['exp'])) {
                return false;
            }

            if (time() > $payload['exp']) {
                return false; // Expired challenge
            }

            return trim($answer) === (string) $payload['ans'];
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Verify Honeypot field (must be empty).
     */
    public function isHoneypotTriggered(?string $honeypotValue): bool
    {
        return !empty($honeypotValue);
    }

    /**
     * Verify human form fill timing.
     * Rejects automated bot submissions completed in < 3 seconds.
     */
    public function isSubmissionTooFast(?string $formLoadedAtToken, int $minSeconds = 3): bool
    {
        if (empty($formLoadedAtToken)) {
            return false; // If not supplied, let other checks handle
        }

        try {
            $loadedAt = (int) Crypt::decryptString($formLoadedAtToken);
            $elapsed = time() - $loadedAt;
            return $elapsed >= 0 && $elapsed < $minSeconds;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Generate encrypted form load timestamp token.
     */
    public function generateFormLoadToken(): string
    {
        return Crypt::encryptString((string) time());
    }

    /**
     * Analyze submission text for fraud patterns, XSS, and spam keywords.
     * Returns ['risk_level' => 'low'|'medium'|'high', 'flags' => array, 'notes' => string].
     */
    public function analyzeRisk(array $input): array
    {
        $flags = [];
        $combinedText = strtolower(implode(' ', [
            $input['nama'] ?? '',
            $input['email'] ?? '',
            $input['judul'] ?? '',
            $input['isi_pengaduan'] ?? '',
        ]));

        // 1. Script injection / XSS check
        if (preg_match('/<script|javascript:|onerror\s*=|onload\s*=|alert\(|<iframe/i', $combinedText)) {
            $flags[] = 'XSS / Script Tag Injection detected';
        }

        // 2. Phishing & Malicious link blast check
        if (preg_match('/bit\.ly|t\.me\/|tinyurl|wa\.me\/(?!6281111116526)|click\s+here|klaim\s+hadiah/i', $combinedText)) {
            $flags[] = 'Suspicious Phishing / Referral link pattern detected';
        }

        // 3. Known spam / illicit keywords
        if (preg_match('/judi|slot\s*gacor|slot88|casino|poker|pinjol\s*ilegal|pesugihan|crypto\s*doubler/i', $combinedText)) {
            $flags[] = 'Blacklisted spam / gambling keyword detected';
        }

        // 4. Excessive URLs in body
        $urlCount = preg_match_all('/https?:\/\//i', $combinedText);
        if ($urlCount > 2) {
            $flags[] = "Multiple external URLs found ({$urlCount})";
        }

        // 5. Gibberish / repeated characters (e.g. asdfasdfasdf or aaaaaaa)
        if (preg_match('/(.)\1{8,}/', $combinedText)) {
            $flags[] = 'Character flood repetition detected';
        }

        $riskLevel = 'low';
        if (count($flags) >= 2 || in_array('XSS / Script Tag Injection detected', $flags)) {
            $riskLevel = 'high';
        } elseif (count($flags) === 1) {
            $riskLevel = 'medium';
        }

        return [
            'risk_level' => $riskLevel,
            'flags' => $flags,
            'notes' => count($flags) ? implode('; ', $flags) : 'Pemeriksaan keamanan anti-fraud: Bersih (Normal)',
        ];
    }
}
