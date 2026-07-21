<?php

$exports['writeStringImpl'] = function($stream, $str, $enc) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $stream instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($stream, $str) {
            try { $stream->write($str); } catch (\Throwable $e) {}
        });
        return true;
    }
    if (method_exists($stream, 'writeString')) {
        $stream->writeString($str);
    } elseif (method_exists($stream, 'write')) {
        $stream->write($str);
    } else {
        echo $str;
    }
    return true;
};

$exports['endImpl'] = function($stream) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $stream instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($stream) {
            try { $stream->end(); } catch (\Throwable $e) {}
        });
        return;
    }
    if (method_exists($stream, 'end')) {
        $stream->end();
    } elseif (isset($stream->end)) {
        $f = $stream->end;
        $f();
    }
};

$exports['endCbImpl'] = function($stream, $cb) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $stream instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($stream, $cb) {
            try { $stream->end(); } catch (\Throwable $e) {}
            $cb();
        });
        return;
    }
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

$exports['resumeImpl'] = function($r) { if (method_exists($r, 'resume')) $r->resume(); };

$exports['pauseImpl'] = function($r) { if (method_exists($r, 'pause')) $r->pause(); };

$exports['isPausedImpl'] = function($r) { return method_exists($r, 'isPaused') ? $r->isPaused() : false; };

$exports['pipeImpl'] = function($r, $w) { if (method_exists($r, 'pipe')) return $r->pipe($w); return $w; };

$exports['pipeCbImpl'] = function($r, $w, $cb) { 
    if (method_exists($r, 'pipeCb')) { $r->pipeCb($w, $cb); } 
    elseif (method_exists($r, 'pipe')) { $r->pipe($w); $cb(); } 
    else { $cb(); } 
};

$exports['unpipeAllImpl'] = function($r) { if (method_exists($r, 'unpipe')) $r->unpipe(); };

$exports['unpipeImpl'] = function($r, $w) { if (method_exists($r, 'unpipe')) $r->unpipe($w); };

$exports['readImpl'] = function($r) { return method_exists($r, 'read') ? $r->read() : null; };

$exports['readSizeImpl'] = function($r, $size) { return method_exists($r, 'read') ? $r->read($size) : null; };

$exports['writeImpl'] = function($w, $buf) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $w instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($w, $buf) {
            try { $w->write($buf); } catch (\Throwable $e) {}
        });
        return true;
    }
    if (method_exists($w, "write")) $w->write($buf); 
    elseif (isset($w->write)) { $f = $w->write; $f($buf); } 
    return true; 
};

$exports['writeCbImpl'] = function($w, $buf, $cb) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $w instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($w, $buf, $cb) {
            try { $w->write($buf); } catch (\Throwable $e) {}
            $cb();
        });
        return;
    }
    if (method_exists($w, "write")) $w->write($buf); 
    elseif (isset($w->write)) { $f = $w->write; $f($buf); } 
    $cb(); 
};

$exports['writeStringCbImpl'] = function($w, $str, $enc, $cb) {
    if (class_exists('\\Amp\\ByteStream\\WritableStream') && $w instanceof \Amp\ByteStream\WritableStream && class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($w, $str, $cb) {
            try { $w->write($str); } catch (\Throwable $e) {}
            $cb();
        });
        return;
    }
    if (method_exists($w, "write")) $w->write($str); 
    elseif (isset($w->write)) { $f = $w->write; $f($str); } 
    $cb(); 
};

$exports['corkImpl'] = function($w) { if (method_exists($w, 'cork')) $w->cork(); };

$exports['uncorkImpl'] = function($w) { if (method_exists($w, 'uncork')) $w->uncork(); };

$exports['setDefaultEncodingImpl'] = function($w, $enc) { if (method_exists($w, 'setDefaultEncoding')) $w->setDefaultEncoding($enc); };

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
