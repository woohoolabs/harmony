# Woohoo Labs. API Framework

**Woohoo Labs. API Framework is a flexible micro-framework developed for quality API-s.**

Our aim was to create a framework for API-s which is almost invisible, easily extensible, but first
of all, extremely flexible. We wanted to give you total control while providing a clean
interface to communicate with.

## "Long live is the framework"



The general problem with frameworks is that they require you to use one set of tools they have. And
everything seems to be OK as long as you have a small project. But as soon as your requirements
change (e.g. things get more complicated, you have to maintain the application longer than you
thought or you need more scaling), there is a good chance that you will face issues with your framework.

Let's look at a realistic scenario: you have an MVC framework which has its integrated IoC Container
and ORM layer. You are pretty happy with these tools. Some weeks later, you discover that
you would cache your database queries, but the interior ORM doesn't support it. It's
not a big deal - you say - to  change your persistence to the other ORM. Problem solved.

Next time you realize that you don't need an MVC framework as you shifted away from this paradigm and started adopting Domain Driven Design. So you need services instead of controllers and entities
instead of models. As your framework doesn't support changing your directory structure, you accept
the fact that your services will be in the controllers directory. You are happy with it as the solution was really easy this time.

But not long afterwards you will find yourself needing a much more sophisticated and performant
IoC Container than you have. Maybe you need Dice or PHP-DI. That will be a real issue: how will you
swap the core of the framework which instantiates and initializes your controllers? As you are a
creative programmer you define a common interface and write adapters etc. So finally you manage to
overcome this hot situation too. But that's nonesense.

As Phil Sturgeon pointed out in his great [blog post](http://philsturgeon.uk/blog/2014/01/the-framework-is-dead-long-live-the-framework), in a complex enough situation, with a skilled enough development team, you don't need a framework at all in its original meaning. You only need a set of **interpluggable** components of **your preference** which can be easily integrated into your application.

Requirements will always change. What seemed to be a good choice once, it is not enough next time.  So making thing impossible to change in a framework leads to hard times refactoring your application.

## Features

- Supporting Swagger 2.0 design-first approach
- Full control over HTTP requests and responses in an OO way
- Supporting multiple media types (JSON, YML, XML)
- Supporting any [Interop-compliant IoC Containers](https://github.com/container-interop/container-interop)
- Supporting MVC as well as creating Services
- Supporting caching

## Concepts of Woohoo Labs. API Framework

1. Configuration
2. Request
3. Container
4. Router
5. Discoverer
6. Dispatcher
7. Serializer
8. Response Info
9. Responder
