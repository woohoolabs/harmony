# Woohoo Labs. API Framework

**Woohoo Labs. API Framework is a flexible micro-framework developed for quality API-s.**

Our aim was to create an invisible, easily extensible, but first of all, extremely flexible framework for API-s.
We wanted to give you total control while providing a clean interface to communicate with.

## "Long live is the framework"

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

We created API Framework to remedy this issue.

### A Case Study

Let's look at a realistic scenario to understand the problem better: you have an MVC framework which has its
integrated IoC Container and ORM layer. You are pretty happy with these tools. Some weeks later, you discover
that you would cache your database queries, but the interior ORM doesn't support it.

Next time you realize that you no longer need an MVC framework as you shifted away from this paradigm and
started adopting something else - let's say Domain Driven Design. Furthermore you want to reorganize your
project into bundles. Sadly, your framework neither supports changing the directory structure (it can
easily happen because of autoloading which came from the old times) nor calling your Controllers as Services
(you wanted to rename them because by now, your Controllers are rather Application Services).

Later on you will find yourself needing a much more sophisticated or performant IoC Container than the one
you have. Maybe you need Dice or PHP-DI. But the question emerges: how would you swap the core of the
framework which instantiates all your controllers? There is no way to do that. You can't overcome the
situation this time too (without hacking on the code).

### Lessons Learned

