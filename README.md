# Magento 2.0 Enterprise Edition

This is a no-thrills example of a minimal repository to deploy a Magento 2 Enterprise Edition instance.

This example is based on using the Composer to load up dependencies and get the Magento vendor folders.

## Repository structure

Here are the specific files for this example to work on Platform.sh:

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

in `.magento.app.yaml` we have the basic configuration of our application (we call it ``mymagento``), saying this is a
Composer based application, that we depend on a database called `database` and that we what to run a build script and a
deploy script during deployment.. and also set up some crons.

In `.magento/routes.yaml` we just say that we will redirect www to the naked domain, and that the application that
will be serving HTTP will be the one we called `php`.

In `.magento/services.yaml` we say we want a MySQL instance, a Redis and a Solr. That would cover most basic Magento
needs, right?

The ``composer.json`` will fetch the Magento 2.0 Enterprise Edition, and some configuration scripts to prepare your application.

Make sure you add your Magento credentials to the `auth.json` file based on the `auth.json.sample` example and that those credentials can get you access to Magento Enterprise Edition. You can get those credentials in your [MagentoCommerce account](https://www.magentocommerce.com/magento-connect/customerdata/accessKeys/list/).

```
"http-basic": {
      "repo.magento.com": {
         "username": "<public-key>",
         "password": "<private-key>"
      }
   }
```
