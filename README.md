# Magento 2.0.15 Enterprise Cloud Edition

This sample repository helps you deploy a Magento 2.0.15 Enterprise Edition (EE) instance in the cloud. You must be a licensed user of Magento Enterprise Cloud Edition to use the example in this repository.

This example is based on using composer to load dependencies and download Magento packages.

## Create an authorization file
To enable you to install and update the Magento software, you must have an `auth.json` file in your project's root directory. `auth.json` contains your Magento EE [authorization credentials](http://devdocs.magento.com/guides/v2.1/install-gde/prereq/connect-auth.html).

In some cases, you might already have `auth.json` so check to see if it exists and has your authentication credentials before you create a new one.

To create a new `auth.json` in the event you don't have one:

1.  Copy the provided sample using the following command:

        cp auth.json.sample auth.json
2.  Open `auth.json` in a text editor.
3.  Replace `<public-key>` and `<private-key>` with your authentication credentials.

    See the following example:

        "http-basic": {
           "repo.magento.com": {
              "username": "<public-key>",
              "password": "<private-key>"
            }
        }
3.  Save your changes to `auth.json` and exit the text editor.

## Required changes for upgrading to 2.0.9 from previous versions
Upgrading to 2.0.9 from previous versions of Magento Enterprise Cloud Edition may require you to make some manual changes 
to the following files, which are both located in your project root directory.:

*   `.magento.app.yaml`, the main project configuration file.
*   `composer.json`, which specifies project dependencies.

Changes are discussed in the sections that follow.

### `.magento.app.yaml`
Open `.magento.app.yaml` in a text editor and update the `deploy` section (which is nested in the `hooks` section) and `crons` sections as follows:

#### deploy section
```
deploy: |
    php ./vendor/magento/magento-cloud-configuration/pre-deploy.php
    php ./bin/magento magento-cloud:deploy
```

#### crons section
```
crons:
        cronrun:
            spec: "*/5 * * * *"
            cmd: "php bin/magento cron:run && php bin/magento cron:run"
```

### `composer.json`
Open `composer.json` and update the `"files"` directive in the `"autoload"` section as follows (do not modify other parts 
of the autoload section):

```
"autoload": {
        . . .
        "files": [
            "app/etc/NonComposerComponentRegistration.php"
        ]
    }
```

Move `app/NonComposerComponentRegistration.php` to `app/etc/NonComposerComponentRegistration.php`.
Make sure the relative paths that point to locations in the app and lib directories reflect the 
new location of the file. For an example that can be copied, see the [copy in this project](app/etc/NonComposerComponentRegistration.php).

Update the `require` section as follows to:

*   Replace `"magento/product-enterprise-edition": "<current version>",` with `"magento/magento-cloud-metapackage": "<upgrade version>",`
*   Remove `"magento/magento-cloud-configuration": "<current version>",`

(In some cases, your `composer.json` might already be correct.)

Exmaple:

```
 ...
    "require": {
        "magento/magento-cloud-metapackage": "2.0.15",
    },
 ...
```

Run `composer update`, and make sure the updated composer.lock and other changed files are
checked in to git.

## Repository structure
Here are the specific files for this example to work on Magento Enterprise Cloud Edition:

```
.magento/
         /routes.yaml
         /services.yaml
.magento.app.yaml
auth.json
composer.json
magento-vars.php
php.ini
```

`.magento/routes.yaml` redirects `www` to the naked domain, and that the application that will be serving HTTP is named `php`.

`.magento/services.yaml` sets up a MySQL instance, plus Redis and Solr. 

``composer.json`` fetches the Magento Enterprise Edition and some configuration scripts to prepare your application.

## Documentation
For more details, see our [Magento Enterprise Cloud Guide](http://devdocs.magento.com/guides/v2.1/cloud/bk-cloud.html). 

Get started [here](http://devdocs.magento.com/guides/v2.0/cloud/before/before.html).
