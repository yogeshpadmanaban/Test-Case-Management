<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestCase extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'test_cases';
    protected $primaryKey= 'id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'summary',
        'description',
        'attachment'
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

}
