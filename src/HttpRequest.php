<?php

namespace westonwatson\realtag;

interface HttpRequest
{
    public function setOption(string $name, $value);
    public function execute();
    public function getInfo(string $name);
    public function close();
}
