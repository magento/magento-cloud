# Add custom patches

In this directory you may add your custom patches which will be applied on the Build phase during the deployment.
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

