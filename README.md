# Woohoo Labs. Harmony

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

**Woohoo Labs. Harmony is a flexible micro-framework developed for PHP applications.**

Our aim was to create an invisible, easily extensible, but most important of all, extremely flexible framework for your
long-term, strategic applications. We wanted to give you total control via
[PSR-7](http://www.php-fig.org/psr/psr-7/) and
[PSR-11 (former Container-Interop)](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md).

## Table of Contents

* [Introduction](#introduction)
* [Install](#install)
* [Basic Usage](#basic-usage)
* [Advanced Usage](#advanced-usage)
* [Examples](#examples)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Credits](#credits)
* [License](#license)

## Introduction

### Rationale

This blog post explains the idea best why Harmony was born: http://www.catonmat.net/blog/frameworks-dont-make-sense/ 

### Features

- Extreme flexibility through middleware
- High performance due to a simple and clean design
- Full control over HTTP messages via PSR-7
- Support for many DI Containers via PSR-11 (formerly known as Container-Interop)

### Why Harmony?

There are a lot of very similar middleware dispatcher libraries out there, like
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Slim Framework 3](http://www.slimframework.com/docs/concepts/middleware.html) or
[Relay](http://relayphp.com/). You might ask yourself, what is the purpose of yet another library with the same
functionality?

We believe Harmony offers two key features which justify its existence:

- It is the most simple library of all. Although simplicity is subjective, one thing is certain: Harmony offers the
bare minimum functionality of what a library like this would need. That's why Harmony itself fits into a single class of 200 lines.

- As of version 3, Harmony natively supports the concept of [Conditions](#defining-conditions) which is a unique
feature for middleware dispatchers. This eases dealing with a major weakness of the middleware-oriented approach,
which is the ability to invoke middleware conditionally.

### Use cases

Certainly, Harmony won't suit the needs of all projects and teams: this framework works best for advanced teams.
Less experienced teams should probably choose a less lenient framework with more features, in order to speed up
development in its initial phase. Harmony's flexibility is the most advantageous when your software is a long-term,
strategic project. That's why legacy applications can also benefit from Harmony, because it makes gradual refactoring
easier.

### Concepts

Woohoo Labs. Harmony is built upon two main concepts: middleware, which promote separation of concerns, and common
interfaces, making it possible to rely on loosely coupled components.

By using middleware, you can easily take hands on the course of action of the request-response lifecycle: you can
authenticate before routing, do some logging after the response has been sent, or you can even dispatch multiple
routes in one request. This all can be achieved because everything in Harmony is a middleware, so the framework
itself only consists of cc. 200 lines of code. This is why there is no framework-wide configuration, only middleware
can be configured. What you do with Harmony depends only on your imagination and needs.

But middleware must work in cooperation (the router and the dispatcher are particularly tightly coupled to each other).
That's why it is also important to provide common interfaces for the distinct components of the framework.

Naturally, we decided to use [PSR-7](http://www.php-fig.org/psr/psr-7/) for modelling the HTTP request and response.
In order to facilitate the usage of different DI Containers, we adapted
[PSR-11 (former Container-Interop)](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md) which is supported
by various containers out of the box.

### Middleware interface design

Woohoo Labs. Harmony's middleware interface design is based on the "request, response, next" style advocated by such
prominent developers as [Matthew Weier O'Phinney](https://mwop.net/) (you can read more on the topic
[in his blog post](https://mwop.net/blog/2015-01-08-on-http-middleware-and-psr-7.html)). This style - often called
"double pass" or "functional" style - is the current de-facto standard among PHP middleware dispatchers, and is also
supported by major vendors like [Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Slim Framework 3](http://www.slimframework.com/) and [Relay](http://relayphp.com/).

If you want to learn about the specifics of this style, please refer to the following introductions which describe the
very concept:

- [Middleware logic in Relay PHP](http://relayphp.com/#middleware-logic) 
- [How does middleware work in Slim 3?](http://www.slimframework.com/docs/concepts/middleware.html#how-does-middleware-work)

### Additional middleware

Besides the built-in middleware and the ones for the compatible middleware dispatcher libraries, you can find various
other third party middleware available for Harmony:

- [Woohoo Labs. Yin-Middleware](https://github.com/woohoolabs/yin-middleware): A bunch of middleware to integrate
[Woohoo Labs. Yin](https://github.com/woohoolabs/harmony) - the elegant JSON API framework - into Harmony.
- [PSR-7 Middlewares](https://github.com/oscarotero/psr7-middlewares): A collection of PSR-7 middleware from Oscar Otero
- [MiniUrl](https://github.com/mtymek/MiniUrl): A simple URL shortener, which can be used as a free, open-source
replacement for bit.ly's core functionality: creating short links and redirecting users.

## Install

The steps of this process are quite straightforward. The only thing you need is [Composer](http://getcomposer.org).

### Require a PSR-7 implementation:

Because Harmony requires a PSR-7 implementation (a package which provides the `http-message-implementation` virtual
package), you must install one first. You may use Zend Diactoros or any other library of preference:

```bash
$ composer require zendframework/zend-diactoros:^2.3.0
```

### Add Harmony to your composer.json:

To install the latest version of this library, run the command below:

```bash
$ composer require woohoolabs/harmony
```

Harmony 3.1+ needs PHP 7.0 at least, but you may use Harmony 3.0.1 for PHP 5.5 and 5.6.

### Require the necessary dependencies:

If you want to use the default middleware stack then you have to require the following dependencies too:

```bash
$ composer require nikic/fast-route:^1.0.0
```

## Basic Usage

### Define your endpoints:

The following example applies only if you use the
[default dispatcher middleware](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/DispatcherMiddleware.php).
There are two important things to note here: first, each endpoint receives a `Psr\Http\Message\ServerRequestInterface`
and a `Psr\Http\Message\ResponseInterface` object and they are expected to manipulate and return the latter. Secondly,
you are not forced to only use classes for the endpoints, it is possible to define other callables too (see below in
the routing section).

```php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response)
    {
        $users = ["Steve", "Arnie", "Jason", "Bud"];
        $response->getBody()->write(json_encode($users));
        
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
     public function updateUser(ServerRequestInterface $request, ResponseInterface $response)
     {
        $userId = $request->getAttribute("id");
        $userData = $request->getParsedBody();

        // Updating user...
        
        return $response;
     }
}
```

### Define your routes:

The following example applies only if you use the
[default router middleware](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/FastRouteMiddleware.php)
which is based on [FastRoute](https://github.com/nikic/FastRoute), the library of Nikita Popov. We chose to use it by
default because of its performance and simplicity. You can read more about it
[in Nikita's blog](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).

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

### Finally, launch the app:

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
    ->addMiddleware(new DiactorosResponderMiddleware(new SapiEmitter()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware());

$harmony();
```

You have to register all the prior middleware in order for the framework to function properly:
- `DiactorosResponderMiddleware` sends the response to the ether via [Zend Diactoros](https://github.com/zendframework/zend-diactoros)
- `FastRouteMiddleware` takes care of routing (`$router`  was configured in the previous step)
- `DispatcherMiddleware` dispatches a controller which belongs to the request's current route

Note that there is a second optional argument of `Harmony::addMiddleware()` with which you can define the ID of a
middleware (doing so is necessary if you want to call `Harmony::getMiddleware()` somewhere in your code).

Of course, it is completely up to you how you add additional middleware or how you replace them with your own
implementations. When you'd like to go live, just call `$harmony()`!

## Advanced Usage

### Using invokable controllers

Most of the time, you will define your route handlers (~controller actions) as regular callables as was shown in the
section about the default router:

```php
$r->addRoute("GET", "/users/me", [\App\Controllers\UserController::class, "getMe"]);
```

But nowadays, there is an increasing popularity of controllers containing only one action. To do so, it is a general
practice to implement the `__invoke()` magic method. In former versions of Harmony, if you wanted to apply this pattern,
you had to define the example route above the following way (at least if you used the default router and dispatcher):
  
```php
$r->addRoute("GET", "/users/me", [\App\Controllers\GetMe::class, "__invoke"]);
```

As of Harmony 2.1.0, your route definition can be simplified to:

```php
$r->addRoute("GET", "/users/me", \App\Controllers\GetMe::class);
```

Note: If you use a different router or dispatcher than the default ones, please make sure if the feature is available
for you.

If you are interested in how you could benefit from invokable controllers in the context of the Action-Domain-Responder
pattern, you can find an insightful description in [Paul M. Jones' blog post](http://paul-m-jones.com/archives/6006).

### Using your favourite DI Container with Harmony

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect of the framework.
That's why you can use any DI Container you want.

For this purpose, we chose to build upon [PSR-11](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md) - the most widespread common interface for DI Containers - in the built-in `DispatcherMiddleware`.

It's also important to know that the `DispatcherMiddleware` uses the `BasicContainer` by default. It's nothing more
than a very silly DIC which tries to create objects based on their class name (so calling
`$basicContainer->get(Foo::class)` would create a new `Foo` instance).

But if you provide an argument to the middleware's constructor, you can use your favourite PSR-11 compliant
DI Container too. Let's have a look at an example where one would like to swap `BasicContainer` with
[Zen](https://github.com/woohoolabs/zen):

```php
$container = new MyContainer();
$harmony->addMiddleware(new DispatcherMiddleware($container));
```

### Creating custom middleware

In order to avoid some initial confusion, please ensure that you know the basics and gotchas of the "request, response,
next" middleware interface design before creating your first own middleware. You can have a look at
[the section about this topic](#middleware-interface-design).

It's not a big deal to add a new middleware to your stack. For a basic scenario, you can use anonymous functions. Let's
say you would like to log all the requests:

```php
$middleware = function (ServerRequestInterace $request, ResponseInterface $response, callable $next) use ($logger) {
    // Perform logging before handling the request
    $logger->logInfo("Request needs to be handled");
    
    // Invoking the remaining middleware
    $response = $next();
    
    // Perform logging after the request has been handled
    $logger->logInfo("Request was successfuly handled");

    // Return to the previous middleware
    return $response;
}
```

And then you have to attach the middleware to Harmony:

```php
$harmony->addMiddleware($middleware);
```

What to do if you want to pass a manipulated request or response to the next middleware? Then, you should call
`$next($request, $response)`. This way, the following middleware will receive the modified request or response.
Calling `$next(null, $response)` will pass the original request and the possibly changed response to the next
middleware!

If you need more sophistication, you can use a `Closure` as a middleware too. Let's create an authentication middleware
to demonstrate this feature:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getHeader("x-api-key") !== [$this->apiKey]) {
            return $response->withStatusCode(401);
        }
        
        return $next();
    }
}
```

Then 

```php
$harmony->addMiddleware(new AuthenticationMiddleware("123"));
```

As you can see, the constructor receives the API Key, while the `__invoke()` method is responsible for performing
authentication.

Instead of `callable`, you can also typehint the `$next` argument against `Harmony` according to
[`HarmonyMiddlewareInterface`](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/HarmonyMiddlewareInterface.php).
By implementing this interface, you can use some specific features of Harmony (like `Harmony::getMiddleware()`) but lose
the ability to reuse your middleware in other frameworks.

### Defining conditions

Non-trivial applications often need some kind of branching during the execution of their middleware pipeline. A possible
use-case is when they want to perform authentication only for some of their endpoints or when they want to check for a
CSRF token if the request method is `POST`. With Harmony 2 branching was also easy to handle, but Harmony 3 helps you to
optimize the performance of conditional logic in your middleware.

Let's revisit our authentication middleware example from the last section! This time, we only want to authenticate
endpoints below the `/user` path. In Harmony 2, we had to achieve it with something like this:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware
{
    /**
     * @var string
     */
    protected $securedPath;

    /**
     * @var MyAuthenticatorInterface
     */
    protected $authenticator;

    /**
     * @param string $securedPath
     * @param MyAuthenticatorInterface $authenticator
     */
    public function __construct($securedPath, MyAuthenticatorInterface $authenticator)
    {
        $this->securedPath = $securedPath;
        $this->authenticator = $authenticator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
         // Invoke the next middleware and cancel authentication if the current URL is for a public endpoint
        if (substr($request->getUri()->getPath(), 0, strlen($this->securedPath)) !== $this->securedPath) {
            return $next();
        }
    
        // Return Error 401 if authentication fails
        if ($this->authenticator->authenticate($request) === false) {
            return $response->withStatusCode(401);
        }
        
        // Invoke the next middleware otherwise
        return $next();
    }
}
```

And finally attach the middleware to Harmony:

```php
$harmony->addMiddleware(new AuthenticationMiddleware("/user", new ApiKeyAuthenticator("123")));
```

You only had to check the current URI inside the middleware and the problem was solved. The downside of doing this is
that `AuthenticationMiddleware` and all its dependencies are instantiated for each request even though authentication
is not needed at all! This can be a major inconvenience if you depend on a big object graph.

In Harmony 3, however, you are able to use conditions in order to optimize the number of objects created. In this case
you can utilize the built-in `PathPrefixCondition`. You only have to attach it to Harmony:

```php
$harmony->addCondition(
    new PathPrefixCondition(["/users"]),
    function (Harmony $harmony) {
        $harmony->addMiddleware(new AuthenticationMiddleware("/user", new ApiKeyAuthenticator("123")));
    }
);
```

This way, `AuthenticationMiddleware` will only be instantiated when `PathPrefixCondition` evaluates to `true`
(when the current URI path starts with `/users`). Furthermore, you are able to attach more middleware to Harmony in
the anonymous function. They will be executed together, as if they were part of a containing middleware.

Here is a complete list of the built-in conditions:

- [`ExactPathCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/ExactPathCondition.php):
Evaluates to true if the current URI path exactly matches any of the allowed paths.

- [`PathPrefixCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/PathPrefixCondition.php):
Evaluates to true if the current URI path starts with any of the allowed path prefixes.

- [`HttpMethodCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/HttpMethodCondition.php):
Evaluates to true if the current HTTP method matches any of the allowed HTTP methods.

## Examples

Have a look at the [examples directory](https://github.com/woohoolabs/harmony/blob/master/examples/) for a really basic
application structure. Don't forget to run `composer install` first in Harmony's root directory if you want to try
it out!

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

Harmony has a PHPUnit test suite. To run the tests, run the following command from the project folder
after you have copied phpunit.xml.dist to phpunit.xml:

``` bash
$ phpunit
```

Additionally, you may run `docker-compose up` to execute the tests.

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
[ico-gitter]: https://badges.gitter.im/woohoolabs/harmony.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/harmony
[link-travis]: https://travis-ci.org/woohoolabs/harmony
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/harmony/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/harmony
[link-downloads]: https://packagist.org/packages/woohoolabs/harmony
[link-gitter]: https://gitter.im/woohoolabs/harmony?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
