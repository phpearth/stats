# Contribution Guidelines

You are most welcome to suggest improvements, send pull requests or open an
[issue](https://github.com/wwphp-fb/stats/issues)

* Fork this repository over GitHub
* Set up your local repository

  ```bash
git clone git@github.com:your_username/stats
cd stats
git remote add upstream git://github.com/wwphp-fb/stats
git config branch.master.remote upstream
```

* Make changes and send pull request

  ```bash
git add .
git commit -m "Fix bug"
git push origin
```

Code follows [PSR-1](http://php-fig.org/psr/psr-1/), [PSR-2](www.php-fig.org/psr/psr-2/)
and [extended code style guide proposal](https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md).
Documentation uses [Markdown](https://daringfireball.net/projects/markdown/)
syntax and follows [cirosantilli/markdown-style-guide](http://www.cirosantilli.com/markdown-style-guide/)
style guide.

To run tests:

```bash
phpunit
```

## Translations

Translation messages for the generated stats report are located in `app/translations`
using the Symfony Translation Component. Variables in messages are in the format
`%variable%`.

### Pluralization for languages with multiple pluralization formats:

* English and languages with 1 plural format:
  "1 comment|%count% comments"

* Russian and languages with 2 plural formats - first for 1 item, second for 2
  items and third for 3 or more items:
  `"1 комментарий|%count% комментария|%count% комментариев"`

* Slovenian and languages with 3 plural formats - first for 1 item, second for 2
  items, third for 3 and 4 items and fourth for 5 or more items:
  `"1 komentar|%count% komentarja|%count% komentarji|%count% komentarjev"`

## GitHub Issues Labels

Labels are used to organize issues and pull requests into manageable categories.
The following labels are used:

* **Bug** - Attached for bugs.
* **Duplicate** - Attached when the same issue or pull request already exists.
* **Enhancement** - New feature.
* **Hacktoberfest** - Attached for open source [Hacktoberfest] event.
* **Invalid** - Attached when
* **Needs Review** - Attached when further review is required.
* **Question** - Attached for questions or discussions.
* **Request** - Attached for new feature requests.
* **Wontfix** - Attached when decided that issue will not be fixed.

## Release Process

*(For repository maintainers)*

This repository follows [semantic versioning](http://semver.org). When source
code changes or new features are implemented, a new version (e.g. `1.x.y`) is
released by the following release process:

* **1. Code Quality:**

    Make sure tests pass:
  ```bash
phpunit
```

    Before releasing new version, check status on
    [Scrutinizer](https://scrutinizer-ci.com/g/wwphp-fb/stats/),
    [Travis CI](https://travis-ci.org/wwphp-fb/stats) and
    [SensioLabsInsight](https://insight.sensiolabs.com/projects/c317a2f5-1fbe-4d76-a93c-8f0d98e61ef6)

* **2. Update Changelog:**

    Create an entry in [CHANGELOG.md](CHANGELOG.md) describing all the notable
    changes from previous release.

* **3. Tag New Release:**

    Tag a new version on [GitHub](https://github.com/wwphp-fb/stats/releases)
    with description of notable changes.


[Hacktoberfest]: https://hacktoberfest.digitalocean.com/
