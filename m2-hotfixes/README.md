# Add custom patches

You can add custom patch files to this directory. All custom patch filenames must have the `.patch` extension, for example `<patch_name>.patch`.
After all Magento patches are applied, custom patches are applied in alphabetical order from the root of the Magento project as shown in the following example:
```
diff -Naur a/vendor/package-name/ClassName.php b/vendor/package-name/ClassName.php
--- a/vendor/package-name/ClassName.php
+++ b/vendor/package-name/ClassName.php
...
```

You can use the following command to apply all Magento patches and your custom patches locally.
```
php ./vendor/bin/ece-patches apply
```
