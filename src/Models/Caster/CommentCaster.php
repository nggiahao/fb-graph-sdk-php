<?php


namespace Nggiahao\Facebook\Models\Caster;


use Illuminate\Support\Collection;
use Nggiahao\Facebook\Models\Comment;

class CommentCaster implements CastsAttributes
{

    public function get($value)
    {
        $comments = [];
        if (isset($value['data'])) {
            foreach ($value['data'] as $comment) {
                $comments[] = new Comment($comment);
            }
        }

        return new Collection($comments);
    }

    public function set($value)
    {
        // TODO: Implement set() method.
    }
}