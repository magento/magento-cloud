<?php
/**
 * AdminGWS configuration nodes validator
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Legacy\Magento\AdminGws;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
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
                /** @var SimpleXMLElement $node */
                foreach ($nodes as $node) {
                    if (preg_match('/\_\_/', $node->getName())) {
                        $errors[] = $node->getName();
                    }
                }
                if ($errors) {
                    $this->fail("Obsolete class names detected in {$file}:\n" . implode(PHP_EOL, $errors) . PHP_EOL);
                }
            },
            \Magento\Framework\App\Utility\Files::init()->getMainConfigFiles()
        );
    }
}
