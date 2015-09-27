# Stats generator for Facebook group

## About

This is a generator for creating stats for our Facebook group.


## FAQ

**How are points calculated?**

Each post, each comment and each reply to comment in the group gets you one point.


## Installation

1. Register Facebook Application

2. Install code
    
    ```bash
    $ git clone git://github.com/wwphp-fb/stats
    $ cd stats
    $ composer install
    ```

3. Adjust configuration
    
    Add and edit `app/config/parameters.yaml` file according to your settings:
    
    ```bash
    $ cp app/config/parameters.yml.dist app/config/parameters.yml
    ```
    
    `parameters.yml` should look like this
    
    ```bash
    fb_app_id: 123456789012312
    fb_app_secret: 9xdlsd93kdcd3jkd
    fb_access_token: xyz
    group_id: 2204685680
    top_users_count: 10
    start_datetime: '2015-07-20 00:00:00'
    end_datetime: '2015-07-26 23:59:59'
    last_member_name: 'John Doe'
    last_blocked_count: 123
    new_blocked_count: 321
    api_pages: 20
    ```
    
    Configuration parameters:
    
    `fb_app_id` - id of the Facebook application from step 1. You can get it in the dashboard [settings](https://developers.facebook.com/apps/) of your Facebook application.
    
    `fb_app_secret` - secret string of the Facebook application from step 1. You can get it in the dashboard [settings](https://developers.facebook.com/apps/) of your Facebook application.
    
    `fb_access_token` - Facebook access token should be copied from your Facebook application [Graph Api Explorer](https://developers.facebook.com/tools/explorer)
    
    `group_id` - Facebook group id
    
    `top_users_count` - how many top users should be shown in the generated report
    
    `start_datetime` - start datetime string of the report
    
    `end_datetime` - end datetime string of the report
    
    `last_member_name` - you should provide a name of added member from previous generated report
    
    `last_blocked_count` - number of blocked accounts from previous report
    
    `new_blocked_count` - number of blocked accounts on the time of report generation
    
    `api_pages` - how many pages should the data collecting include. Large amount of data from the Facebook Graph API gets returned in multiple pages and Facebook group feed is sorted by updated topics.

4. Run tests:

    ```bash
    $ phpunit -c app/
    ```

5. Generate report
    
    ```bash
    $ php app/console
    ```

## License

This repository is released under the [MIT license](LICENSE).
