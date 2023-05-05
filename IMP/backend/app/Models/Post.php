<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'image',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if($model->getKey() == null){
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
