<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogEvent\Model;

class EventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\CatalogEvent\Model\Event
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CatalogEvent\Model\Event::class
        );
    }

    protected function _getDate($time = 'now')
    {
        return date('Y-m-d H:i:s', strtotime($time));
    }

    public function testCRUD()
    {
        $this->_model->setCategoryId(
            1
        )->setDateStart(
            $this->_getDate('-1 day')
        )->setDateEnd(
            $this->_getDate('+1 day')
        )->setDisplayState(
            \Magento\CatalogEvent\Model\Event::DISPLAY_CATEGORY_PAGE
        )->setSortOrder(
            null
        );
        $crud = new \Magento\TestFramework\Entity(
            $this->_model,
            [
                'category_id' => 2,
                'date_start' => $this->_getDate('-1 year'),
                'date_end' => $this->_getDate('+1 month'),
                'display_state' => \Magento\CatalogEvent\Model\Event::DISPLAY_PRODUCT_PAGE,
                'sort_order' => 123
            ]
        );
        $crud->testCrud();
    }
}
