--TEST--
v8js.thread_pool_size INI directive validation and lifecycle
--SKIPIF--
<?php require_once(dirname(__FILE__) . '/skipif.inc'); ?>
--INI--
v8js.thread_pool_size=0
--FILE--
<?php
// 1. Initial value
var_dump(ini_get('v8js.thread_pool_size'));

// 2. Valid values before initialization (including boundaries 0 and 64)
var_dump(ini_set('v8js.thread_pool_size', '1'));
var_dump(ini_get('v8js.thread_pool_size'));

var_dump(ini_set('v8js.thread_pool_size', '64'));
var_dump(ini_get('v8js.thread_pool_size'));

var_dump(ini_set('v8js.thread_pool_size', '0'));
var_dump(ini_get('v8js.thread_pool_size'));

var_dump(ini_set('v8js.thread_pool_size', '4'));
var_dump(ini_get('v8js.thread_pool_size'));

// 3. Out-of-range and malformed values (must return false, value remains unchanged)
var_dump(ini_set('v8js.thread_pool_size', '-1'));
var_dump(ini_set('v8js.thread_pool_size', '-42'));
var_dump(ini_set('v8js.thread_pool_size', '65'));
var_dump(ini_set('v8js.thread_pool_size', '100'));
var_dump(@ini_set('v8js.thread_pool_size', '-1foo'));
var_dump(@ini_set('v8js.thread_pool_size', '100M'));
var_dump(ini_get('v8js.thread_pool_size'));

// 4. Initialize V8 platform by creating an instance
$v8 = new V8Js();
var_dump($v8->executeString('1 + 2'));

// 5. Attempts to change setting after initialization must fail
var_dump(ini_set('v8js.thread_pool_size', '2'));
var_dump(ini_set('v8js.thread_pool_size', '0'));
var_dump(ini_set('v8js.thread_pool_size', '64'));
var_dump(ini_get('v8js.thread_pool_size'));

// 6. Attempts to change setting after destroying instance must still fail
unset($v8);
var_dump(ini_set('v8js.thread_pool_size', '8'));
var_dump(ini_set('v8js.thread_pool_size', '0'));
var_dump(ini_get('v8js.thread_pool_size'));
?>
===EOF===
--EXPECT--
string(1) "0"
string(1) "0"
string(1) "1"
string(1) "1"
string(2) "64"
string(2) "64"
string(1) "0"
string(1) "0"
string(1) "4"
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
string(1) "4"
int(3)
bool(false)
bool(false)
bool(false)
string(1) "4"
bool(false)
bool(false)
string(1) "4"
===EOF===
