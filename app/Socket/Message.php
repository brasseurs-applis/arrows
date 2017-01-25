<?php

namespace BrasseursApplis\Arrows\App\Socket;

interface Message
{
    /**
     * @return string
     */
    public function getType();
}
