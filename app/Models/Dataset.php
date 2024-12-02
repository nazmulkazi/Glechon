<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'creator_id', 'course', 'activity', 'year', 'semester', 'name', 'num_responses', 'labels', 'statistics', 'statistics_updated_at'
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'statistics_updated_at',
        'statistics',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'statistics_updated_at' => 'datetime',
        'labels' => 'object',
        'statistics' => 'object'
    ];
    
    /**
     * Returns responses of the dataset.
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
    
    /**
     * Returns annotations of the authenticated user.
     * if `annotator_id` is given, returns annotations of the corresponding annotator.
     * if `annotator_id` is negative, returns annotations of all annotators.
     */
    public function annotations($annotator_id = null)
    {
        $query = $this->hasMany(Annotation::class);
        if (!is_null($annotator_id)) {
            return $query->where('annotator_id', $annotator_id);
        }
        return $query;
    }
}
