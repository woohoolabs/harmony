## 2.2.0 - unreleased

ADDED:

- Configuration option for `DiactorosResponderMiddleware` to not respond when headers have already been sent
- Example application

CHANGED:

FIXED:

- PHP version constraint in composer.json

## 2.1.0 - 2015-11-06

ADDED:

- Configuration option to define the request attribute name storing the route handler

CHANGED:

- Simplified router definition of invokable controller classes
- Improved and more extensive documentation

FIXED:

- Made default middlewares compatible with other frameworks

## 2.0.1 - 2015-08-27

FIXED:

- Final middlewares could invoke all the remaining middlewares

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

- Support for middlewares

CHANGED:

- Workflow is now based on middlewares completely
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
