# Magento 2.0 Enterprise Edition

This sample repository helps you deploy a Magento 2 Enterprise Edition (EE) instance in the cloud. You must be a licensed user of Magento Enterprise Cloud Edition to use the example in this repository.

This example is based on using the Composer to load dependencies and get the Magento vendor folders.

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

`.magento.app.yaml` specifies a basic configuration of our application (named ``mymagento``), saying this is a
Composer based application, that we depend on a database called `database` and that we what to run a build script and a deploy script during deployment, and also set up some cron jobs.

`.magento/routes.yaml` redirects `www` to the naked domain, and that the application that will be serving HTTP is named `php`.

`.magento/services.yaml` sets up a MySQL instance, plus Redis and Solr. 

``composer.json`` fetches the Magento Enterprise Edition and some configuration scripts to prepare your application.

Make sure you add your Magento credentials to the `auth.json` file based on the `auth.json.sample` example and that those credentials can get you access to Magento Enterprise Edition. You can get those credentials in your [magento.com account](https://www.magentocommerce.com/magento-connect/customerdata/accessKeys/list).

```
"http-basic": {
      "repo.magento.com": {
         "username": "<public-key>",
         "password": "<private-key>"
      }
   }
```

## Documentation
For more details, see our [Magento Enterprise Cloud Guide](http://devdocs.magento.com/guides/v2.0/cloud/bk-cloud.html). To get started, start [here](http://devdocs.magento.com/guides/v2.0/cloud/before/before.html).