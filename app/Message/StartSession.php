<?php

namespace BrasseursApplis\Arrows\App\Message;

class StartSession implements Message
{
    const TYPE = 'session.start';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }
}
