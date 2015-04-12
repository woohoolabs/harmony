# Change Log

### 0.7 - 2015-04-12

#### Added
- Support for middlewares

#### Changed
- Workflow is now based on middlewares completely
- Removed events and configuration class

### 0.5.0 - 2015-04-11

#### Added
- Possibility to define defaults for various request data

#### Changed
- Renamed project to Harmony
- Renamed the framework's handler method to live()

### 0.4.0 - 2014-12-04

#### Added
- Session handling
- Form data handling

#### Changed
- Handlers don't have to return the response

### 0.3.0 - 2014-10-25

#### Added
- ``Request`` and ``Response`` objects got new capabilities

#### Changed
- Completely reworked serializers/deserializers
- ``Responder`` objects were removed, ``Response`` objects became responders too

### 0.2.1 - 2014-10-19

#### Fixed
- Issue with class event listeners

### 0.2.0 - 2014-10-18

#### Added
- Event dispatcher
- Routes can be added with ``Config::setRoutes()`` to enable route caching
- Separated serializing and deserializing

#### Changed
- ``PHPSerializer`` became the default serializer implementation
- ``PHPDeserializer`` became the default deserializer implementation
- Removed Discoverers

#### Fixed
- ``FastRouter`` now works as expected!

### 0.1.2 - 2014-10-02

#### Added
- A simple ``PhpSerializer`` which only supports the JSON format

#### Fixed
- ``JmsSerializer`` deserializing error
- HTTP request errors

#### Changed
- ``Fastrouter`` doesn't need the ``Container`` as a dependency

### 0.1.1 - 2014-10-01

#### Changed
- ``Fastrouter`` doesn't need the ``Container`` as a dependency

### 0.1.0 - 2014-10-01

#### Added
- Enabled using callbacks as route handlers
- Improved support for HTTP caching