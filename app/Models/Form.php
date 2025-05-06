<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'table_name',
        'user_id',
        'description',
        'json_file',
        'version'
    ];
    protected $dates = ['deleted_at'];
    public function fields()
    {
        return $this->hasMany(FormField::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
