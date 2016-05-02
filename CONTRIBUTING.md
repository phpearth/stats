# Contribution Guidelines

You are most welcome to suggest improvements, or send a pull requests. In case
you find an issue, please report an
[issue](https://github.com/wwphp-fb/stats/issues)

* Fork this repository over GitHub
* Set up your local repository

  ```bash
$ git clone git@github.com:your_username/stats
$ cd stats
$ git remote add upstream git://github.com/wwphp-fb/stats
$ git config branch.master.remote upstream
```
* Make changes and send pull request

  ```bash
$ git add .
$ git commit -m "Fix bug"
$ git push origin
```


## Release Process

*(For repository maintainers)*

This repository follows [semantic versioning](http://semver.org). When source
code changes or new features are implemented, a new version (e.g. `1.x.y`) is
released by the following release process:


* **1. Update Changelog:**

    Create an entry in [CHANGELOG.md](CHANGELOG.md) describing all the notable
    changes from previous release.

* **2. Tag new release:**

    Tag a new version on [GitHub](https://github.com/wwphp-fb/stats/releases)
    with description of notable changes.
