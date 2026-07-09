<?php

$exports['writeStringImpl'] = function($stream) {
    return function($enc) {
        return function($str) {
            return function($cb) {
                return function() use ($str, $cb) {
                    echo $str;
                    $cb(null);
                };
            };
        };
    };
};

return $exports;
