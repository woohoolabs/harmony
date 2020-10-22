## 7.2.0 - unreleased

ADDED:

CHANGED:

REMOVED:

DEPRECATED:

FIXED:

## 7.1.3 - 2020-10-22

CHANGED:

- Increased minimum PHPUnit version requirement to 9.3

FIXED:

- [#28](https://github.com/woohoolabs/harmony/pull/28): Fix the composer provide rule

## 7.1.2 - 2020-06-23

ADDED:

- Support for PHP 8
- Support for PHPUnit 9

## 7.1.1 - 2020-04-29

FIXED:

- Middleware after a false condition are not run

## 7.1.0 - 2020-01-06

ADDED:

- `LaminasEmitterMiddleware`

DEPRECATED:

- `HttpHandlerRunnerMiddleware` because Zend Framework was migrated to Laminas

## 7.0.0 - 2019-12-29

REMOVED:

- `Harmony::__invoke()` which was deprecated since Harmony 6.1.0

## 6.2.2 - 2019-10-04

CHANGED:

- Improved PSR-12 conformance
- Added more property type declarations

## 6.2.1 - 2019-08-21

CHANGED:

- Updated dev dependencies
- Improved static analysis

## 6.2.0 - 2019-08-14

CHANGED:

- Increased minimum PHP version requirement to 7.4 as property type declarations were added

## 6.1.0 - 2019-06-06

DEPRECATED:

- `Harmony::__invoke()`: use `Harmony::run()` instead

## 6.0.2 - 2019-01-11

FIXED:

- Instead of a fatal error, an exception is thrown when the `FastRouteMiddleware::$fastRoute` property is `null` 

## 6.0.1 - 2018-12-21

CHANGED:

- Apply the Woohoo Labs. Coding Standard

## 6.0.0 - 2018-10-18

ADDED:

- Support for Zend Diactoros 2.0

CHANGED:

- Run tests on PHP 7.3

REMOVED:

- `DiactorosResponderMiddleware` as it has been deprecated since Harmony 5.1.0 (**breaking change**)

## 5.1.0 - 2018-07-30

ADDED:

- `HttpHandlerRunnerMiddleware` in order to be able to easily use Zend's Http Handler Runner package

CHANGED:

- Deprecated `DiactorosResponderMiddleware` because the default HTTP response emitters were deprecated as of Diactoros v1.8.0.
You can use `HttpHandlerRunnerMiddleware` instead.

## 5.0.0 - 2018-02-02

ADDED:

- Support for PSR-15

CHANGED:

- Adapted the PSR-15 `MiddlewareInterface` (**breaking change**)
- `Harmony::__invoke()` accepts no arguments (**breaking change**)
- Condition callables receive the request (`ServerRequestInterface`) as a second parameter
- PHPUnit 7 is minimally required to run tests

REMOVED:

- `HarmonyMiddlewareInterface` (**breaking change**)
- `Harmony::getResponse()` (**breaking change**)
- `Harmony::removeMiddleware()` (**breaking change**)

## 4.1.1 - 2017-11-04

CHANGED:

- Routing logic was put into `FastRouteMiddleware::routeRequest()` template method

FIXED

- Included Docker image now has PHP 7.1

## 4.1.0 - 2017-09-05

CHANGED:

- Increased minimum PHP version requirement to 7.1
- Added more type declarations

## 4.0.0 - 2017-02-14

ADDED:

- Support for PSR-11

CHANGED:

- Better exception message for `DispatcherException`
- Tests namespace became `WoohooLabs\Harmony\Tests`

REMOVED:

- Support for Container-Interop

FIXED:

- `DiactorosResponderMiddleware` passes the request and response to the next middleware

## 3.1.0 - 2016-10-18

ADDED:

- Docker Compose file to run tests more easily

CHANGED:

- Increased minimum PHP version requirement to 7.0
- Fixed stylistic errors in the documentation

## 3.0.1 - 2016-06-13

ADDED:

- Getters for `MethodNotAllowed`, `MiddlewareNotExists` and `RouteNotFound` exceptions
- Exception message for `MethodNotAllowed` and `RouteNotFound` exceptions

## 3.0.0 - 2016-05-22

ADDED:

- Support for conditional middleware
- Some basic built-in conditions (e.g.: `ExactPathCondition`)
- Support for PHPUnit 5.0

CHANGED:

- [#8](https://github.com/woohoolabs/harmony/issues/8): Use StyleCI for CS checks
- [#9](https://github.com/woohoolabs/harmony/issues/9): Improved Travis config
- Middleware returning null will throw `MiddlewareWrongReturnType`
- Providing an ID for middleware became optional
- Exception names became shorter
- A `MiddlewareNotFound` exception will be thrown when removing a non-existent middleware with `Harmony::removeMiddleware()`
- Renamed `MiddlewareInterface` to `HarmonyMiddlewareInterface`

FIXED:

- `@return` PHPDoc comments for middleware were missing
- `DiactorosResponderMiddleware` uses `SapiEmitter` by default
- `DiactorosResponderMiddleware` used to be only usable as a final middleware

REMOVED:

- The concept of "final middleware"
- Output buffering middleware

## 2.3.1 - 2016-03-06

CHANGED:

- [#7](https://github.com/woohoolabs/harmony/issues/7): Removed unnecessary `terminated` property
- Improved tests

## 2.3.0 - 2016-03-01

CHANGED:

- [#5](https://github.com/woohoolabs/harmony/issues/5): Return a Response object for all middleware
- Deprecated middleware returning null
- Middleware must return a `ResponseInterface` instance, otherwise an exception will be thrown
- Running tests on PHP 5.6 (code style) and PHP 7 (PHPUnit) instead of PHP 5.5 and PHP 5.6
- Improved test coverage

## 2.2.0 - 2015-12-16

**The release contains breaking changes**

ADDED:

- Support for multiple final middleware
- Configuration option for `DiactorosResponderMiddleware` to not respond when headers have already been sent
- Added middleware to support output buffering
- Example application

CHANGED:

- Final middleware are integrated with the normal ones (**breaking change**)

FIXED:

- PHP version constraint in composer.json to correctly support PHP 7

## 2.1.0 - 2015-11-06

ADDED:

- Configuration option to define the request attribute name storing the route handler

CHANGED:

- Simplified router definition of invokable controller classes
- Improved and more extensive documentation

FIXED:

- Made default middleware compatible with other frameworks

## 2.0.1 - 2015-08-27

FIXED:

- Final middleware could invoke all the remaining middleware

## 2.0.0 - 2015-07-16

ADDED:

- Added the possibility to set a final middleware which is always executed after the normal ones

CHANGED:

- Middleware interface design is now based on Zend Stratigility
- `Harmony::__construct()` requires a request and a response dependency
- `Harmony::addMiddleware()` also needs the ID of the middleware

REMOVED:

- `Harmony::build()`, `Harmony::next()` and ``Harmony::live()`
- `CallbackMiddleware`, `InitializerMiddleware`
- All dispatchers

## 1.0.1 - 2015-06-28

CHANGED:

- Improved unit tests coverage

FIXED:

- `FastRouteMiddleware` now passes correct URI to FastRoute

## 1.0.0 - 2015-06-16

ADDED:

- Support for PSR-7
- Improved middleware control
- Unit tests

REMOVED:

- Original request and response interfaces
- Serialization capabilities
- Hooks from handler classes

## 0.7.0 - 2015-04-12

ADDED:

- Support for middleware

CHANGED:

- Workflow is now based on middleware completely
- Removed events and configuration class

## 0.5.0 - 2015-04-11

ADDED:

- Possibility to define defaults for various request data

CHANGED:

- Renamed project to Harmony
- Renamed the framework's handler method to live()

## 0.4.0 - 2014-12-04

ADDED:

- Session handling
- Form data handling

CHANGED:

- Handlers don't have to return the response

## 0.3.0 - 2014-10-25

ADDED:

- `Request` and `Response` objects got new capabilities

CHANGED:

- Completely reworked serializers and deserializers
- `Responder` objects were removed, `Response` objects became responders too

## 0.2.1 - 2014-10-19

FIXED:

- Issue with class event listeners

## 0.2.0 - 2014-10-18

ADDED:

- Event dispatcher
- Routes can be added with `Config::setRoutes()` to enable route caching
- Separated serializing and deserializing

CHANGED:

- `PHPSerializer` became the default serializer implementation
- `PHPDeserializer` became the default deserializer implementation
- Removed Discoverers

FIXED:

- `FastRouter` now works as expected!

## 0.1.2 - 2014-10-02

ADDED:

- A simple `PhpSerializer` which only supports the JSON format

FIXED:

- `JmsSerializer` deserializing error
- HTTP request errors

CHANGED:

- `Fastrouter` doesn't need the `Container` as a dependency

## 0.1.1 - 2014-10-01

CHANGED:

- `Fastrouter` doesn't need the `Container` as a dependency

## 0.1.0 - 2014-10-01

- Initial release
