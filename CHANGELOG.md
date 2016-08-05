This changelog lists changes specifically related to Magento Enterprise Cloud Edition starting with the 101.0.1 release. The 101.0.x releases provide patches and component updates for Cloud. We recommend you use the latest release for best results. 

(Magento Enterprise Cloud Edition release 101.0.1 corresponds to the Magento 2.1.0 product release.)

To get detailed information about changes in the Magento 2.1.0 product, see the [Magento Enterprise Edition (EE) Release Notes](http://devdocs.magento.com/guides/v2.1/release-notes/ReleaseNotes2.1.0EE.html).

## 101.0.2
*	Implemented a pre-deploy hook to clear the cache and other tasks
*	Fixed an issue where upgrade from 2.0.x doesn't work without clearing the cache
*	Fixed an issue where a newly created branch would redirect to master environment if `magento setup:upgrade` failed
*	Fixed an issue where `NonComposerComponentRegistration.php` prevents installation of custom modules
*	Fixed an issue where session and application cache were using same Redis database
*	Fixed an issue where https was not configured properly by default

## 101.0.1 (initial version supporting the Magento 2.1.0 product release)
*   Clear `var/cache` and Redis cache before running the deploy hook


