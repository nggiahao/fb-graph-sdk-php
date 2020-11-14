<?php


namespace Nggiahao\Facebook\Models;



use Nggiahao\Facebook\Models\Caster\AttachmentCaster;
use Nggiahao\Facebook\Models\Caster\CommentCaster;
use Nggiahao\Facebook\Models\Caster\UserCaster;
use Nggiahao\Facebook\Models\Concerns\MessageMutator;

/**
 * @property mixed $id
 * @property mixed $message
 * @property User $from
 * @property mixed $permalink_url
 * @property mixed $link
 * @property mixed $attachments
 * @property mixed $comments
 * @property mixed $created_time
 *
 */
class Feed extends Model
{
//    use MessageMutator;

    protected $casts = [
        'attachments' => AttachmentCaster::class,
        'attachment' => AttachmentCaster::class,
        'comments' => CommentCaster::class,
        'from' => UserCaster::class,
    ];
}