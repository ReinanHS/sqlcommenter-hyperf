<p align="center"><a href="https://google.github.io/sqlcommenter/" target="_blank" rel="noopener noreferrer"><img src="https://i.imgur.com/pkcyYLU.png" alt="Sqlcommenter Logo"></a></p>

<p align="center">
  <a href="https://github.com/reinanhs/sqlcommenter-hyperf/releases"><img src="https://poser.pugx.org/reinanhs/sqlcommenter-hyperf/v/stable" alt="Stable Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=8.1-brightgreen.svg?maxAge=2592000" alt="Php Version"></a>
  <a href="https://packagist.org/packages/reinanhs/sqlcommenter-hyperf"><img src="https://poser.pugx.org/reinanhs/sqlcommenter-hyperf/downloads" alt="Total Downloads"></a>
  <a href="https://github.com/reinanhs/sqlcommenter-hyperf/blob/main/LICENSE"><img src="https://img.shields.io/github/license/reinanhs/sqlcommenter-hyperf.svg?maxAge=2592000" alt="Sqlcommenter License"></a>
</p>

## Introduction

Sqlcommenter Hyperf is a library designed to automatically add comments to SQL queries executed by
the [Hyperf](https://github.com/hyperf/hyperf) framework. These comments use
the [sqlcommenter](https://google.github.io/sqlcommenter/) format, which is understood by various database tools and
services, providing enhanced insights and traceability for your application's database interactions.

## Installation

You can install the library via Composer:

```shell
composer require reinanhs/sqlcommenter-hyperf
```

Add component configuration:

```shell
php bin/hyperf.php vendor:publish reinanhs/sqlcommenter-hyperf
```

## Sample

See an example of a simple query being executed:

```sql
select * from users
```

Using this package, comments like this will be added:

```sql
select * from users /*framework='Hyperf',
application='hyperf-skeleton',
controller='UserController',
action='index',
route='%%2Fapi%%2Ftest',
db_driver='mysql',
traceparent='00-1cd60708968a61e942b5dacc2d4a5473-7534abe7ed36ce35-01'*/
```

By examining the above SQL statement and its comment in `/*...*/`, we can correlate and identify the fields in the slow SQL query to our source code in our web application:

> Table 1: Information about the description of each type of comment

| ORIGINAL FIELD                                                            | INTERPRETATION                                                                                                               |
|---------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------|
| **framework**='Hyperf'                                                    | The word “Hyperf” represents the framework that generated the query.                                                         |
| **application**='hyperf-skeleton'                                         | The name of the project where the code was run.                                                                              |
| **controller**='UserController'                                           | Controller name in `app/Controller`.                                                                                         |
| **action**='index'                                                        | Name of the method that was called called by the controller.                                                                 |
| **route**='%%2Fapi%%2Ftest'                                               | The route used during the query call.                                                                                        |
| **db_driver**='mysql'                                                     | The name of the Hyperf database engine.                                                                                      |
| **traceparent**='00-1cd60708968a61e942b5dacc2d4a5473-7534abe7ed36ce35-01' | The [W3C TraceContext Traceparent](https://www.w3.org/TR/trace-context/#traceparent-field) field of the OpenTelemetry trace. |

Various tools and services can interpret these comments, helping to correlate user code with SQL statements generated by the ORM, and can be examined in the database server logs. This provides better observability into your application state up to the database server.

Here is an example of how this information appears within [Google Cloud SQL](https://cloud.google.com/sql/docs/postgres/using-query-insights):

![SQLcommenter example on GCP](https://i.imgur.com/lce2iZ6.png)

In addition to **Cloud SQL**, several other tools also support _sqlcommenter_. One example is the [Planetscale Query Insights](https://planetscale.com/docs/concepts/query-insights).

## Config

When you install the library in your project, it will be automatically enabled through the default settings. Therefore, to use this library, you only need to have it installed in your project.

However, if you want to disable some comments, you can do so through the settings. It is also worth mentioning that you can completely disable the execution of this library in a specific environment through the settings.

### Include

With the settings below, you can enable or disable the comments generated by this library. By default, all comments are enabled. You will find this configuration in the `config/autoload/sqlcommenter.php` file:

```php
'include' => [
    'framework' => env('SQLCOMMENTER_ENABLE_FRAMEWORK', true),
    'controller' => env('SQLCOMMENTER_ENABLE_CONTROLLER', true),
    'action' => env('SQLCOMMENTER_ENABLE_ACTION', true),
    'route' => env('SQLCOMMENTER_ENABLE_ROUTE', true),
    'application' => env('SQLCOMMENTER_ENABLE_APPLICATION', true),
    'db_driver' => env('SQLCOMMENTER_ENABLE_DB_DRIVER', true),
],
```

You can refer to the documentation for more details.

## Features

- Automatically adds sqlcommenter-compatible comments to SQL queries.
- Provides better traceability and insights into database interactions.
- Easy integration with the Hyperf framework.
- Supports multiple database drivers.

## Performance

Using the Sqlcommenter Hyperf library may introduce a small performance impact due to the addition of comments to SQL queries. However, the benefits in terms of traceability, ease of debugging, and integration with monitoring tools generally outweigh this impact.

To demonstrate the effectiveness of the Sqlcommenter Hyperf library, we will conduct two distinct tests. The measurement will be performed in a controlled Google Cloud Run environment with the following configurations:

- CPU always allocated
- Minimum number of instances: 1
- Maximum number of instances: 10
- Memory per instance: 2GB
- Number of vCPUs per instance: 2vCPU
- Maximum concurrent requests per instance: 500

### Average execution time test of the code block:

In this test, we will measure the average execution time of the SqlCommenterAspect code block that adds SQL comments.
See the results of this experiment below:

// TODO: Add image with results

The average execution time of this code was x.xx ms

- [Complete documentation regarding the average execution time test of the code block](https://github.com/ReinanHS/sqlcommenter-hyperf/wiki/Performance#average-execution-time-test-of-the-code-block)

### Request latency performance test

In this test, we will use K6 to make multiple requests and compare the performance with the library enabled and disabled.
See the results of this experiment below:

// TODO: Add image with results

If you want to check the detailed information about the test, it is recommended to click the link below:

- [Complete documentation regarding the request latency performance test](https://github.com/ReinanHS/sqlcommenter-hyperf/wiki/Performance#request-latency-performance-test)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Do you want to be part of this project? Read [how to contribute](CONTRIBUTING.md).

## Security Vulnerabilities

Please review [our security policy](https://github.com/reinanhs/sqlcommenter-hyperf/security/policy) on how to report security vulnerabilities.

### License

This project is under license. See the [LICENSE](LICENSE) file for more details.