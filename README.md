# Magento 2.2.6 Magento Commerce Cloud

This repository contains a sample Magento Commerce (on-premise) version 2.2.6 instance for you to deploy in the cloud. You must have an active Magento Commerce Cloud user license to use the example in this repository.

The example requires the use of [Composer](https://getcomposer.org/doc/) to load and manage dependencies and Magento vendor folders.

## Authentication
You must have an authentication key to access the Magento Commerce repository and to enable install and update commands for your Magento Commerce Cloud project. 
The following method is best to prevent accidental exposure of credentials, such as pushing an `auth.json` file to a public repository.

To add authentication keys using an environment variable:

1.  In the _Project Web UI_, click the configuration icon in the upper left corner.

1.  In the _Configure Project_ view, click the **Variables** tab.

1.  Click **Add Variable**.

1.  In the _Name_ field, enter `env:COMPOSER_AUTH`.

1.  In the _Value_ field, add the following and replace `<public-key>` and `<private-key>` with your Magento Commerce Cloud authentication credentials:

    ```json
    {
       "http-basic": {
          "repo.magento.com": {
          "username": "<public-key>",
          "password": "<private-key>"
        }
      }
    }
    ```

1.  Select **Visible during build** and deselect **Visible at run**.

1.  Click **Add Variable**.

See [Adding Magento authentication keys](https://devdocs.magento.com/guides/v2.2/cloud/setup/first-time-setup-import-prepare.html#auth-json).

## Repository structure
The following is a list of the specific files required for this example to work in the Magento Commerce Cloud:

```bash
.magento/
        /routes.yaml
        /services.yaml
.magento.app.yaml
composer.json
magento-vars.php
php.ini
```

-  `.magento/routes.yaml`—redirects `www` to the naked domain and `php` application to serve HTTP.
-  `.magento/services.yaml`—sets up a MySQL instance, including Redis and ElasticSearch. 
-  `composer.json`—fetches the Magento Enterprise Edition and configuration scripts to prepare your application.

## Developer documentation
See the [Magento Commerce Cloud Guide](http://devdocs.magento.com/guides/v2.2/cloud/bk-cloud.html).
