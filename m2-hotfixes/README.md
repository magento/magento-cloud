# Add custom patches

You can add custom patch files to this directory. All custom patch filenames must have the `.patch` extension, for example `<patch_name>.patch`.
There are rules about patches in this directory:
 - patch file should have extension `.patch`
 - patches are applied in alphabetical sequence
 - patches are applied from the root of Magento project. E.g.
 
    ```
    diff -Naur a/vendor/package-name/ClassName.php b/vendor/package-name/ClassName.php
    --- a/vendor/package-name/ClassName.php
    +++ b/vendor/package-name/ClassName.php
    ...
    ```
    
You can use the following command to test the patch process–applying all Magento patches and your custom patches  locally before you build and deploy to a Cloud environment.
``` 
./vendor/bin/ece-tools patch
```

