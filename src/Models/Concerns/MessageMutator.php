<?php


namespace Nggiahao\Facebook\Models\Concerns;

/**
 * @property $message_tags
 * @property $message
 */
trait MessageMutator
{
    /**
     * mutator message
     */
    public function getMessageAttribute() {
        $message = $this->message;
        if ($this->message_tags) {
            foreach ($this->message_tags as $tag) {
                $message = str_replace($tag->name, "@[$tag->id]", $message);
            }
        }
        return $message;
    }
}