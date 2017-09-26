<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Controller;

use \Magento\Setup\Controller\WebConfiguration;

class WebConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function testIndexAction()
    {
        /** @var $controller WebConfiguration */
        $controller = new WebConfiguration();
        $_SERVER['DOCUMENT_ROOT'] = 'some/doc/root/value';
        $viewModel = $controller->indexAction();
        $this->assertInstanceOf(\Zend\View\Model\ViewModel::class, $viewModel);
        $this->assertTrue($viewModel->terminate());
        $this->assertArrayHasKey('autoBaseUrl', $viewModel->getVariables());
    }
}
