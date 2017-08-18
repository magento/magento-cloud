<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Adminhtml\Customer\Edit\Tab\Balance\History;

use Magento\Backend\Test\Block\Widget\Grid as ParentGrid;
use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\Mtf\Client\Locator;

/**
 * Balance history grid.
 */
class Grid extends ParentGrid
{
    /**
     * More information description template.
     *
     * @var string
     */
    protected $moreInformation = "By admin: admin. (%s)";

    /**
     * Customer notified mapping.
     *
     * @var array
     */
    protected $customerNotified = ['Yes' => 'Notified', 'No' => 'No'];

    /**
     * Table header row selector.
     *
     * @var string
     */
    protected $tableHeader = 'thead';

    /**
     * Verify value in balance history grid.
     *
     * @param CustomerBalance $customerBalance
     * @return bool
     */
    public function verifyCustomerBalanceGrid(CustomerBalance $customerBalance)
    {
        $this->waitGridToLoad();
        $moreInformation = $customerBalance->getAdditionalInfo();
        $gridRowValue = './/tr[td[contains(.,"' . abs($customerBalance->getBalanceDelta()) . '")]';
        $customerNotified = $this->customerNotified[$customerBalance->getIsCustomerNotified()];
        $gridRowValue .= ' and td[contains(.,"' .  $customerNotified . '")]';
        if ($moreInformation) {
            $gridRowValue .= ' and td["' . sprintf($this->moreInformation, $moreInformation) . '"]';
        }
        $gridRowValue .= ']';

        return $this->_rootElement->find($gridRowValue, Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Wait customer balance history grid to load.
     *
     * @return void
     */
    protected function waitGridToLoad()
    {
        $rootElement = $this->_rootElement;
        $selector = $this->tableHeader;
        $rootElement->waitUntil(
            function () use ($rootElement, $selector) {
                $element = $rootElement->find($selector);
                return $element->isVisible() ? true : null;
            }
        );
    }
}
