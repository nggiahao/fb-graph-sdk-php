<?php


namespace Nggiahao\Facebook\Models;



use Nggiahao\Facebook\Models\Caster\AttachmentCaster;

class Feed extends Model
{
    protected $casts = [
        'attachments' => AttachmentCaster::class
    ];
}