<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $table = 'categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'color',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'category_id', 'id');
    }
}
