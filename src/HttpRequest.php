<?php

namespace westonwatson\realtag;

interface HttpRequest
{
    public function setUrl(string $url);
    public function setHeaders(array $headers);
    public function setPostData($data);
    public function setOption(string $name, $value);
    public function getInfo(string $name);
    public function execute();
    public function close();
}
