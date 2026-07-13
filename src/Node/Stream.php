<?php

$exports['writeStringImpl'] = function($stream, $str, $enc) {
    if (method_exists($stream, 'writeString')) {
        $stream->writeString($str);
    } else {
        echo $str;
    }
    return true;
};

return $exports;
