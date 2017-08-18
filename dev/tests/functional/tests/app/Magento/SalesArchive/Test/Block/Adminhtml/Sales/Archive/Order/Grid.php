<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Block\Adminhtml\Sales\Archive\Order;

use Magento\Mtf\Client\Locator;

/**
 * Sales order grid.
 */
class Grid extends \Magento\Ui\Test\Block\Adminhtml\DataGrid
{
    /**
     * 'Add New' order button.
     *
     * @var string
     */
    protected $addNewOrder = "../*[@class='page-actions']//*[@id='add']";

    /**
     * Purchase Point Filter selector.
     *
     * @var string
     */
    protected $purchasePointFilter = '//*[@data-ui-id="widget-grid-column-filter-store-0-filter-store-id"]';

    /**
     * Purchase Point Filter option group elements selector.
     *
     * @var string
     */
    protected $purchasePointOptGroup = '//*[@data-ui-id="widget-grid-column-filter-store-0-filter-store-id"]/optgroup';

    /**
     * Order Id td selector.
     *
     * @var string
     */
    protected $editLink = 'a.action-menu-item ';

    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'id' => [
            'selector' => '[name="increment_id"]',
        ],
        'status' => [
            'selector' => '[name="status"]',
            'input' => 'select',
        ],
    ];

    /**
     * Start to create new order.
     */
    public function addNewOrder()
    {
        $this->_rootElement->find($this->addNewOrder, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Get StoreGroup list of Purchase Point on filter.
     *
     * @return array
     */
    public function getPurchasePointStoreGroups()
    {
        $storeGroupElements = $this->_rootElement->find($this->purchasePointFilter, Locator::SELECTOR_XPATH)
            ->getElements('.//optgroup[./option]', Locator::SELECTOR_XPATH);
        $result = [];

        foreach ($storeGroupElements as $storeGroupElement) {
            $result[] = trim($storeGroupElement->getAttribute('label'), ' ');
        }

        return $result;
    }
}
