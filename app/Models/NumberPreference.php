<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NumberPreference extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Fillable fields
     */
    protected $fillable = ['name', 'value', 'number_id'];

    /**
     * Returns the number
     */
    public function number()
    {
        return $this->belongsTo(Number::class);
    }
}
