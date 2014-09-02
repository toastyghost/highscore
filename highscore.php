<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');

require 'Zend/Amf/Server.php';
require 'highscore.class.php';

$server = new Zend_Amf_Server();
$server->setClass('highscore');

echo $server->handle();