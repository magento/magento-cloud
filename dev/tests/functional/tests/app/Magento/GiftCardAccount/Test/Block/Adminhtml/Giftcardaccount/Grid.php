<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block\Adminhtml\Giftcardaccount;

/**
 * Gift card account grid block.
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Locator for edit link.
     *
     * @var string
     */
    protected $editLink = '.col-code';

    /**
     *  Name for 'Sort' link.
     *
     * @var string
     */
    protected $sortLinkName = 'giftcardaccount_id';

    /**
     * Initialize block elements.
     *
     * @var array
     */
    protected $filters = [
        'code' => [
            'selector' => '#giftcardaccountGrid_filter_code',
        ],
        'balanceFrom' => [
            'selector' => '#giftcardaccountGrid_filter_balance_from',
        ],
        'balanceTo' => [
            'selector' => '#giftcardaccountGrid_filter_balance_to',
        ],
        'state' => [
            'selector' => '#giftcardaccountGrid_filter_state',
        ],
        'date_expires' => [
            'selector' => 'date_expires',
        ],
    ];

    /**
     * Search for item and select it.
     *
     * @param array $filter
     * @param bool $isSearchable [optional]
     * @throws \Exception
     */
    public function searchAndOpen(array $filter, $isSearchable = false)
    {
        $this->sortGridByField($this->sortLinkName);
        $selectItem = $this->getRow($filter, $isSearchable, false);
        if ($selectItem->isVisible()) {
            $selectItem->find($this->editLink)->click();
        } else {
            throw new \Exception("Searched item was not found by filter\n" . print_r($filter, true));
        }
    }

    /**
     * Search for item and select it.
     *
     * @param array $filter
     * @param bool $isSearchable [optional]
     * @return string
     * @throws \Exception
     */
    public function getCode(array $filter, $isSearchable = false)
    {
        $this->sortGridByField($this->sortLinkName);
        $selectItem = $this->getRow($filter, $isSearchable, false);
        if ($selectItem->isVisible()) {
            return $selectItem->find($this->editLink)->getText();
        } else {
            throw new \Exception("Searched item was not found by filter\n" . print_r($filter, true));
        }
    }
}
