<p align="center"><a href="https://google.github.io/sqlcommenter/" target="_blank" rel="noopener noreferrer"><img width="180" src="https://google.github.io/sqlcommenter/images/sqlcommenter_logo.png" alt="Sqlcommenter Logo"></a></p>

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

## Sample

See an example of a simple query being executed:

```sql
select * from users
```

Using this package, comments like this will be added:

```sql
select * from users /*framework='Hyperf',
controller='UserController',
action='index',
route='%%2Fapi%%2Ftest',
db_driver='mysql',
traceparent='00-1cd60708968a61e942b5dacc2d4a5473-7534abe7ed36ce35-01'*/
```

By examining the above SQL statement and its comment in `/*...*/`, we can correlate and identify the fields in the slow SQL query to our source code in our web application:

| ORIGINAL FIELD                                                            | INTERPRETATION                                                                                                              |
|---------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------|
| **framework**='Hyperf'                                                    | The word “Hyperf” represents the framework that generated the query                                                         |
| **controller**='UserController'                                           | Controller name in `app/Controller`                                                                                         |
| **action**='index'                                                        | Name of the method that was called called by the controller                                                                 |
| **route**='%%2Fapi%%2Ftest'                                               | The route used during the query call                                                                                        |
| **db_driver**='mysql'                                                     | The name of the Hyperf database engine                                                                                      |
| **traceparent**='00-1cd60708968a61e942b5dacc2d4a5473-7534abe7ed36ce35-01' | The [W3C TraceContext Traceparent](https://www.w3.org/TR/trace-context/#traceparent-field) field of the OpenTelemetry trace |

Various tools and services can interpret these comments, helping to correlate user code with SQL statements generated by the ORM, and can be examined in the database server logs. This provides better observability into your application state up to the database server.

Here is an example of how this information appears within [Google Cloud SQL](https://cloud.google.com/sql/docs/postgres/using-query-insights):

![Exemplo do sqlcommenter na GCP](image.png)

In addition to **Cloud SQL**, several other tools also support _sqlcommenter_. One example is the [Planetscale Query Insights](https://planetscale.com/docs/concepts/query-insights).

## Features

- Automatically adds sqlcommenter-compatible comments to SQL queries.
- Provides better traceability and insights into database interactions.
- Easy integration with the Hyperf framework.
- Supports multiple database drivers.

## Installation

You can install the library via Composer:

```
composer require reinanhs/sqlcommenter-hyperf
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Become one of the contributors

Do you want to be part of this project? Click HERE and read [how to contribute](CONTRIBUTING.md).

### License

This project is under license. See the [LICENSE](LICENSE) file for more details.