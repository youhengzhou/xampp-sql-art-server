<?php

$memcached = new Memcached();
$memcached->addServer('localhost', 11211);
$memcached->set('test', 'testcache');
var_dump($memcached->get('test'));
$memcached->set('test2', '123');
var_dump($memcached->get('test2'));
var_dump($memcached->get('test3'));
?>
