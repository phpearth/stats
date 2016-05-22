# Stats generator for Facebook group

[![Latest Version](https://img.shields.io/github/release/wwphp-fb/stats.svg?style=flat-square)](https://github.com/wwphp-fb/stats/releases)
[![Build Status](https://img.shields.io/travis/wwphp-fb/stats/master.svg?style=flat-square)](https://travis-ci.org/wwphp-fb/stats)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6/big.png)](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6)

PHP application for generating stats for International PHP Facebook group.

## FAQ

**What is the benefit of the points?**

We could never possibly thank properly enough for contributions from members in
this group. This group is all about open source spirit and helping others. These
points will help you check your contribution activity in our group compared to
other members and bring some fun in PHP back. Leetness in the universe is also
not excluded for getting higher score than other members :)

**How are points calculated?**

* Each post, each comment and each reply to a comment in the group gets you one point.
* Number of likes of your topic, comment or reply gets you extra points.
* More detailed comments and replies get you extra points.
* Sequential comments and replies on topic from same members are merged and calculated as one.
* Topics, comments and replies with recommended links get you extra points.
* Topics with only image and lacky description or question aren't rewarded with
  points.
* Topics with disabled comments aren't rewarded with points. Comments are in
  most cases turned off by administrators for various reasons, such as not
  following the group's code of conduct or low quality.
* Offensive messages and inappropriate links can get you negative points.
* Some topics (such as administrators announcements and similar) are awarded with
  extra points as a recognition for special contributions to the group. 

**What is the default points configuration and why such configuration for calculation?**

Configuration for calculating the points aims to encourages the group code of
conduct and recommended netiquette for online communication in internet communities
such as this. All the configuration parameters are described in details in the
[points.yml](app/config/points.yml) and
[offensive_words.yml](app/config/offensive_words.yml) files and have been
carefully set for this group.

**Why some sites are not recommended and get negative points?**

[Answering other people's questions](http://dev-human.com/~mauriciojr/growing-your-experience-with-community-participation).
is a very important part of being a member in development community. Group aims
for quality discussions and not every answer and resource is always the best for
the given situation. To promote the best possible answers, some links are not
recommended and get you negative points. Every developer should know how to use
search engines but question was probably asked for a reason. More experienced
users might suggest better resource than those listed on the first pages of the
search engines.

## Installation

To install this application locally, do the following:

1. Register Facebook Application

2. Install code

    ```bash
    $ git clone git://github.com/wwphp-fb/stats
    $ cd stats
    $ composer install
    ```

3. Adjust configuration

    Add `app/config/parameters.yml` file for your settings:

    ```bash
    fb_app_id: 123456789012312
    fb_app_secret: 9xdlsd93kdcd3jkd
    fb_access_token: xyz
    start_datetime: '2015-07-20 00:00:00'
    end_datetime: '2015-07-26 23:59:59'
    last_member_name: 'John Doe'
    last_blocked_count: 123
    new_blocked_count: 321
    top_topics:
        -
            title: "20 years of PHP"
            url: "fb.com/groups/2204685680/permalink/10153439277470681"
    ```

    Configuration parameters are described in comments in [parameters.yml.dist](app/config/parameters.yml.dist)
    and other default configuration files.

4. Run tests:

    ```bash
    $ phpunit
    ```

5. Generate report

    ```bash
    $ php app/console generate
    ```

## Other useful commands

* Clear all generated logs in the log folder `app/logs`:

```bash
$ php app/console clearlogs
```


## License and Contributing

This repository is released under the [MIT license](LICENSE).

You are welcome to suggest improvements or open a ticket. More info in the 
[CONTRIBUTING](CONTRIBUTING.md) file.
