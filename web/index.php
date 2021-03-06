<?php

require_once __DIR__.'/../vendor/autoload.php';

use Neoxygen\NeoClient\ClientBuilder;

$app = new Silex\Application();

$app['neo'] = $app->share(function(){
    $client = ClientBuilder::create()
        ->addConnection('default', 'http', 'localhost', 7474, true, 'neo4j', 'password')
        ->setAutoFormatResponse(true)
        ->build();

    return $client;
});

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/social.log'
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->get('/', 'Ikwattro\\SocialNetwork\\Controller\\WebController::home')
    ->bind('home');

$app->get('/user/{login}', 'Ikwattro\\SocialNetwork\\Controller\\WebController::showUser')
    ->bind('show_user');

$app->post('/relationship/create', 'Ikwattro\\SocialNetwork\\Controller\\WebController::createRelationship')
    ->bind('relationship_create');

$app->post('/relationship/remove', 'Ikwattro\\SocialNetwork\\Controller\\WebController::removeRelationship')
    ->bind('relationship_remove');

$app->get('/users/{user_login}/posts', 'Ikwattro\\SocialNetwork\\Controller\\WebController::showUserPosts')
    ->bind('user_post');

$app->get('/users/{user_login}/timeline', 'Ikwattro\\SocialNetwork\\Controller\\WebController::showUserTimeline')
    ->bind('user_timeline');

$app->post('/new_post', 'Ikwattro\\SocialNetwork\\Controller\\WebController::newPost')
    ->bind('new_post');

$app->run();