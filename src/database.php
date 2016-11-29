<?php 

require_once 'Log.php';

ActiveRecord\Config::initialize(function($cfg)
{
	$logger = Log::singleton('file','phpar.log','ident',array('mode' => 0664, 'timeFormat' =>  '%Y-%m-%d %H:%M:%S'));
	$cfg->set_logging(true);
	$cfg->set_logger($logger);
	
	$password = urlencode('ka:3Cu!3Vi%4a');
    $cfg->set_model_directory( __DIR__ . '/../models');
    $cfg->set_connections(array(
        'development' => "mysql://collinsc_voltige:ui+2aA%9cAu:5w@127.0.0.1/collinsc_voltigetest?charset=utf8"
    ));
});

//ActiveRecord\DateTime::$DEFAULT_FORMAT = 'db';
