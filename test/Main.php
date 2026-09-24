<?php

$exports = [];

// The suite only needs a duplex value to chain with pipe; the mock stream of
// Node.Stream is enough (same model as the reference Go backend).
$exports['createGzip'] = function() {
    return ($GLOBALS['Node_Stream_newPassThrough'])();
};

$exports['createGunzip'] = function() {
    return ($GLOBALS['Node_Stream_newPassThrough'])();
};

return $exports;
