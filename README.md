# Magento 2.2.2 Magento Commerce (Cloud)

This repository contains a sample Magento Commerce (on-premise) version 2.2.2 instance for you to deploy in the cloud. You must have an active Magento Commerce (Cloud) user license to use the sample in this repository.

The following example requires the use of [Composer](https://getcomposer.org/doc/) to load and manage dependencies and Magento vendor folders.

## Create an authorization file
You must have an `auth.json` file in your Project root directory before you can install and update the Magento software.

The `auth.json` file contains your Magento Commerce (on-premise) [authorization credentials](http://devdocs.magento.com/guides/v2.2/install-gde/prereq/connect-auth.html). Do not create a new `auth.json` file if you already have a file with your valid, authentication credentials.

#### To create a new `auth.json` file:

1.  Copy the provided sample.

    ```
    cp auth.json.sample auth.json
    ```

2.  Open `auth.json` in a text editor.
3.  Replace `<public-key>` and `<private-key>` with your authentication credentials.

    ```json
    "http-basic": {
       "repo.magento.com": {
          "username": "<public-key>",
          "password": "<private-key>"
        }
    }
    ```

3.  Save your changes to `auth.json` and exit the text editor.

## Repository structure
The following is a list of the specific files required for this example to work on Magento Commerce (Cloud):

```bash
.magento/
         /routes.yaml
         /services.yaml
.magento.app.yaml
auth.json
composer.json
magento-vars.php
php.ini
```

-  `.magento/routes.yaml`—redirects `www` to the naked domain and `php` application to serve HTTP.
-  `.magento/services.yaml`—sets up a MySQL instance, including Redis and ElasticSearch. 
-  `composer.json`—fetches the Magento Enterprise Edition and configuration scripts to prepare your application.

## Documentation
See the [Magento Commerce Cloud Guide](http://devdocs.magento.com/guides/v2.2/cloud/bk-cloud.html). 

