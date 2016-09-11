# [Documentation](/README.md#documentation)

## Commands

The following commands are available over command line interface (CLI):

### Generate Report

This is the main command to generate the report based on the provided configuration:

```bash
bin/stats generate
```

The command asks for a Facebook user token, which can be generated via the
[Graph API Explorer](https://developers.facebook.com/tools/explorer).

### Clear Logs

Generated stats reports are also logged in log folder `app/logs`. To clear all
generated logs, the `clear-logs` command is available:

```bash
bin/stats clear-logs
```

### Offensive Words

To manage [offensive words](/app/config/offensive_words.yml)

```bash
bin/stats offensive-words
```
