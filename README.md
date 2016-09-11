# Stats Generator for Facebook Groups

[![Latest Version](https://img.shields.io/github/release/wwphp-fb/stats.svg?style=flat-square)](https://github.com/wwphp-fb/stats/releases)
[![Build Status](https://img.shields.io/travis/wwphp-fb/stats/master.svg?style=flat-square)](https://travis-ci.org/wwphp-fb/stats)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6/big.png)](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6)

PHP application for generating Facebook groups statistics.


## Documentation

The [Frequently Asked Questions](doc/faq.md) might have an answer to your question.

Read the following chapters for using this application locally:

* [Installation](doc/installation.md)
* [Configuration](doc/configuration.md)
* [Commands](doc/commands.md)


## Usage

1. [Create](https://developers.facebook.com/) a new Facebook Application.

2. Install with Composer

    ```bash
    git clone git://github.com/wwphp-fb/stats
    cd stats
    composer install
    ```

3. Configure Parameters

    The componser installation script creates `app/config/parameters.yml` file
    for your settings. Configuration parameters are described in comments in the
    [parameters.yml.dist](app/config/parameters.yml.dist). Provide values for
    your application, date range and other configuration.

4. Generate Report

    ```bash
    bin/stats generate
    ```

    To generate the user access token, use
    [Graph API explorer](https://developers.facebook.com/tools/explorer/) and
    select the application created in step 1.


## License and Contributing

This repository is released under the [MIT license](LICENSE).

You are welcome to [suggest improvements or open a ticket](CONTRIBUTING.md).
