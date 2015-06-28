# Woohoo Labs. Harmony

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/woohoolabs/harmony/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/harmony/?branch=master)
[![Build Status](https://img.shields.io/travis/woohoolabs/harmony.svg)](https://travis-ci.org/woohoolabs/harmony)
[![Code Coverage](https://scrutinizer-ci.com/g/woohoolabs/harmony/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/harmony/?branch=master)
[![Stable Release](https://img.shields.io/packagist/v/woohoolabs/harmony.svg)](https://packagist.org/packages/woohoolabs/harmony)
[![License](https://img.shields.io/packagist/l/woohoolabs/harmony.svg)](https://packagist.org/packages/woohoolabs/harmony)

**Woohoo Labs. Harmony is a flexible micro-framework developed for PHP applications.**

Our aim was to create an invisible, easily extensible, but first of all, extremely flexible framework for your
quality application. We wanted to give you total control via
[PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) and
[Container-Interop](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md).

## Introduction

#### "Long live is the framework"

The general problem with frameworks is that they suggest you using the set of tools they have. Initially,
everything seems to be OK, because either the scope of your project is too small or you are sure that you've found
the best framework ever. But as soon as your requirements change (e.g. things get more complicated, you have to
maintain the application longer than you thought or you need more scaling), there is a good chance that you will
face issues with your framework.

As [Phil Sturgeon](https://twitter.com/philsturgeon) pointed out in his great [blog post](http://philsturgeon.uk/blog/2014/01/the-framework-is-dead-long-live-the-framework),
in a complex enough situation, with a skilled enough development team, you don't need a framework at all in its
original meaning thanks to the modern era of Composer. All you need is only a set of inter-pluggable components
of your preference so that they can be easily integrated into your application.

In conclusion, requirements will always change. What seemed to be a good choice once, it is not enough next time.
If things are impossible to change in your framework then it might lead to hard times when refactoring your
application even though if you tried hard to decouple your business objects from the framework.

We created Harmony to remedy this issue.

#### Use Cases of Woohoo Labs. Harmony

Certainly, Harmony won't suit the needs of all projects and teams. Firstly, this framework works best
for advanced teams. So less experienced teams should probably choose a less lenient framework with more features
in order to speed up development in its initial phase. Harmony's flexibility is the most advantageous if your
software is a long-term, strategic project. That's why legacy applications can also profit from Harmony because it
eases gradual refactoring.

#### Features

- Extreme flexibility through middlewares
- Full control over HTTP requests and responses via PSR-7
- Support for any IoC Containers via Container-Interop
- Totally object-oriented workflow

#### Concepts

Woohoo Labs. Harmony is build upon two main concepts: middlewares and common interfaces as they both promote
separation of concerns. Why is that?

Middlewares - that are [described in detail by Igor Wiedler](https://igor.io/2013/02/02/http-kernel-middlewares.html) -
make it possible to take hands on the course of action of the request-response lifecycle: you can authenticate before
routing, do some logging after the response has been sent, or you can even dispatch multiple routes in one
request if you want. These can be achieved because everything in Harmony is a middleware, so the framework itself only
consists of some getters and setters. And that's why there is no framework-wide configuration (only the middlewares can
be configured). Basically it only depends on your imagination and needs what you do with Harmony.

But middlewares must work in cooperation (especially the router and the dispatcher are tightly coupled to each other,
or one can also mention the request and the router). That's why it is also important to provide common interfaces for
the distinct components of the framework.

Naturally, we decided to use [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)
for modelling the HTTP request and response.

In order to faciliate the use of different IoC Containers when dispatching a controller, whe adapted the
[Container-Interop standard interface](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md)
(which is supported by various containers off-the-shelf). These, in conjunction with the
[dispatcher interface](https://github.com/woohoolabs/harmony/tree/master/src/Dispatcher/DispatcherInterface.php) fully separates the
concerns of routing the HTTP request to the appropriate controller. And they make it so easy to band your favourite
components together!

## Install

The steps of this process are quite straightforward. The only thing you need is [Composer](http://getcomposer.org).

#### Add Harmony to your composer.json:

To install this library, run the command below and you will get the latest version:

```bash
$ composer require woohoolabs/harmony
```

#### Require the necessary dependencies:

If you want to use the default middlewares then you have to ask for the following dependencies too:

```bash
$ composer require nikic/fast-route
$ composer require zendframework/zend-diactoros
```

#### Autoload in your bootstrap:

```php
require "vendor/autoload.php"
```

## Basic Usage

#### Define your endpoints:

There are two important things to notice here: first, each endpoint receives a ``Psr\Http\Message\ServerRequestInterface``
and a ``Psr\Http\Message\ResponseInterface`` object and they are expected to manipulate and return the latter.
Second, you are not forced to only use classes for the endpoints, it is possible to define anonymous functions too (see
below in the routing section).

```php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response)
    {
        $users= ["Steve", "Arnie", "Jason", "Bud"];
        $response->getBody()->write(json_encode($users));
        
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
     public function updateUser(ServerRequestInterface $request, ResponseInterface $response)
     {
        $userId= $request->getAttribute("id");
        $userData= $request->getParsedBody();

        // Updating user...
        
        return $response;
     }
}
```

#### Define your routes:

The following example pertains only to the default router used by Woohoo Labs. Harmony. We chose FastRoute for this purpose,
the [library](https://github.com/nikic/FastRoute) of Nikita Popov, because of its performance and elegance. You can read
more about it [in his blog](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).

Let's add three routes to the router among which the first one is an anonymous function:

```php
$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute("GET", "/me", function (ServerRequestInterface $request, ResponseInterface $response) {
        $response->getBody()->write("Welcome to the real world!");
        
        return $response;
    });
    
    $r->addRoute("GET", "/users", [\App\Controllers\UserController::class, "getUsers"]);
    $r->addRoute("POST", "/users/{id}", [\App\Controllers\UserController::class, "updateUser"]);
};
```

#### Finally, launch the framework:

You have to register all the following middlewares in order for the framework to function properly:
- ``InitializerMiddleware`` initializes the framework with an HTTP request, a response and a container
- ``FastRouteMiddleware`` takes care of routing (``$router``  was configured in the previous step)
- ``DispatcherMiddleware`` dispatches a controller class or callable which belongs to the current route
- ``DiactorosResponderMiddleware`` sends the response to the ether via [Zend Diactoros](https://github.com/zendframework/zend-diactoros)

```php
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\InitializerMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

Harmony::build()
    ->addMiddleware(new InitializerMiddleware(ServerRequestFactory::fromGlobals(), new Response()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware())
    ->addMiddleware(new DiactorosResponderMiddleware(new SapiEmitter()))
    ->live();
```

Of course, it is completely up to you how you add additional middlewares or how you replace them with your own
implementations. When you'd like to go live, just call the ``live()`` method!

## Advanced Usage

#### Adding Custom Middlewares

It's not a big deal to add a new middleware to your stack. For a basic scenario, there is a ``CallbackMiddleware`` you
can utilize. Let's say you would like to authenticate all the requests:

```php
$harmony->addMiddleware(
    new CallbackMiddleware("authentication",
        function(Harmony $harmony) {
            if ($harmony->getRequest()->getHeader("x-api-key") === "123") {
                $harmony->next();
            }
        }
    )
);
```

The first argument of the middleware's constructor is the ID of the middleware that must be unique, the second argument
is a callable which gets the reference of the full framework as its only parameter.

The single most important thing any middleware can do is to call ``$harmony->next()`` to invoke the next middleware
when its function was accomplished. Not calling this method means interrupting the framework's operation! That's why
we only invoke ``$harmony->next()`` in this example when authentication was successful. 

It you need more sophistication, there is also possibility to create a custom middleware. Let's reimplement the previous
authentication functionality as a separate middleware:

```php
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;
use WoohooLabs\Harmony\Harmony;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return "authentication";
    }

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     */
    public function execute(Harmony $harmony)
    {
        if ($harmony->getRequest()->getHeader("x-api-key") === $this->apiKey) {
             $harmony->next();
        }
    }
}
```

then

```php
$harmony->addMiddleware(new AuthenticationMiddleware("123"));
```

As you can see, the constructor receives the API Key, while the ``execute()`` method is responsible for performing the
authentication.

Again: the single most important thing any middleware can do is to call ``$harmony->next()`` to invoke the next middleware
when its function was accomplished. Not calling this method means interrupting the framework's operation! That's why
we only invoke ``$harmony->next()`` in this example when authentication was successful.

#### Redefining Default Components

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect
of the framework. That's why you can customize almost everything with minimal effort.

The following example shows how to swap the ``BasicContainer`` with the awesome [PHP-DI](http://php-di.org):

```php
$container= new \DI\Container();
$harmony->setContainer($container);
```

Maybe its more elegant to use the initializer middleware for this purpose:

```php
$container= new \DI\Container();
$harmony->addMiddleware(new InitializerMiddleware($yourRequest, $yourResponse, $container));
```

And what if you would like to replace the default router? Just do it, we don't really care. OK, there is something:
please make sure that your new router plays nice with the ``DispatcherMiddleware``, or you have to implement its
functionality by yourself (those two lines of code).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/harmony/blob/master/LICENSE.md)
for more information.
