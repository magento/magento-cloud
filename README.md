# Magento 2.2.6 Magento Commerce (Cloud)

This sample repository helps you deploy a Magento 2.2.6 Enterprise Edition (EE) instance in the cloud. You must be a licensed user of Magento Magento Commerce (Cloud) to use the example in this repository.

This example is based on using the Composer to load dependencies and get the Magento vendor folders.

## Create an authorization file
To enable you to install and update the Magento software, you must have an `auth.json` file in your project's root directory. `auth.json` contains your Magento EE [authorization credentials](http://devdocs.magento.com/guides/v2.2/install-gde/prereq/connect-auth.html).

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

## Repository structure
Here are the specific files for this example to work on Magento Magento Commerce (Cloud):

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
For more details, see our [Magento Enterprise Cloud Guide](http://devdocs.magento.com/guides/v2.2/cloud/bk-cloud.html). 

