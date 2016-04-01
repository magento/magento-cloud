# Magento 2.0 Enterprise Edition

## Configuration

This is an example of a minimal repository to deploy a Magento 2 Enterprise Edition instance.

This example is based on using the Composer to build the site. You can see there is not much in terms of files committed to this repository.

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

In `.magento/services.yaml` we say we want a MySQL instance, a Redis and a Solr.

The ``composer.json`` will fetch the Magento 2.0 Enterprise Edition, and some configuration scripts to prepare your application.

## Custom modules & themes

Your custom developments should be put into the following folders:

* Modules: `app/code/<Vendor>/`
* Themes: `app/design/frontend/<Vendor>/`
* Language packs: `app/i18n/<Vendor>/`

The Magento vendor folder will be merged into `app/code` and `app/design/frontend` over the build process.
