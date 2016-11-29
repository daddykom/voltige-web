<?php
// Routes
use \Firebase\JWT\JWT;

$app->get('/admin/users', array( 'App\Controller\Users','search') );
$app->post('/admin/users', array( 'App\Controller\Users','search') );
$app->get('/admin/user/{user_id}', array( 'App\Controller\Users','get') );
$app->post('/admin/user', array( 'App\Controller\Users','post') );
$app->delete('/admin/user/{user_id}', array( 'App\Controller\Users','delete') );

$app->get('/admin/code', array( 'App\Controller\Codes','search') );
$app->post('/admin/codes', array( 'App\Controller\Codes','search') );
$app->get('/admin/code/{code_id}', array( 'App\Controller\Codes','get') );
$app->get('/codes/{codename}', array( 'App\Controller\Codes','get_codes') );
$app->get('/code_cmds/{code_id}', array( 'App\Controller\Codes','get_code_cmds') );
$app->post('/admin/code', array( 'App\Controller\Codes','post') );
$app->delete('/admin/code/{code_id}', array( 'App\Controller\Codes','delete') );
$app->get('/admin/code_cmds', array( 'App\Controller\Codes','search_cmd') );
$app->post('/admin/code_cmds', array( 'App\Controller\Codes','search_cmd') );
$app->post('/admin/code_texts', array( 'App\Controller\Codes','search_text') );
$app->get('/admin/code_cmd/{code_cmd_id}', array( 'App\Controller\Codes','get_cmd') );
$app->post('/admin/code_cmd', array( 'App\Controller\Codes','post_cmd') );
$app->delete('/admin/code_cmd/{code_cmd_id}', array( 'App\Controller\Codes','delete_cmd') );
$app->get('/admin/code_cmd_text/{code_cmd_text_id}', array( 'App\Controller\Codes','get_cmd_text') );
$app->post('/admin/code_cmd_text', array( 'App\Controller\Codes','post_cmd_text') );
$app->delete('/admin/code_cmd_text/{code_cmd_id}', array( 'App\Controller\Codes','delete_cmd') );

$app->get('/actual_show', array( 'App\Controller\Shows','actual') );
$app->get('/admin/shows', array( 'App\Controller\Shows','search') );
$app->post('/admin/shows', array( 'App\Controller\Shows','search') );
$app->get('/admin/show/{show_id}', array( 'App\Controller\Shows','get') );
$app->post('/admin/show', array( 'App\Controller\Shows','post') );
$app->delete('/admin/show/{show_id}', array( 'App\Controller\Shows','delete') );

$app->any('/upload', array( 'App\Controller\Upload','upload') );
$app->get('/admin/longesub_all/{show_id}', array( 'App\Controller\Subscriptions','longesub_all') );
$app->get('/admin/longsub_get_approx/{description}', array( 'App\Controller\Subscriptions','longsub_get_approx') );

$app->post('/admin/member_check', array( 'App\Controller\Members','member_check') );

$app->post('/admin/subscription', array( 'App\Controller\Subscriptions','post') );
$app->post('/admin/subscriptions', array( 'App\Controller\Subscriptions','search') );
$app->get('/admin/subscription/{subscription_id}', array( 'App\Controller\Subscriptions','get') );
$app->post('/admin/subscription_member', array( 'App\Controller\Subscriptions','post_member') );
$app->post('/admin/subscription_beings', array( 'App\Controller\Subscriptions','updateSubscriptionBeings') );

$app->get('/admin/category_bases', array( 'App\Controller\CategoryBases','all') );
$app->post('/admin/category_base', array( 'App\Controller\CategoryBases','post') );
$app->get('/admin/category_base/{id}', array( 'App\Controller\CategoryBases','get') );
$app->delete('/admin/category_base/{id}', array( 'App\Controller\CategoryBases','delete') );


$app->get('/php', function( $req, $res, $args ) {
	phpinfo();
});


$app->get('/[template/{routepath}]', function ( $req, $res, $args ){
	$routepath = $req->getAttribute('routepath');
	return $this->renderer->render ($res, $routepath ? $routepath : 'index', $args );
});
	