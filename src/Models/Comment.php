<?php


namespace Nggiahao\Facebook\Models;


use Nggiahao\Facebook\Models\Caster\AttachmentCaster;
use Nggiahao\Facebook\Models\Caster\CommentCaster;
use Nggiahao\Facebook\Models\Caster\UserCaster;

class Comment extends Model
{
    protected $casts = [
        'attachments' => AttachmentCaster::class,
        'attachment' => AttachmentCaster::class,
        'comments' => CommentCaster::class,
        'from' => UserCaster::class,
    ];
}