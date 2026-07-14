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



$exports['setEncodingImpl'] = function($s, $enc) {};

$exports['readChunkImpl'] = function($useBuffer, $useString, $chunk) { if (is_string($chunk)) return $useString($chunk); return $useBuffer($chunk); };

$exports['readableImpl'] = function($r) { return true; };

$exports['readableEndedImpl'] = function($r) { return false; };

$exports['readableFlowingImpl'] = function($r) { return false; };

$exports['readableHighWaterMarkImpl'] = function($r) { return 0; };

$exports['readableLengthImpl'] = function($r) { return 0; };

$exports['resumeImpl'] = function($r) {};

$exports['pauseImpl'] = function($r) {};

$exports['isPausedImpl'] = function($r) { return false; };

$exports['pipeImpl'] = function($r, $w) {};

$exports['pipeCbImpl'] = function($r, $w, $cb) { $cb(); };

$exports['unpipeAllImpl'] = function($r) {};

$exports['unpipeImpl'] = function($r, $w) {};

$exports['readImpl'] = function($r) { return null; };

$exports['readSizeImpl'] = function($r, $size) { return null; };

$exports['writeImpl'] = function($w, $buf) { if (method_exists($w, "write")) $w->write($buf); elseif (isset($w->write)) { $f = $w->write; $f($buf); } return true; };

$exports['writeCbImpl'] = function($w, $buf, $cb) { if (method_exists($w, "write")) $w->write($buf); elseif (isset($w->write)) { $f = $w->write; $f($buf); } $cb(); };

$exports['writeStringCbImpl'] = function($w, $str, $enc, $cb) { if (method_exists($w, "write")) $w->write($str); elseif (isset($w->write)) { $f = $w->write; $f($str); } $cb(); };

$exports['corkImpl'] = function($w) {};

$exports['uncorkImpl'] = function($w) {};

$exports['setDefaultEncodingImpl'] = function($w, $enc) {};

$exports['writeableImpl'] = function($w) { return true; };

$exports['writeableEndedImpl'] = function($w) { return false; };

$exports['writeableCorkedImpl'] = function($w) { return false; };

$exports['erroredImpl'] = function($w) { return null; };

$exports['writeableFinishedImpl'] = function($w) { return false; };

$exports['writeableHighWaterMarkImpl'] = function($w) { return 0; };

$exports['writeableLengthImpl'] = function($w) { return 0; };

$exports['writeableNeedDrainImpl'] = function($w) { return false; };

$exports['destroyImpl'] = function($w) { if (method_exists($w, "destroy")) $w->destroy(); elseif (isset($w->destroy)) { $f = $w->destroy; $f(); } };

$exports['destroyErrorImpl'] = function($w, $e) { if (method_exists($w, "destroy")) $w->destroy($e); elseif (isset($w->destroy)) { $f = $w->destroy; $f($e); } };

$exports['closedImpl'] = function($w) { return false; };

$exports['destroyedImpl'] = function($w) { return false; };

$exports['allowHalfOpenImpl'] = function($d) { return false; };

$exports['pipelineImpl'] = function($src, $transforms, $dst, $cb) { $cb(); };

$exports['readableFromStrImpl'] = function($str, $encoding) { return $str; };

$exports['readableFromBufImpl'] = function($buf) { return $buf; };

$exports['newPassThrough'] = function() { return new \stdClass(); };

return $exports;
