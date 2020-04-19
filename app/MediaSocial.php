<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaSocial extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'media_socials';
    protected $fillable = [
        'user_id', 'social_media', 'username',
    ];
    public $timestamps = true;
}
