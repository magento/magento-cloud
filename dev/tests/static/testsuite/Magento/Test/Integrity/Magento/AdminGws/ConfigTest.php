<?php
/**
 * AdminGWS configuration nodes validator
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Magento\AdminGws;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    const CLASSES_XPATH =
        '/config/adminhtml/magento/admingws/*[name()!="controller_predispatch" and name()!="acl_deny"]/*';

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
