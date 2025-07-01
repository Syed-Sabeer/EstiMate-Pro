<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_phone',
        'total_area',
        'floor_length',
        'floor_width',
        'wall_height',
        'bathroom_type',
        'tiling_level',
        'design_style',
        'home_age_category',
        'photos',
        'calculated_floor_area',
        'calculated_wall_area',
        'calculated_total_area',
        'budget_area',
        'standard_area',
        'premium_area',
        'base_estimate',
        'high_estimate',
        'status'
    ];

    protected $casts = [
        'total_area' => 'double',
        'floor_length' => 'double',
        'floor_width' => 'double',
        'wall_height' => 'double',
        'calculated_floor_area' => 'double',
        'calculated_wall_area' => 'double',
        'calculated_total_area' => 'double',
        'budget_area' => 'double',
        'standard_area' => 'double',
        'premium_area' => 'double',
        'base_estimate' => 'double',
        'high_estimate' => 'double',
        'photos' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
