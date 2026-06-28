<?php

namespace App\Services;

use Illuminate\Support\Arr;

class LumicashService
{
    protected static function path(): string
    {
        return storage_path('app/lumicash.json');
    }

    public static function get(): array
    {
        if (file_exists(self::path())) {
            $content = file_get_contents(self::path());
            return json_decode($content ?: '{}', true) ?: [];
        }

        return [
            'phone' => env('LUMICASH_PHONE', ''),
            'name' => env('LUMICASH_NAME', ''),
        ];
    }

    public static function set(string $phone, string $name): void
    {
        $data = [
            'phone' => $phone,
            'name' => $name,
        ];

        file_put_contents(self::path(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
