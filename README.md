# Woohoo Labs. Harmony

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1aa0881f-c185-4be8-b1e8-4b6378f4c5b2/big.png)](https://insight.sensiolabs.com/projects/1aa0881f-c185-4be8-b1e8-4b6378f4c5b2)

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
in order to speed up development in its initial phase.

To sum up, Woohoo Labs. Harmony is the most effective for teams with a solid understanding of software development.
Its flexibility is the most advantageous if your application is a long-term, strategic project or you need
sophisticated tools like IoC Containers.

## Features

- Extreme flexibility through middlewares
- Totally object-oriented workflow
- Full control over HTTP requests and responses
- Support for serializing/deserializing different media types (JSON, XML, YAML)
- Support for any IoC Containers

## Concepts

1. Middleware
2. Router
3. Container
4. Serializer/Deserializer
5. Request
6. Response

#### Middleware


#### Router

Basically, the router tells you which handler (let it be a class method or an anomymous function) is in charge
of handling the request coming to a specific URI. The default router used by Woohoo Labs. Harmony is FastRouter, the
library of [Nikita Popov](https://twitter.com/nikita_ppv), because of its elegance and performance. You can read
more about it [clicking here](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).
Of course if you weren't satisfied with it, you can change it anytime with a minimal amount of work.

| Middleware name     | Description                           |
| ------------------- | ------------------------------------- |
| `RouterMiddleware`  | Wrapper around the Fast Route library |

#### Container

For Woohoo Labs. Harmony, the container is only a class which is capable to instantiate any handlers
if you provide them their fully qualified class name (in fact, containers are called IoC Containers and
they are much more then described above). The built-in container is a really naive one: it uses pure PHP
reflection to create a handler object. If you want to use a more clever IoC Container which is
[Container-Interop compliant](https://github.com/container-interop/container-interop) (like PHP-DI), all
you have to do is to pass its reference to the framework.

If your chosen container doesn't support this interface (like Pimple or Dice), you only have to write an
adapter for them implementing the common interface.

| Implementation      | Description                                      |
| ------------------- | ------------------------------------------------ |
| `BasicContainer `   | Instantiates the handler classes with reflection |

#### Serializers/Deserializers

A deserializer automatically handles string to array/object conversion from data formats like JSON, XML or
YML. They may be needed in the beginning of the a request-response lifecycle when the framework receives a
request and the contained data (the body) should be converted into an array or object.

A serializer automatically handles array/object to string conversion into data formats like JSON, XML or YML.
They may be needed in the end of the request-response lifecycle when you want to send your data as a response.

| Implementation       | Supported formats | Description                                                 |
| -------------------- | ----------------- | ----------------------------------------------------------- |
| `JmsSerializer`      | JSON, XML, YML    | A wrapper around the JmsSerializer library                  |
| `FormSerializer`     | form data         | Serializes/deserializes form data                           |
| `JsonSerializer`     | JSON              | Serializes/deserializes with pure PHP functions             |
| `CompoundSerializer` | any               | Supports choosing a serializer/deserializer for each format |
| `NaiveSerializer`    | -                 | Just transmits the received data                            |

#### Request

A request object is the Object-Oriented representation of an HTTP request. For this purpose,
Symfony's HTTP Foundation is used by a wrapper class which implements the ``RequestInterface``.

| Implementation       | Description                                            |
| -------------------- | ------------------------------------------------------ |
| `FoundationRequest`  | A wrapper around the Symfony Foundation request class  |

#### Response

The response is the Object-Oriented representation of an HTTP response. It is capable to send itself
into the ether. For this purpose, Symfony's HTTP Foundation is used by default by a wrapper class.

| Implementation        | Description                                            |
| --------------------- | ------------------------------------------------------ |
| `FoundationResponse`  | A wrapper around the Symfony Foundation response class |

#### Event Dispatcher

An event dispatcher emits events during each phase of the control flow. These events can be listened
by listeners (they are callables or class methods) after you had them subscribed. Read more about it
in the Advanced Usage section.

| Implementation           | Description                                       |
| ------------------------ | ------------------------------------------------- |
| `SymfonyEventDispatcher` | A wrapper around Symfony Event Dispatcher         |

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
$ composer require symfony/http-foundation
$ composer require jms/serializer
```

#### Autoload classes in your bootstrap:

```php
require "vendor/autoload.php"
```

## Basic Usage

#### Define some routes:

```php
$router = function(FastRoute\RouteCollector $r) {
    $r->addRoute("GET", "/me", function (RequestInterface $request, ResponseInterface $response) {
        $response->setContent("Welcome to the real world!");
    });
    $r->addRoute("GET", "/users", ["App\\Controllers\\UserController", "getUsers"]);
    $r->addRoute("POST", "/users/{id}", ["App\\Controllers\\UserController", "updateUser"]);
});
'''

You can define either a class or a callback handler for each route. A route consists of an HTTP verb and
a URI. By convention, start the URI with a _/_. It can also contain curly brace templates if you stay with
the default implementation.

**Reminder**: As of PHP 5.5, you are able to use the [::class keyword](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class)
to resolve class names:

```php
$r->addRoute("GET", "/users", [\App\Controllers\UserController::class, "getUsers"]);
```

#### Define the handlers for the routes:

There are two important things to notice here: each handler receives a ``RequestInterface`` and a ``ResponseInterface``
object and they are expected to manipulate the latter.

```php
namespace App\Controllers;

use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

class UserController
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function getUsers(RequestInterface $request, ResponseInterface $response)
    {
        $users= ["Steve", "Arnie", "Jason", "Bud"];
        $response->setContent($users);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
     public function updateUser(RequestInterface $request, ResponseInterface $response)
     {
        $userId= $request->getUriParameter("id");
        $userData= $request->getBodyAsArray();
        $response->setContent(array_merge($userData, ["id" => $userId]));
     }
}
```

However you don't have to worry that your handlers become tightly coupled to HTTP. Just read
[this fantastic post](https://igor.io/2013/02/03/http-foundation-value.html) from
[Igor Wiedler](https://twitter.com/igorwhiletrue).

#### Finally, launch the framework:

```php
$harmony= Harmony::build()
    ->addMiddleware(new InitializerMiddleware())
    ->addMiddleware(new RouterMiddleware($router)
    ->addMiddleware(new DispatcherMiddleware())
    ->addMiddleware(new ResponderMiddleware())
    ->live();
```

## Advanced Usage

#### Redefining Default Components

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect
of the framework. That's why you can customize almost everything with minimal effort.

The following example shows how to swap the ``BasicContainer`` with the PHP-DI Container then how to use the
serializer and deserializer of the famous JMS library instead of the default one.

```php
$container= new \DI\Container();
$harmony->setContainer($container);

$serializer= new JmsSerializer($config);
$harmony->setSerializer($serializer);
```

#### Hooks

Hooking enables you to get the control before and/or after dispatching occurs. Note that it is only
available for class handlers! If you specify a ``preHook()`` method in your handler class then it
will be automatically invoked before the handler method. The same way, if you specify a ``postHook()``
method then it will be called after the original handler method has been invoked. Important to note
that the hooking methods must also expect a ``Request`` and a ``Response`` object as their only arguments
and they aren't required to provide any return value (just like regular handler methods).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/harmony/blob/master/LICENSE.md)
for more information.
