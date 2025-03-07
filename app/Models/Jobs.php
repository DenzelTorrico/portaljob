<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'salary'];
    
    public function applications()
    {
        return $this->hasMany(Applications::class, 'job_id');
    }
}
