# Add custom patches

You can add custom patch files to this directory. Each patch file must use the `.patch` extension, for example the `<patch_name>.patch`.
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
    
To test applying all patches (your custom patches and cloud patches) which is made during the deployment, you can run next command locally:
``` 
./vendor/bin/ece-tools patch
```

