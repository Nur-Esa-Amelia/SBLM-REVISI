<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeminiModel extends Model
{
    protected $fillable = [
        'name',
        'model_id',
        'api_key',
        'status',
    ];

    /**
     * Get the masked API key for display purposes.
     */
    public function getMaskedApiKeyAttribute()
    {
        $key = $this->api_key;
        if (strlen($key) > 8) {
            return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
        }
        return '********';
    }
}
