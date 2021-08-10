# Magento 2.4.3 Magento Commerce Cloud

This repository contains a sample Magento Commerce (on-premise) version 2.4.3 instance for you to deploy in the cloud. You must have an active Magento Commerce Cloud user license to use the example in this repository.

The example requires the use of [Composer](https://getcomposer.org/doc/) to load and manage dependencies and Magento vendor folders.

-  [Authentication](#authentication)
    -  [Authenticating in Docker](#authenticating-in-docker)
-  [Repository structure](#repository-structure)
-  [Developer documentation](#developer-documentation)

## Authentication

You must have an authentication key to access the Magento Commerce repository and to enable install and update commands for your Magento Commerce Cloud project. 
The following method is best to prevent accidental exposure of credentials, such as pushing an `auth.json` file to a public repository. If you plan to use Docker for your local development, then jump to the [Authenticating in Docker](#authenticating-in-docker) section.

To add authentication keys using an environment variable:

1.  In the _Project Web UI_, click the configuration icon in the upper left corner.

1.  In the _Configure Project_ view, click the **Variables** tab.

1.  Click **Add Variable**.

1.  In the _Name_ field, enter `env:COMPOSER_AUTH`.

1.  In the _Value_ field, add the following and replace `<public-key>` and `<private-key>` with your Magento Commerce Cloud authentication credentials.

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

See [Adding Magento authentication keys](https://devdocs.magento.com/cloud/setup/first-time-setup-import-prepare.html#auth-json).

### Authenticating in Docker

You must have an `auth.json` file that contains your Magento Commerce authorization credentials in your Magento Commerce Cloud root directory.

1.  Using a text editor, create an `auth.json` file and save it in your Magento root directory.

1.  Replace <public-key> and <private-key> with your Magento Commerce authentication credentials.

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

1.  Save your changes to `auth.json` file and exit the text editor.

To use Docker for local development, see [Launching a Docker configuration](https://devdocs.magento.com/cloud/docker/docker-config.html).

## Repository structure

The following is a list of the specific files required for this example to work in the Magento Commerce Cloud:

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

## Developer documentation

See the [Magento Commerce Cloud Guide](https://devdocs.magento.com/cloud/bk-cloud.html).

## License
Each Magento source file included in this distribution is licensed under the OSL-3.0 license.

Please see [LICENSE.txt](https://github.com/magento/magento-cloud/blob/master/LICENSE.txt) for the full text of the [Open Software License v. 3.0 (OSL-3.0)](http://opensource.org/licenses/osl-3.0.php).
