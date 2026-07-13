<?php

$exports['writeStringImpl'] = function($stream, $str, $enc) {
    if (method_exists($stream, 'writeString')) {
        $stream->writeString($str);
    } else {
        echo $str;
    }
    return true;
};

$exports['endImpl'] = function($stream) {
    if (method_exists($stream, 'end')) {
        $stream->end();
    } elseif (isset($stream->end)) {
        $f = $stream->end;
        $f();
    }
};

$exports['endCbImpl'] = function($stream, $cb) {
    if (method_exists($stream, 'end')) {
        $stream->end();
    } elseif (isset($stream->end)) {
        $f = $stream->end;
        $f();
    }
    $cb();
};

return $exports;
