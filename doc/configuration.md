# [Documentation](/README.md#documentation)

## Configuration

When installing application with Composer the configuration parameters are added
to the ignored `config/parameters.yml` file.

The following parameters are used to configure the report generation:

| Parameter                | Type      | Description |
|--------------------------|-----------|-------------|
| fb_app_id                | int       | Facebook application ID |
| fb_app_secret            | string    | Facebook application secret key |
| default_graph_version    | string    | Default Facebook Graph API version |
| group_id                 | int       |  |
| locale                   | string    | Locale for the report |
| top_users_count          | int       |  |
| last_member_name         | string    |  |
| last_blocked_count       | int       |  |
| new_blocked_count        | int       |  |
| api_pages                | int       |  |
| top_topics               | array     |  |
| ignored_users            | array     |  |
| admins                   | array     |  |
| offset                   | int       | Adjust the data from Graph API |
