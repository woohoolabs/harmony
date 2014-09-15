# Woohoo Labs. API Framework

**Woohoo Labs. API Framework is a flexible micro-framework developed for quality API-s.**

Our aim was to create an invisible, easily extensible, but first of all, extremely flexible framework for API-s. We wanted to give you total control while providing a clean interface to communicate with.

## "Long live is the framework"

The general problem with frameworks is that they require you to use one set of tools they have. Initially, everything seems to be OK, because either the scope of the project is small or you are sure that you've found the best framework ever. But as soon as your requirements change (e.g. things get more complicated, you have to maintain the application longer than you thought or you need more scaling), there is a good chance that you will face issues with your framework.

As Phil Sturgeon pointed out in his great [blog post](http://philsturgeon.uk/blog/2014/01/the-framework-is-dead-long-live-the-framework), in a complex enough situation, with a skilled enough development team, you don't need a framework at all in its original meaning thanks to the modern era of Composer. All you need is only a set of interpluggable components of your preference which can be easily integrated into your application.

Requirements will always change. What seemed to be a good choice once, it is not enough next time. So if things are impossible to change in a framework then it can easily lead to hard times refactoring your application. Our framework tries to find remedy for this issue.

### A Case Study

Let's look at a realistic scenario to understand the problem better: you have an MVC framework which has its integrated IoC Container and ORM layer. You are pretty happy with these tools. Some weeks later, you discover that you would cache your database queries, but the interior ORM doesn't support it. Your application is well designed, so it's not a (very) big deal to change your persistence to another ORM. Problem solved quite easily.

Next time you realize that you no longer need an MVC framework as you shifted away from this paradigm and started adopting Domain Driven Design. So you need services instead of controllers and entities instead of models. As your framework doesn't support changing the directory structure, you accept the fact that your services will be in the controllers directory. It was an easy solution again.

But not long afterwards you will find yourself needing a much more sophisticated or performant IoC Container than the one you have. Maybe you need Dice or PHP-DI. This time you can't overcome the situation easily: how will you swap the core of the framework which instantiates and initializes your controllers? OK, you are a creative programmer and you send a Pull Request to the maintainers of your framework. So you can manage to win again.

But these solutions described above are nonsense. Only hacking things together. Let's have a look at the reasons.

### Lessons learned

Letting your Services stay in the controllers directory, in fact, was a big mistake: when developing quality software, naming should be in focus. According to DDD initiatives like Matthias Verraes, finding expressive and consistent names for things should be a priority! That's why you shouldn't let your framework define your directory structure or restrict the changes. This principle is well summarized by Anna-Julie Scheuermann:

> Once I'm happy with the names, I'm usually happy with the design.

Then, changing routing or dispatching (class instantiation) in a framework can be hard on-the-fly. But why not think about it in advance? Fortunately, a (not yet officially accepted) common interface for IoC Containers already exists, so why not making use of it? When your project is huge, make sure that you can easily swap things in the deep of your framework too.

And finally, there are full stack and even micro frameworks with a long list of features and components. In our opinion, a framework shouldn't provide more than that is actually needed. E.g. if I don't need the Eloquent ORM layer in Laravel, why placing it in the project? In the most cases, it's better to only create separate components and let the user decide if they want to use them or not.

## Use cases of Woohoo Labs. API Framework

Of course, the ideas above doesn't suit the needs of all projects and teams. Firstly, this framework works best for API-s. Furthermore, less experienced teams should probably choose a less lenient framework with more features in order to speed up development in the initial phase.

To sum up, Woohoo Labs. API Framework is most effective for teams with a solid understanding of API development. Its flexibility is more advantageous if you need complex tools like IoC Containers or API documentation tools like Swagger.

## Features of Woohoo Labs. API Framework

- Supporting Swagger 2.0
- Full control over HTTP requests and responses in an OO way
- Supporting multiple media types (JSON, YML, XML)
- Supporting any IoC Containers
- Supporting MVC as well as any other architecture patterns
- Supporting caching

## Concepts of Woohoo Labs. API Framework

1. Configuration
2. Router
3. Discoverer
4. Container
5. Serializer
6. Request
7. Response
8. Responder

### Configuration

When you instantiate the framework you have to provide your configuration options including the current environment of your appliation (DEV or PROD), caching etc. This configuration will be a POPO (Plain old PHP Object).

### Router

The router tells you which class method (called handlers as of now) handles a request coming to a specific URI. The default router used by Woohoo Labs. API Framework is the library of Nikita Popov, because of its elegance and performance. You can read more about it [clicking here](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html). Of course if you weren't satisfied with it, you can change it anytime with a minimal amount of work.

### Discoverer

The notion of Discoverer was introduced to aid defining routes. Sometimes you don't want to call the ``addRoute()`` method for each route. A reason could be that you have defined your routes elsewhere and you want to avoid duplication of these definitions which can be subjects of frequent change during development.

A Discoverer simplifies routing but you are absolutely free to use them or not. Currently, we only provide one Discoverer which integrates the Swagger 2.0 spec into you application.

### Container

For Woohoo Labs. API Framework, the container is only a class which is capable to instantiate any handlers if you provide them their fully qualified class name (in fact, containers are called IoC Containers and they are much more then described above). The build-in container is a really naive one: it uses pure PHP reflection to create a handler object. If you want to use a more clever IoC Container which is [Container-Interop compliant](https://github.com/container-interop/container-interop) (like PHP-DI), all you have to do is to pass its reference to the framework.

If your chosen container doesn't support this interface (like Pimple or Dice), you only have to write an adapter for them implementing the common interface.

### Serializer

A serializer handles automatic conversion of data formats like JSON, XML or YML. They are needed twice during a request-response lifecycle. First, when the framework receives a request and the contained data (e.g.: body) should be converted into an array or object. A serializer will also be needed when you want to send your data as a response. As a default, the popular library, JMS Serializer is used.

### Request

A request object is the Object-Oriented representation of an HTTP request. For this purpose, Symfony's HTTP Foundation is used by a wrapper class which implements the ``RequestInterface``.

### Response

Again, the response is the Object-Oriented representation of an HTTP response. It is just a POPO again.

### Responder

A responder is capable of sending a response into the ether. For this purpose, Symfony's HTTP Foundation is used by a wrapper class which implements the ``ResponderInterface``.

## Usage of Woohoo Labs. API Framework

The workflow with Woohoo Labs. API Framework is quite straightforward. You will only need Composer for the dependencies and the autoloading.

- Add the following to your composer.json:
```json
{
    "require": {
        "woohoolabs/api-framework": "0.*"
    }
}
```

- Update your dependencies with Composer:
```console
composer update
```

- Autoload the classes in your bootstrap (if you haven't already done so):

```php
require "vendor/autoload.php"
```

- Instantiate and configure the framework:

First, create the configuration object:

```php
$config= new \WoohooLabs\ApiFramework\Config();
$config->setDevelopmentMode(true);
$config->setCaching(false);
```

It will set the framework to run in development mode and turn off the caching. Now, define some routes:

```php
$router= new FastRouter($config);
$router->addRoute("GET", "users", ["app\\controllers\\UserController", "getUsers"]);
$router->addRoute("POST", "users", ["app\\controllers\\UserController", "createUser"]);
```

Finally, launch the framework and make use of the router created above:

```php
$apiFramework= new ApiFramework($config);
$apiFramework->setRouter($router);
```

- Define the handlers for the ``UserController``:

There are two important things to know here: each handler will receive a ``request`` argument and must provide a return value of the ``ResponseInterface`` type.

```php
namespace app\controllers;

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
        $users= ["Johnny", "Jacky", "James", "Arny"];
        $response= new Response();
        $response->setContent($users);
        return $response;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
     public function createUser(RequestInterface $request)
     {
        $user= $request->getDataAsArray();
        $response= new Response();
        $response->setContent(["id" => 1]);
        return $response;
     }
}
```

Of course, you should remain [DRY](http://en.wikipedia.org/wiki/Don't_repeat_yourself) and not copy all this response logic throughout your controllers. Maybe you can create a Factory for it.

#### Congratualitions, you have just learned how to use Woohoo Labs. API Framework! ####
