<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormField extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'form_id',
        'type',
        'label',
        'name',
        'options',
        'order',
        'required',
        'extra'
    ];
    protected $casts = [
        'options' => 'array',
        'extra' => 'array',
        'required' => 'boolean',
    ];
    protected $dates = ['deleted_at'];
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
