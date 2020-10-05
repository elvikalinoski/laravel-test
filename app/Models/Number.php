<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Number extends Model
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
    protected $fillable = ['number', 'status', 'customer_id'];

    /**
     * Returns the status' options
     */
    public static function statusOptions()
    {
        return [
            0 => 'Active',
            1 => 'Inactive',
            2 => 'Cancelled'
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
     * Returns the customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Returns the number preferences
     */
    public function numberPreferences() {
        return $this->hasMany(NumberPreference::class);
    }
}
