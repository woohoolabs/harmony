# Woohoo Labs. Harmony

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

**Woohoo Labs. Harmony is a PSR-15 compatible middleware dispatcher.**

Harmony was born to be a totally flexible and almost invisible framework for your application. That's why Harmony
supports the [PSR-7](https://www.php-fig.org/psr/psr-7/), [PSR-11](https://www.php-fig.org/psr/psr-11/), as well as the [PSR-15](https://www.php-fig.org/psr/psr-15/) standards.

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
* [Support](#support)
* [Credits](#credits)
* [License](#license)

## Introduction

### Rationale

This blog post explains the idea best why Harmony was started back in 2014: http://www.catonmat.net/blog/frameworks-dont-make-sense/ 

### Features

- High performance due to Harmony's simplicity
- High flexibility thanks to the vast middleware ecosystem of PSR-15
- Full control over HTTP messages via PSR-7
- Support for many DI Containers via PSR-11 (formerly known as Container-Interop)

### Why Harmony?

There are a lot of very similar middleware dispatcher libraries out there, like
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Slim Framework 3](https://www.slimframework.com/docs/concepts/middleware.html) or
[Relay](http://relayphp.com/). You might ask yourself, what is the purpose of yet another library with the same
functionality?

We believe Harmony offers two key features which justify its existence:

- It is the most simple library of all. Although simplicity is subjective, one thing is certain: Harmony offers the
bare minimum functionality of what a library like this would need. That's why Harmony itself fits into a single class of ~140 lines.

- As of version 3, Harmony natively supports the concept of [Conditions](#defining-conditions) which is a rare
feature for middleware dispatchers. This eases dealing with a major weakness of the middleware-oriented approach,
namely, the ability to invoke middleware conditionally.

### Use cases

Certainly, Harmony won't suit the needs of all projects and teams: this framework works best for an experienced team with a
longer term project. Less experienced teams - especially if they have short deadlines - should probably choose a framework
with more features - working out-of-the box - in order to speed up development in its initial phase. Harmony's flexibility
is the most advantageous when your software should be supported for a longer time.

### Concepts

Woohoo Labs. Harmony is built upon two main concepts: middleware, which promote separation of concerns, and common
interfaces, making it possible to rely on loosely coupled components.

By using middleware, you can easily take hands on the course of action of the request-response lifecycle: you can
authenticate before routing, do some logging after the response has been sent, or you can even dispatch multiple
routes in one request. This all can be achieved because everything in Harmony is a middleware, so the framework
itself only consists of cc. 140 lines of code. This is why there is no framework-wide configuration, only middleware
can be configured. What you do with Harmony depends only on your imagination and needs.

But middleware must work in cooperation (the router and the dispatcher are particularly tightly coupled to each other).
That's why it is also important to provide common interfaces for the distinct components of the framework.

Naturally, we decided to use [PSR-7](https://www.php-fig.org/psr/psr-7/)
for modelling the HTTP request and response. In order to facilitate the usage of different DI Containers, we adapted
[PSR-11 (former Container-Interop)](https://www.php-fig.org/psr/psr-11/)
which is supported by various containers out of the box.

### Middleware interface design

Woohoo Labs. Harmony's middleware interface design is based on the the
[PSR-15](https://www.php-fig.org/psr/psr-15/) de-facto standard.

If you want to learn about the specifics of this style, please refer to the following articles which describe the
very concept:

- [PSR-15 Meta Document](https://www.php-fig.org/psr/psr-15/meta/) 
- [PSR-15](https://mwop.net/blog/2018-01-23-psr-15.html) 

## Install

The only thing you need before getting started is [Composer](https://getcomposer.org).

### Install a PSR-7 implementation:

Because Harmony requires a PSR-7 implementation (a package which provides the `psr/http-message-implementation` virtual
package), you must install one first. You may use [Zend Diactoros](https://github.com/zendframework/zend-diactoros) or
any other library of your preference:

```bash
$ composer require zendframework/zend-diactoros:^2.0.0
```

### Install Harmony:

To install the latest version of this library, run the command below:

```bash
$ composer require woohoolabs/harmony
```

> Note: The tests and examples won't be downloaded by default. You have to use `composer require woohoolabs/harmony --prefer-source`
or clone the repository if you need them.

Harmony 6 requires PHP 7.1 at least, but you may use Harmony 4.0.0 for PHP 7.0.

### Install the optional dependencies:

If you want to use the default middleware stack then you have to require the following dependency too:

```bash
$ composer require nikic/fast-route:^1.0.0 # FastRouteMiddleware needs it
$ composer require zendframework/zend-httphandlerrunner:^1.0.0 # HttpHandlerRunnerMiddleware needs it
```

## Basic Usage

### Define your endpoints:

The following example applies only if you use the
[default dispatcher middleware](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/DispatcherMiddleware.php).
There are two important things to note here: first, each dispatchable endpoint receives a `Psr\Http\Message\ServerRequestInterface`
and a `Psr\Http\Message\ResponseInterface` object as parameter and the latter is expected to be manipulated and returned. Secondly,
you can not only use class methods as endpoints, it is possible to define other callables too (see below in the routing section).

```php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $users = ["Steve", "Arnie", "Jason", "Bud"];
        $response->getBody()->write(json_encode($users));
        
        return $response;
    }

    public function updateUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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
[in Nikita's blog](https://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).

Let's add the routes for the aforementioned endpoints to FastRoute:

```php
use App\Controllers\UserController;

$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    // An anonymous function endpoint
    $r->addRoute("GET", "/me", function (ServerRequestInterface $request, ResponseInterface $response) {
            // ...
    });

    // Class method endpoints
    $r->addRoute("GET", "/users", [UserController::class, "getUsers"]);
    $r->addRoute("POST", "/users/{id}", [UserController::class, "updateUser"]);
});
```

### Finally, launch the app:

```php
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

$harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());
$harmony
    ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware());

$harmony();
```

You have to register all the prior middleware in order for the framework to function properly:
- `HttpHandlerRunnerMiddleware` sends the response to the ether via [zend-httphandlerrunner](https://github.com/zendframework/zend-httphandlerrunner)
- `FastRouteMiddleware` takes care of routing (`$router`  was configured in the previous step)
- `DispatcherMiddleware` dispatches a controller which belongs to the request's current route

Note that there is a second optional argument of `Harmony::addMiddleware()` with which you can define the ID of a
middleware (doing so is necessary if you want to call `Harmony::getMiddleware()` somewhere in your code).

Of course, it is completely up to you how you add additional middleware or how you replace them with your own
implementations. When you'd like to go live, call `$harmony()`!

## Advanced Usage

### Using invokable class controllers

Most of the time, you will define your endpoints (~controller actions) as regular callables as was shown in the
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

For this purpose, we chose to build upon [PSR-11](https://www.php-fig.org/psr/psr-11/) - the most widespread common
interface for DI Containers - in the built-in `DispatcherMiddleware`.

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

New middleware also has to implement the [PSR-15](https://www.php-fig.org/psr/psr-15/) `MiddlewareInterface`. Let's
see an example:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class LoggerMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Perform logging before handling the request
        $logger->info("Request needs to be handled");
        
        // Invoking the remaining middleware
        $response = $handler->handle($request);
        
        // Perform logging after the request has been handled
        $logger->info("Request was successfuly handled");
    
        // Return the response
        return $response;
    }
}
```

And when you are ready, attach it to Harmony:

```php
$harmony->addMiddleware(new LoggerMiddleware(new Logger()));
```

What to do if you do not want to invoke the remaining middleware (possibly because of an error)? Then you can simply
manipulate and return a response whose "prototype" was passed to the middleware in its constructor. You can see this
in action in the following example:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $apiKey;
    
    /**
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(string $apiKey, ResponseInterface $errorResponsePrototype)
    {
        $this->apiKey = $apiKey;
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Return Error 401 "Unauthorized" if the provided API key doesn't match the expected one
        if ($request->getHeader("x-api-key") !== [$this->apiKey]) {
            return $this->errorResponsePrototype->withStatusCode(401);
        }
        
        // Invoke the remaining middleware if authentication was successful
        return $handler->handle($request);
    }
}
```

Then 

```php
$harmony->addMiddleware(new AuthenticationMiddleware("123"), new Response());
```

### Defining conditions

Non-trivial applications often need some kind of branching during the execution of their middleware pipeline. A possible
use-case is when they want to perform authentication only for some of their endpoints or when they want to check for a
CSRF token if the request method is `POST`. With Harmony 2 branching was also easy to handle, but Harmony 3+ helps you to
optimize the performance of conditional logic in your middleware.

Let's revisit our authentication middleware example from the last section! This time, we only want to authenticate
endpoints below the `/users` path. In Harmony 2, we had to achieve it with something like this:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
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
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(string $securedPath, MyAuthenticatorInterface $authenticator, ResponseInterface $errorResponsePrototype)
    {
        $this->securedPath = $securedPath;
        $this->authenticator = $authenticator;
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        // Invoke the remaining middleware and cancel authentication if the current URL is for a public endpoint
        if (substr($request->getUri()->getPath(), 0, strlen($this->securedPath)) !== $this->securedPath) {
            return $handler->handle($request);
        }
    
        // Return Error 401 "Unauthorized" if authentication fails
        if ($this->authenticator->authenticate($request) === false) {
            return $this->errorResponsePrototype->withStatusCode(401);
        }
        
        // Invoke the remaining middleware otherwise
        return $handler->handle($request);
    }
}
```

And finally attach the middleware to Harmony:

```php
$harmony->addMiddleware(new AuthenticationMiddleware("/users", new ApiKeyAuthenticator("123"), new Response()));
```

You had to check the current URI inside the middleware and the problem was solved. The downside of doing this is
that `AuthenticationMiddleware` and all its dependencies are instantiated for each request even though authentication
is not needed at all! This can be a major inconvenience if you depend on a big object graph.

In Harmony 3+, however, you are able to use conditions in order to optimize the number of invoked middleware. In this case
you can utilize the built-in `PathPrefixCondition`. You only have to attach it to Harmony:

```php
$harmony->addCondition(
    new PathPrefixCondition(["/users"]),
    function (Harmony $harmony) {
        $harmony->addMiddleware(new AuthenticationMiddleware(new ApiKeyAuthenticator("123")));
    }
);
```

This way, `AuthenticationMiddleware` will only be instantiated when `PathPrefixCondition` evaluates to `true`
(when the current URI path starts with `/users`). Furthermore, you are able to attach more middleware to Harmony in
the anonymous function. They will be executed together, as if they were part of a containing middleware.

Here is the complete list of the built-in conditions:

- [`ExactPathCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/ExactPathCondition.php):
Evaluates to true if the current URI path exactly matches any of the allowed paths.

- [`PathPrefixCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/PathPrefixCondition.php):
Evaluates to true if the current URI path starts with any of the allowed path prefixes.

- [`HttpMethodCondition`](https://github.com/woohoolabs/harmony/blob/master/src/Condition/HttpMethodCondition.php):
Evaluates to true if the current HTTP method matches any of the allowed HTTP methods.

## Examples

If you want to see a really basic application structure in action, have a look at the
[examples](https://github.com/woohoolabs/yin/tree/master/examples). If `docker-compose` and `make` is available on your system,
then run the following commands in order to try out the example app:

```bash
cp .env.dist .env      # You can now edit the settings in the .env file
make composer-install  # Install the Composer dependencies
make up                # Start the webserver
```

> If you don't have `make`, you can copy the underlying commands, and directly use them in your terminal. 

Finally, the example app is available at `localhost:8080`. 

> If you modified the `.env` file, you should change the port to the value of the `HOST_WEB_PORT` variable.

Example URIs:
- `GET /books/1`
- `GET /users/1`
- `GET /me`

When you finished your work, simply stop the webserver:

```bash
make down
```

If the prerequisites are not available for you, you have to set up a webserver on your host, install PHP, as
well as the dependencies via `Composer`.

## Versioning

This library follows [SemVer v2.0.0](https://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

Harmony has a PHPUnit test suite. To run the tests, run the following command from the project folder:

``` bash
$ phpunit
```

Additionally, you may run `docker-compose up` or `make test` to execute the tests.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Support

Please see [SUPPORT](SUPPORT.md) for details.

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
