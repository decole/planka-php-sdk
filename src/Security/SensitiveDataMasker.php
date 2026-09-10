<?php

declare(strict_types=1);

namespace Planka\Bridge\Security;

final class SensitiveDataMasker
{
    private const SENSITIVE_KEYS = [
        'password',
        'currentpassword',
        'apikey',
        'api_key',
        'token',
        'pendingtoken',
        'accesstoken',
        'access_token',
        'secret',
        'signature',
        'authorization',
        'x-api-key',
    ];

    /**
     * Recursively masks sensitive keys within an associative array.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public static function maskArray(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $normalizedKey = strtolower(str_replace(['_', '-'], '', $key));

            if (in_array($normalizedKey, self::SENSITIVE_KEYS, true)) {
                $sanitized[$key] = '********';
            } elseif (is_array($value)) {
                /* @var array<string, mixed> $value */
                $sanitized[$key] = self::maskArray($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitizes URL string by masking query parameters containing sensitive keys.
     */
    public static function maskUrl(string $url): string
    {
        $parsed = parse_url($url);

        if (!isset($parsed['query'])) {
            return $url;
        }

        parse_str($parsed['query'], $queryParams);
        /** @var array<string, mixed> $queryParams */
        $sanitizedQuery = self::maskArray($queryParams);

        $queryString = http_build_query($sanitizedQuery);
        $cleanUrl = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? '');

        if (isset($parsed['port'])) {
            $cleanUrl .= ':' . $parsed['port'];
        }

        if (isset($parsed['path'])) {
            $cleanUrl .= $parsed['path'];
        }

        return $cleanUrl . '?' . $queryString;
    }
}
