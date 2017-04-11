<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model\Update;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Model\Update\Flag;

class FlagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Flag
     */
    protected $model;

    protected function setUp()
    {
        $this->model = Bootstrap::getObjectManager()
            ->create(Flag::class);
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testSetAndGetCurrentVersionId()
    {
        $valueForTest = 1488793140;

        $this->model->setCurrentVersionId($valueForTest);
        $this->model->save();
        $result = $this->model->getCurrentVersionId();

        $this->assertEquals($valueForTest, $result);
    }
}
