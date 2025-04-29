<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'table_name', 'user_id'];
    public function fields()
    {
        return $this->hasMany(FormField::class);
    }
}
