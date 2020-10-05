<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Default values
     */
    protected $attributes = [
        'status' => 0
    ];

    /**
     * Fillable fields
     */
    protected $fillable = ['name', 'document', 'status', 'user_id'];

    /**
     * Returns the status' options
     */
    public static function statusOptions()
    {
        return [
            0 => 'New',
            1 => 'Active',
            2 => 'Suspended',
            3 => 'Cancelled'
        ];
    }

    /**
     * Overrides the status attribute to it's string value
     */
    public function getStatusAttribute($attribute)
    {
        return $this->statusOptions()[$attribute];
    }

    /**
     * Returns the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the numbers
     */
    public function numbers() {
        return $this->hasMany(Number::class);
    }
}
