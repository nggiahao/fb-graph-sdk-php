<?php


namespace Nggiahao\Facebook\Models;



use Nggiahao\Facebook\Models\Caster\AttachmentCaster;
use Nggiahao\Facebook\Models\Caster\CommentCaster;

class Feed extends Model
{
    protected $casts = [
        'attachments' => AttachmentCaster::class,
        'comments' => CommentCaster::class
    ];
}