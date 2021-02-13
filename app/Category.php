<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    protected $connection = 'mysql';
    protected $table = 'categories';
    protected $primaryKey= 'id';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'module_name'
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

	public function childs() {
        return $this->hasMany('App\Category','parent_id','id') ;
    }
}
