# Woohoo Labs. Harmony

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

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

However, as [Phil Sturgeon](https://twitter.com/philsturgeon) pointed out in his great [blog post](http://philsturgeon.uk/blog/2014/01/the-framework-is-dead-long-live-the-framework),
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
consists of cc. 200 lines of code. And that's why there is no framework-wide configuration (only the middlewares can
be configured). Basically it only depends on your imagination and needs what you do with Harmony.

But middlewares must work in cooperation (especially the router and the dispatcher are tightly coupled to each other,
or one can also mention the request and the router). That's why it is also important to provide common interfaces for
the distinct components of the framework.

Naturally, we decided to use [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)
for modelling the HTTP request and response.

In order to faciliate the use of different IoC Containers when dispatching a controller, whe adapted the
[Container-Interop standard interface](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md)
(which is supported by various containers off-the-shelf). They make it so easy to band your favourite components together!

#### Available middlewares

Woohoo Labs. Harmony's middleware interface design is based on the style that is advocated
by [Matthew Weier O'Phinney](https://github.com/weierophinney) (you can read more on the topic below), that's why
Harmony's middlewares are compatible with middlewares built for both
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility) and
[Slim Framework 3](http://www.slimframework.com/docs/concepts/middleware.html). Furthermore, there are various other
middlewares available for Harmony:

- [Woohoo Labs. Yin-Middlewares](https://github.com/woohoolabs/yin-middlewares): A bunch of middlewares to integrate
[Woohoo Labs. Yin](https://github.com/woohoolabs/harmony) - the elegant JSON API framework - into Harmony.
- [MiniUrl](https://github.com/mtymek/MiniUrl): A simple URL shortener, which can be used as a free, open-source
replacement for bit.ly's core functionality: creating short links and redirecting users.

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

## Basic Usage

#### Define your endpoints:

There are two important things to notice here: first, each endpoint receives a `Psr\Http\Message\ServerRequestInterface`
and a `Psr\Http\Message\ResponseInterface` object and they are expected to manipulate and return the latter.
Second, you are not forced to only use classes for the endpoints, it is possible to define other callables too (see
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

Let's add three routes to FastRoute:

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
- `FastRouteMiddleware` takes care of routing (`$router`  was configured in the previous step)
- `DispatcherMiddleware` dispatches a controller which belongs to the request's current route
- `DiactorosResponderMiddleware` sends the response to the ether via [Zend Diactoros](https://github.com/zendframework/zend-diactoros)

Note that the `Harmony::addMiddleware()` method's first argument is the ID of the middleware (which should be unique)
and the middleware attached via `Harmony::setFinalMiddleware()` will always be executed after the normal middlewares!
In this case, we always want our response to be sent by `DiactorosResponderMiddleware`. 

```php
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

$harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());
$harmony
    ->addMiddleware("fast_route", new FastRouteMiddleware($router))
    ->addMiddleware("dispatcher", new DispatcherMiddleware())
    ->setFinalMiddleware(new DiactorosResponderMiddleware(new SapiEmitter()));

$harmony();
```

Of course, it is completely up to you how you add additional middlewares or how you replace them with your own
implementations. When you'd like to go live, just call `$harmony()`!

## Advanced Usage

#### Using Your Favourite DI Container with Harmony

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect
of the framework. That's why you can use such a DI Container you want.

For this purpose, we chose
the [Container-Interop standard](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md)
(it is PSR-11 now) to be the common interface for DI Containers in the built-in `DispatcherMiddleware`.

It's also important to know that the `DispatcherMiddleware` uses the `BasicContainer` by default. It's nothing more
than a very silly DIC which tries to create objects based on their class name (so calling 
`$basicContainer->get(Foo::class)` would create a new `Foo` instance).

But if you provide an argument to the middleware's constructor, you can use your favourite Container-Interop compliant
DIC too. Let's have a look at an example where one would like to swap `BasicContainer` with the awesome [PHP-DI](http://php-di.org):

```php
$container= new \DI\Container();
$harmony->addMiddleware("dispatcher", new DispatcherMiddleware($container));
```

#### Creating Custom Middlewares

It's not a big deal to add a new middleware to your stack. For a basic scenario, you can use anonymous functions.
Let's say you would like to log all the requests:

```php
$middleware = function(ServerRequestInterace $request, ResponseInterface $response, callable $next) {
    // Logging
    
    $next();
}
```

And then you have to attach the middleware to Harmony:

```php
$harmony->addMiddleware("logging", $middleware);
```

The single most important thing a middleware can do is to call `$next()` to invoke the next middleware
when its function was accomplished. Failing to call this method results in the interruption of the framework's
operation (of course the final middleware will still be executed)!

But what to do if you want to pass a manipulated request or response to the next middleware? Then, you should call
`$next($request, $response)`. This way, the following middleware will receive the modified request or response.
Calling `$next(null, $response)` will pass the original request and the possibly changed response to the next
middleware!

If you need more sophistication, you can use an invokable class as a middleware too. And you can even implement
`MiddlewareInterface` to gain access to all the capabilities of the framework! For example let's create an
authentication middleware implementing the aforementioned `MiddlewareInterface`:

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
        $this->harmony = $harmony;
        $this->apiKey = $apiKey;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        if ($request->getHeader("x-api-key") !== [$this->apiKey]) {
            return $response->withStatusCode(402);
        }
        
         $next();
    }
}
```

then

```php
$harmony->addMiddleware("authentication", new AuthenticationMiddleware("123"));
```

As you can see, the constructor receives the API Key, while the `__invoke()` method is responsible for performing the
authentication.

Again: the single most important thing a middleware can do is to call `$next()` to invoke the next middleware
when its function was accomplished. Failing to call this method results in the interruption of the framework's
operation (of course the final middleware will still be executed)! That's why we only invoke `$next()` in this example
when the authentication was successful.

Very important to notice that when authentication is unsuccessful, no other middlewares will be executed (as `$next()`
is not called), so only the final middleware will be invoked afterwards. As you want to pass a modified response with
status code 412 to the final middleware, you must return the response (as seen in the prior example) in order to inform
the framework from the changed response. Note that you can't do the same with requests, it is only possible with
responses. 

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Máté Kocsis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/woohoolabs/harmony.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/woohoolabs/harmony/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/woohoolabs/harmony.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/woohoolabs/harmony.svg
[ico-downloads]: https://img.shields.io/packagist/dt/woohoolabs/harmony.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/harmony
[link-travis]: https://travis-ci.org/woohoolabs/harmony
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/harmony/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/harmony
[link-downloads]: https://packagist.org/packages/woohoolabs/harmony
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
