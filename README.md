# Woohoo Labs. Harmony

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/woohoolabs/harmony/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/harmony/?branch=master)
[![Build Status](https://travis-ci.org/woohoolabs/harmony.svg)](https://travis-ci.org/woohoolabs/harmony)
[![Coverage Status](https://coveralls.io/repos/woohoolabs/harmony/badge.svg)](https://coveralls.io/r/woohoolabs/harmony)

**Woohoo Labs. Harmony is a flexible micro-framework developed for PHP applications.**

Our aim was to create an invisible, easily extensible, but first of all, extremely flexible framework for your
quality application. We wanted to give you total control while providing a clean interface to communicate with.

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
application even though that it is decoupled from your project.

We created Harmony to remedy this issue.

#### Use Cases of Woohoo Labs. Harmony

Certainly, Harmony won't suit the needs of all projects and teams. Firstly, this framework works best
for advanced teams. So less experienced teams should probably choose a less lenient framework with more features
in order to speed up development in its initial phase. Harmony's flexibility is the most advantageous if your
software is a long-term, strategic project. That's why legacy applications can also profit from Harmony because it
eases gradual refactoring.

#### Features

- Extreme flexibility through middlewares
- Totally object-oriented workflow
- Full control over HTTP requests and responses via PSR-7
- Support for any IoC Containers via Container-Interop

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

In order to faciliate the use of different IoC Containers when dispatching a controller, whe adapted the
[Container-Interop standard interface](https://github.com/container-interop/container-interop/blob/master/src/Interop/Container/ContainerInterface.php)
(which is supported by various containers off-the-shelf). These, in conjunction with the
[dispatchers](https://github.com/woohoolabs/harmony/tree/master/src/Dispatcher) fully separates the concerns of routing
the HTTP request to the appropriate controller. And they make it so easy to band your favourite components together!

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
and a ``Psr\Http\Message\ResponseInterface`` object and they are expected to manipulate and return with the latter.
Second, you are not forced to use classes only for the endpoints, it is possible to define anonymous functions too (see
the next step).

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
        $response->setContent($users);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
     public function updateUser(ServerRequestInterface $request, ResponseInterface $response)
     {
        $userId= $request->getAttribute("id");
        $userData= $request->getParsedBody();
        $response = $response->withBody(new Stream('php://temp'));
        
        return $response;
     }
}
```

You don't have to worry that your endpoints become tightly coupled to HTTP. Just read
[this fantastic post](https://igor.io/2013/02/03/http-foundation-value.html) from [Igor Wiedler](https://twitter.com/igorwhiletrue).

#### Define your routes:

The following example pertains only to the default router used by Woohoo Labs. Harmony. We chose FastRouter for this purpose,
the [library](https://github.com/nikic/FastRoute) of Nikita Popov, because of its performance and elegance. You can read
more about it [in his blog](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).

```php
$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute("GET", "/me", function (RequestInterface $request, ResponseInterface $response) {
        $response->setContent("Welcome to the real world!");
    });
    
    $r->addRoute("GET", "/users", [\App\Controllers\UserController::class, "getUsers"]);
    $r->addRoute("POST", "/users/{id}", [\App\Controllers\UserController::class, "updateUser"]);
};
```

#### Finally, launch the framework:

You have to register all the following middlewares in order for the framework to function properly:

```php
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\InitializerMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\CallbackMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use Zend\Diactoros\ServerRequestFactory;

$harmony = Harmony::build()
    ->addMiddleware(new InitializerMiddleware(ServerRequestFactory::fromGlobals(), new Response(), $container))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware())
    ->addMiddleware(new DiactorosResponderMiddleware())

$harmony->live();
```

Of course, it is completely up to you how you add additional middlewares or how you replace them with your own
implementations. When you'd like to go live, just call the ``live()`` method!

## Advanced Usage

#### Adding Custom Middlewares

It's not a big deal to add a new middleware to your stack. For a basic scenario, there is a ``CallbackMiddleware`` that
you can utilize. Let's say you would like to authenticate all the requests:

```php
$harmony->addMiddleware(
    new CallbackMiddleware("authentication",
        function(Harmony $harmony) {
            if ($harmony->getRequest()->getHeader("x-api-key") !== "123") {
                die();
            }
        }
    )
);
```

The first argument of the constructor is the ID of the middleware that must be unique, the second argument is an anonymous
function which gets the reference of the full framework as its only parameter.
 
It you need more sophistication, there is also possibility to create a custom middleware. Let's reimplement the previous
authentication functionality:

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
        if ($harmony->getRequest()->getHeader("x-api-key") !== $this->apiKey) {
             die();
        }
    }
}
```

then

```php
$harmony->addMiddleware(new AuthenticationMiddleware("123"));
```

As you can see, the constructor receives the API Key, while the ``execute()`` method is responsible for performing the
authentication. It's interesting that you are even able to manipulate the other middlewares thanks to the framework's
reference passed to the method!

#### Redefining Default Components

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect
of the framework. That's why you can customize almost everything with minimal effort.

The following example shows how to swap the ``BasicContainer`` with PHP-DI:

```php
$container= new \DI\Container();
$harmony->setContainer($container);
```

Maybe its more elegant to use the initializer middleware for this purpose:

```php
$harmony->addMiddleware(new InitializerMiddleware($container));
```

This middleware is able to prepopulate the HTTP request and the response too. By default, we wrapped Symfony's HttpFoundation
inside classes implementing the common HTTP interfaces, but you are free to provide your own implementations if you need
something else.

```php
$harmony->addMiddleware(new InitializerMiddleware($yourRequest, $yourResponse, $yourContainer));
```

And what if you would like to replace the default router? Just do it, We don't really care. OK, there is something:
please make sure that your new router plays nice with the ``DispatcherMiddleware``, or you have to implement its
functionality by yourself (those two lines of code).

#### Hooks

Hooking enables you to get the control before and/or after dispatching occurs. Note that it is available for class
endpoints only! If you specify a ``preHook()`` method in your handler class then it will be automatically invoked
before the handler method. The same way, if you specify a ``postHook()`` method then it will be called after the
original handler method has been invoked. Important to note that the hooking methods must also expect a ``Request``
and a ``Response`` object as their only arguments and they aren't required to provide any return value (just like regular
handler methods).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/harmony/blob/master/LICENSE.md)
for more information.
