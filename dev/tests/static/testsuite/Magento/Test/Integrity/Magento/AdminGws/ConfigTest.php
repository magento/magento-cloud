<?php
/**
 * AdminGWS configuration nodes validator
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Magento\AdminGws;

use \Magento\Framework\ObjectManager\ObjectManager;
use \Magento\Framework\App\Router\Base as BaseRouter;
use \Magento\Framework\App\Router\ActionList;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    const CLASSES_XPATH =
        '/config/adminhtml/magento/admingws/*[name()!="controller_predispatch" and name()!="acl_deny"]/*';

    /**
     * @var string
     */
    private $controllerPredispatchXpath = '/config/group[@name="controller_predispatch"]/callback';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var BaseRouter
     */
    private $baseRouter;

    /**
     * @var ActionList
     */
    protected $actionList;

    /**
     * Validate Admingws Predispatch controller configs
     *
     * @see Method where admingws is used:
     *      Magento\AdminGws\Observer\AdminControllerPredispatch::validateControllerPredispatch
     * @return void
     */
    public function testAdminGws()
    {
        $this->markTestIncomplete('MAGETWO-53012,MAGETWO-53426');

        /** @var ObjectManager */
        $this->objectManager = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER)->getObjectManager();
        $options = ['routerId' => 'admin'];
        $this->baseRouter = $this->objectManager->get(BaseRouter::class, $options);
        $this->actionList = $this->objectManager->get(ActionList::class);

        $moduleDirSearch = $this->objectManager->get(\Magento\Framework\Component\DirSearch::class);
        $xmlFiles = $moduleDirSearch->collectFiles(
            \Magento\Framework\Component\ComponentRegistrar::MODULE,
            'etc/{*/admingws.xml,admingws.xml}'
        );

        /** @var string $file */
        foreach ($xmlFiles as $file) {
            $xml = simplexml_load_file($file);

            /** @var \SimpleXMLElement[] $nodes */
            $nodes = $xml->xpath($this->controllerPredispatchXpath) ?: [];
            $errors = [];

            /** @var \SimpleXMLElement $node */
            foreach ($nodes as $node) {
                $actionName = reset($node->attributes()->class);
                $parts = explode('__', $actionName);

                $module = $controller = $action = '';

                if (count($parts) == 3) {
                    list($module, $controller, $action) = $parts;
                } elseif (count($parts) == 2) {
                    list($module, $controller) = $parts;
                }

                $actionClassName = $this->actionList->get(
                    'magento\\' . $module,
                    \Magento\Framework\App\Area::AREA_ADMINHTML,
                    $controller,
                    $action
                );

                if (null === $actionClassName) {
                    $errors[] = $actionName;
                }
            }
            if ($errors) {
                $this->fail(
                    "Classes in \"{$file}\" are related to non-existing routes:\n" . implode(
                        PHP_EOL,
                        $errors
                    ) . PHP_EOL
                );
            }
        }
    }

    public function testEventSubscriberFormat()
    {
        $invoker = new \Magento\Framework\App\Utility\AggregateInvoker($this);
        $invoker(
        /**
         * @param string $file
         */
            function ($file) {
                $xml = simplexml_load_file($file);
                $nodes = $xml->xpath(\Magento\Test\Integrity\Magento\AdminGws\ConfigTest::CLASSES_XPATH) ?: [];
                $errors = [];
                /** @var \SimpleXMLElement $node */
                foreach ($nodes as $node) {
                    $class = implode('\\', array_map('ucfirst', explode('_', $node->getName())));
                    if (!\Magento\Framework\App\Utility\Files::init()->classFileExists($class, $path)) {
                        $errors[] = "'{$node->getName()}' => '{$path}'";
                    }
                }
                if ($errors) {
                    $this->fail(
                        "Invalid class declarations in {$file}. Files are not found in code pools:\n" . implode(
                            PHP_EOL,
                            $errors
                        ) . PHP_EOL
                    );
                }
            },
            \Magento\Framework\App\Utility\Files::init()->getMainConfigFiles()
        );
    }
}
