# Stats Generator for Facebook Groups

[![Latest Version](https://img.shields.io/github/release/wwphp-fb/stats.svg?style=flat-square)](https://github.com/wwphp-fb/stats/releases)
[![Build Status](https://img.shields.io/travis/wwphp-fb/stats/master.svg?style=flat-square)](https://travis-ci.org/wwphp-fb/stats)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/wwphp-fb/stats.svg?style=flat-square)](https://scrutinizer-ci.com/g/wwphp-fb/stats)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6/big.png)](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6)

PHP application for generating stats for Facebook groups.

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

1. [Create](https://developers.facebook.com/) a new Facebook Application.

2. Install Code

    ```bash
    git clone git://github.com/wwphp-fb/stats
    cd stats
    composer install
    ```

3. Provide Configuration Parameters

    The componser installation script creates `app/config/parameters.yml` file
    for your settings. Configuration parameters are described in comments in the
    [parameters.yml.dist](app/config/parameters.yml.dist). Provide values for
    your application, date range and other configuration.

4. Run Tests:

    ```bash
    phpunit
    ```

5. Generate Report

    ```bash
    bin/stats generate
    ```

    To generate the user access token, use
    [Graph API explorer](https://developers.facebook.com/tools/explorer/) and
    select the application created in step 1.

## Other Useful Commands

* Clear all generated logs in the log folder `app/logs`:

```bash
bin/stats clearlogs
```

* Manage [offensive words](/app/config/offensive_words.yml)

```bash
bin/stats offensivewords
```

## License and Contributing

This repository is released under the [MIT license](LICENSE).

You are welcome to [suggest improvements or open a ticket](CONTRIBUTING.md).