The first situation had a grasp on that there are full stack and micro frameworks with a
long list of features and components, but in our opinion, a good framework should conform to the
[Single Responsibility Principle](http://blog.8thlight.com/uncle-bob/2014/05/08/SingleReponsibilityPrinciple.html):
they shouldn't provide more than that the user actually needs. For example if you won't make use of the
Eloquent ORM in Laravel, why placing it into the project? In the most cases it's better to just
create separate components and let the user decide which libraries they prefer.

The second lesson intended to teach you that it was a big mistake to leave everything as-is (e.g.:
class names, directory structure) despite the fact that the paradigm you use has been changed. When
developing quality software, naming must be in focus. According to DDD initiatives like Matthias
Verraes, finding expressive and consistent names for things is inevitable! As everything varies over
time, you mustn't let your framework restrict any changes or define the directory structure instead
of you. The advantage of this principle is well summarized by Anna-Julie Scheuermann:

> Once I'm happy with the names, I'm usually happy with the design.

Finally in the third story, we wanted to point out that changing routing or class instantiation in a
framework can be hard on-the-fly. But why not think about these situations in advance? For example,
regarding IoC Containers a (not yet officially accepted) common interface already exists, so it makes
any changes with them as easy as ABC!

As a general suggestion, when your project is huge make sure that you can easily swap things in and out
from every part of your framework too. That's why we designed API Framework so that almost every piece
of it can be changed with minimal effort and with minimal impact on your project.

## Use Cases of Woohoo Labs. API Framework

Certainly, API Framework won't suit the needs of all projects and teams. Firstly, this framework works best
for API-s as every single detail of the framework was brought to live to support these efforts. Furthermore,
less experienced teams should probably choose a less lenient framework with more features in order to speed
up development in the initial phase.

To sum up, Woohoo Labs. API Framework is most effective for teams with a solid understanding of API development.
Its flexibility is more advantageous if you need sophisticated tools like IoC Containers or API documentation
tools like Swagger.

## Features of Woohoo Labs. API Framework

- Extreme flexibility
- Totally Object-Oriented workflow
- Full control over HTTP requests and responses
- Support for different media types (JSON, YML, XML)
- Support for any IoC Containers

## Concepts of Woohoo Labs. API Framework

1. Configuration
2. Router
3. Discoverer
4. Container
5. Deserializer
6. Serializer
7. Request
8. Response
9. Responder

### Configuration

When you instantiate the framework you have to provide your configuration options including the current environment
of your application (DEV or PROD), caching etc. This configuration will be a POPO (Plain old PHP Object).

### Router

Basically, the router tells you which handler (let it be a class method or an anomymous function) is in charge
of handling the request coming to a specific URI. The default router used by Woohoo Labs. API Framework is the
library of [Nikita Popov](https://twitter.com/nikita_ppv), because of its elegance and performance. You can read
more about it [clicking here](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).
Of course if you weren't satisfied with it, you can change it anytime with a minimal amount of work.

**Implementations:**

| Class name          | Description                           |
| ------------------- | ------------------------------------- |
| `FastRouter     `   | Wrapper around the Fast Route library |

### Discoverer

The notion of Discoverer was introduced to aid defining routes. Sometimes you don't want to call the ``addRoute()``
method for each route. A reason could be that you have defined your routes elsewhere and you want to avoid
duplication of these definitions which can be subjects of frequent change during development.

Currently, there aren't any implementations.

### Container

For Woohoo Labs. API Framework, the container is only a class which is capable to instantiate any handlers
if you provide them their fully qualified class name (in fact, containers are called IoC Containers and
they are much more then described above). The built-in container is a really naive one: it uses pure PHP
reflection to create a handler object. If you want to use a more clever IoC Container which is
[Container-Interop compliant](https://github.com/container-interop/container-interop) (like PHP-DI), all
you have to do is to pass its reference to the framework.

If your chosen container doesn't support this interface (like Pimple or Dice), you only have to write an
adapter for them implementing the common interface.

**Implementations:**

| Class name          | Description                                      |
| ------------------- | ------------------------------------------------ |
| `BasicContainer `   | Instantiates the handler classes with reflection |

### Deserializer

A deserializer automatically handles string to array/object conversion from data formats like JSON, XML or
YML. They are needed in the beginning of the a request-response lifecycle when the framework receives a
request and the contained data (e.g.: body) should be converted into an array or object. As a default,
``PHPDeserializer`` is used.

**Implementations:**

| Class name          | Supported formats | Description                                  |
| ------------------- | ----------------- | -------------------------------------------- |
| `PhpDeserializer`   | JSON              | Deserializes with pure PHP functions         |
| `JmsDeserializer`   | JSON, XML         | A wrapper around the JmsSerializer library   |
| `NaiveDeserializer` | -                 | Only outputs the original data               |

### Serializer

A serializer automatically handles array/object to string conversion into data formats like JSON, XML or YML.
They are needed in the end of the request-response lifecycle when you want to send your data as a response.
As a default, ``PHPSerializer`` is used which is able to produce data in JSON format.

**Implementations:**

| Class name        | Supported formats | Description                                  |
| ----------------- | ----------------- | -------------------------------------------- |
| `PhpSerializer`   | JSON              | Serializes with pure PHP functions           |
| `JmsSerializer`   | JSON, XML, YML    | A wrapper around the JmsSerializer library   |
| `NaiveSerializer` | -                 | Only outputs the original data               |

### Request

A request object is the Object-Oriented representation of an HTTP request. For this purpose,
Symfony's HTTP Foundation is used by a wrapper class which implements the ``RequestInterface``.

**Implementations:**

| Class name           | Description                                      |
| -------------------- | ------------------------------------------------ |
| `FoundationRequest ` | A wrapper around the Symfony Foundation library  |

### Response

Again, the response is the Object-Oriented representation of an HTTP response. It is just a POPO again.

### Responder

A responder is capable of sending a response into the ether. For this purpose, Symfony's HTTP Foundation is used
by a wrapper class which implements the ``ResponderInterface``.

**Implementations:**

| Class name           | Description                                       |
| -------------------- | ------------------------------------------------- |
| `FoundationResponder | A wrapper around the Symfony Foundation responder |

## Basic Usage of Woohoo Labs. API Framework

The workflow with Woohoo Labs. API Framework is quite straightforward. You will only need Composer for the
dependencies and the autoloading.

##### Add the following to your composer.json:
```json
{
    "require": {
        "woohoolabs/api-framework": "0.*"
    }
}
```

Furthermore, if you want to use the built-in components (like the autoloader, the serializer etc.) then you also
have to add the following dependencies to the _require_ section of your composer.json:

```json
{
    "require": {
        "symfony/http-foundation": "*",
        "nikic/fast-route": "*@dev",
        "jms/serializer": "*",
        "symfony/yaml": "*"
    }
}
```

##### Update your dependencies with Composer:

```bash
$ composer update
```

##### Autoload the classes in your bootstrap (if you haven't already done so):

```php
require "vendor/autoload.php"
```

##### Instantiate and configure the framework:

You have to create a configuration object for this step. In the example below, it will set the framework to run in
development mode and turn off caching:

```php
$config= new Config();
$config->setDevelopmentMode(true);
$config->setCaching(false);

$apiFramework= new ApiFramework($config);
```

##### Define some routes:

You have to define either a class or a callback handler for each route. A route consists of an HTTP verb and
a URI. By convention, start the URI with a _/_. It can also contain curly brace templates if you stay with
the default implementation.

```php
$apiFramework->addRoutes(function (RouterInterface $router) {

    $router->addCallbackRoute("GET", "/me", function (RequestInterface $request) {
        $response= new Response();
        $response->setContent("Welcome to the real world!");
        return $response;
    });
    
    $router->addRoute("GET", "/users", "App\\Controllers\\UserController", "getUsers");
    $router->addRoute("POST", "/users/{id}", "App\\Controllers\\UserController", "updateUser");
});
```

**Reminder**: As of PHP 5.5, you are able to use the [::class keyword](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class)
to resolve class names:

```php
$router->addRoute("GET", "/users", UserController::class, "getUsers");
```

##### Define the handlers for the routes:

There are two important things to notice here: each handler receives a ``RequestInterface`` object and
they are expected to return a ``ResponseInterface`` object.

```php
namespace App\Controllers;

use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

class UserController
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function getUsers(RequestInterface $request)
    {
        $users= ["Steve", "Arnie", "Jason", "Bud"];
        
        $response= new Response();
        $response->setContent($users);
        return $response;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
     public function updateUser(RequestInterface $request)
     {
        $userId= $request->getUriParameter("id");
        $userData= $request->getBodyAsArray();
        
        $response= new Response();
        $response->setContent(array_merge($userData, ["id" => $userId]));
        return $response;
     }
}
```

Of course, you should remain [DRY](http://en.wikipedia.org/wiki/Don't_repeat_yourself) and not
copy all this stuff with the initialization of the response throughout your handlers. Creating
an Adapter for the response or building a Factory should be fine.

However you don't have to worry that your handlers become tightly coupled to HTTP. Just read
[this fantastic post](https://igor.io/2013/02/03/http-foundation-value.html) from
[Igor Wiedler](https://twitter.com/igorwhiletrue).

##### Finally, launch the framework:

```php
$apiFramework->work();
```

## Advanced Usage of Woohoo Labs. API Framework

### Hooks

Hooking enables you to get the control before and/or after dispatching occurs. Note that it is only
available for class handlers! If you specify a ``preHook()`` method in your handler class then it
will be automatically invoked before the handler method. The same way, if you specify a ``postHook()``
method then it will be called after the original handler method has been invoked. Important to note
that these methods must expect a ``Request`` object as their only argument but they aren't required
to provide any return value.

Additionally you can even override the name of the hooks in the configuration: so if you have already
had a method like ``preDispatch()`` then you can use it easily!

## The End

**Congratulations, you have just learned how to use Woohoo Labs. API Framework!**
