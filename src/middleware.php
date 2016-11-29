<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);


 $app->add(new \Slim\Middleware\JwtAuthentication([
		"secret" => file_get_contents( __DIR__ . '/../secret.txt'),
 		//"passthrough" => ['/','/#/'],
 		"path" => ['/xadmin'],
 		"callback" => function ($request, $response, $arguments) use ($container) {
 		$container["jwt"] = $arguments["decoded"];
 		}
]));
 