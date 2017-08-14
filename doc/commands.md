# [Documentation](/README.md#documentation)

## Commands

The following commands are available over command line interface (CLI):

### Generate Report

This is the main command to generate the report based on the provided configuration:

#### Usage

```bash
bin/stats generate --from=2016-09-05 --to=2016-09-11
```

The command asks for a Facebook user token, which can be generated via the
[Graph API Explorer](https://developers.facebook.com/tools/explorer).

#### Available Options

* `--from`

    Start date of the generated stats report. Default value is last monday's date.
    If today is monday it is the monday of the last week. The time is appended
    automatically to define the beginning of the day (`00:00:00`).

* `--to`

    End date of the generated stats report. Default value is the end of the week
    from the last monday. If today is monday it is sunday of the last week. The
    time is appended automatically to define the end of the day (`23:59:59`).


### Clear Logs

Generated stats reports are also logged in log folder `app/logs`. To clear all
generated logs, the `clear-logs` command is available:

```bash
bin/stats clear-logs
```

### Offensive Words

To manage [offensive words](/config/offensive_words.yml)

```bash
bin/stats offensive-words
```
