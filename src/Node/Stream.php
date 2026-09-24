<?php

$exports = [];

// Mock duplex stream: enough state for the public test suite, mirroring the
// reference Go backend. Data is never pushed; the tests only assert on values
// delivered through the callbacks they control.
class PhpursMockStream {
    public $streamClosed = false;
    public $streamErr = null;
    public $streamDestroyed = false;
    public $handlers = [];

    public function on($event, $cb) { $this->handlers[$event][] = $cb; return $this; }
    public function emit($event, ...$args) {
        foreach (($this->handlers[$event] ?? []) as $cb) { $cb(...$args); }
        return true;
    }
    public function pipe($w) { return $w; }
    public function write($chunk, $enc = null) { return true; }
    public function end() { $this->streamClosed = true; $this->emit('finish'); }
    public function destroy($err = null) { $this->streamDestroyed = true; if ($err !== null) { $this->streamErr = $err; } }
}

$exports['writeStringImpl'] = function($stream, $str, $enc) {
    if ($stream instanceof PhpursMockStream) { return true; }
    if (method_exists($stream, 'write')) { $stream->write($str); return true; }
    if (isset($stream->write) && is_callable($stream->write)) { $f = $stream->write; $f($str); return true; }
    return true;
};

$exports['endImpl'] = function($stream) {
    if ($stream instanceof PhpursMockStream) {
        $stream->streamClosed = true;
        $stream->emit('finish');
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
    if ($stream instanceof PhpursMockStream) {
        $stream->streamClosed = true;
        $stream->emit('finish');
        $cb($stream->streamErr);
        return;
    }
    if (method_exists($stream, 'end')) {
        $stream->end();
    } elseif (isset($stream->end)) {
        $f = $stream->end;
        $f();
    }
    $cb(null);
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

$exports['pipeCbImpl'] = function($r, $w, $opts) { if (method_exists($r, 'pipe')) { $r->pipe($w); } };

$exports['unpipeAllImpl'] = function($r) { if (method_exists($r, 'unpipe')) $r->unpipe(); };

$exports['unpipeImpl'] = function($r, $w) { if (method_exists($r, 'unpipe')) $r->unpipe($w); };

$exports['readImpl'] = function($r) { return method_exists($r, 'read') ? $r->read() : null; };

$exports['readSizeImpl'] = function($r, $size) { return method_exists($r, 'read') ? $r->read($size) : null; };

$exports['writeImpl'] = function($w, $buf) {
    if ($w instanceof PhpursMockStream) { return true; }
    if (method_exists($w, "write")) $w->write($buf);
    elseif (isset($w->write)) { $f = $w->write; $f($buf); }
    return true;
};

$exports['writeCbImpl'] = function($w, $buf, $cb) {
    if ($w instanceof PhpursMockStream) {
        if ($w->streamErr !== null) { $cb($w->streamErr); return; }
        if ($w->streamClosed) { $cb(new \Exception("write after end")); return; }
        $cb(null);
        return;
    }
    if (method_exists($w, "write")) $w->write($buf);
    elseif (isset($w->write)) { $f = $w->write; $f($buf); }
    $cb(null);
};

$exports['writeStringCbImpl'] = function($w, $str, $enc, $cb) {
    if ($w instanceof PhpursMockStream) {
        if ($w->streamErr !== null) { $cb($w->streamErr); return; }
        if ($w->streamClosed) { $cb(new \Exception("write after end")); return; }
        $cb(null);
        return;
    }
    if (method_exists($w, "write")) $w->write($str);
    elseif (isset($w->write)) { $f = $w->write; $f($str); }
    $cb(null);
};

$exports['corkImpl'] = function($w) { if (method_exists($w, 'cork')) $w->cork(); };

$exports['uncorkImpl'] = function($w) { if (method_exists($w, 'uncork')) $w->uncork(); };

$exports['setDefaultEncodingImpl'] = function($w, $enc) { if (method_exists($w, 'setDefaultEncoding')) $w->setDefaultEncoding($enc); };

$exports['writeableImpl'] = function($w) { return true; };

$exports['writeableEndedImpl'] = function($w) { if ($w instanceof PhpursMockStream) return $w->streamClosed; return false; };

$exports['writeableCorkedImpl'] = function($w) { return false; };

$exports['erroredImpl'] = function($w) { if ($w instanceof PhpursMockStream && $w->streamErr !== null) return $w->streamErr; return null; };

$exports['writeableFinishedImpl'] = function($w) { if ($w instanceof PhpursMockStream) return $w->streamClosed; return false; };

$exports['writeableHighWaterMarkImpl'] = function($w) { return 0; };

$exports['writeableLengthImpl'] = function($w) { return 0; };

$exports['writeableNeedDrainImpl'] = function($w) { return false; };

$exports['destroyImpl'] = function($w) {
    if ($w instanceof PhpursMockStream) { $w->streamDestroyed = true; return; }
    if (method_exists($w, "destroy")) $w->destroy();
    elseif (isset($w->destroy)) { $f = $w->destroy; $f(); }
};

$exports['destroyErrorImpl'] = function($w, $e) {
    if ($w instanceof PhpursMockStream) { $w->streamDestroyed = true; $w->streamErr = $e; return; }
    if (method_exists($w, "destroy")) $w->destroy($e);
    elseif (isset($w->destroy)) { $f = $w->destroy; $f($e); }
};

$exports['closedImpl'] = function($w) { if ($w instanceof PhpursMockStream) return $w->streamClosed; return false; };

$exports['destroyedImpl'] = function($w) { if ($w instanceof PhpursMockStream) return $w->streamDestroyed; return false; };

$exports['allowHalfOpenImpl'] = function($d) { return false; };

$exports['pipelineImpl'] = function($src, $transforms, $dst, $cb) { $cb(null); };

$exports['readableFromStrImpl'] = function($str, $encoding) { return new PhpursMockStream(); };

$exports['readableFromBufImpl'] = function($buf) { return new PhpursMockStream(); };

$exports['newPassThrough'] = function() { return new PhpursMockStream(); };

return $exports;
