<?php
/**
 * Validator of class names in Reward nodes
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Magento\Reward;

class LayoutTest extends \PHPUnit\Framework\TestCase
{
    public function testInitRewardTypeClasses()
    {
        $invoker = new \Magento\Framework\App\Utility\AggregateInvoker($this);
        $invoker(
            /**
             * @param string $file
             */
            function ($file) {
                $xml = simplexml_load_file($file);
                $nodes = $xml->xpath('//argument[@name="reward_type"]') ?: [];
                $errors = [];
                /** @var \SimpleXMLElement $node */
                foreach ($nodes as $node) {
                    $class = (string)$node;
                    if (!\Magento\Framework\App\Utility\Files::init()->classFileExists($class, $path)) {
                        $errors[] = "'{$class}' => '{$path}'";
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
            \Magento\Framework\App\Utility\Files::init()->getLayoutFiles()
        );
    }
}
