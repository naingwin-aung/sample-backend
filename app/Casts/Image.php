<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Image implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes) : mixed
    {
        if (Str::startsWith($value, 'http')) {
            return $value;
        }

        $images = json_decode($value);
        $path = request()->getSchemeAndHttpHost() . '/image/';

        if ($images && is_array($images)) {
            $data = [];
            foreach ($images as $image) {
                if (!empty($image)) {
                    $data[] = $path . $image;
                }
            }

            return $data;
        }
        if (!empty($value)) {
            return $path . $value;
        }

        return null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : mixed
    {
        return $value;
    }
}
