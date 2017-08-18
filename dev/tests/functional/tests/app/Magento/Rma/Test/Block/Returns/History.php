<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Returns;

use Magento\Rma\Test\Block\Returns\History\RmaRow;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Rma of order grid block.
 */
class History extends Block
{
    /**
     * Selector for rma row by id.
     *
     * @var string
     */
    protected $rmaRowById = './/tbody/tr[./*[contains(@class,"col id") and contains(text(),"%s")]]';

    /**
     * Get rma row.
     *
     * @param Rma $rma
     * @return RmaRow
     */
    public function getRmaRow(Rma $rma)
    {
        $locator = sprintf($this->rmaRowById, $rma->getEntityId());
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Returns\History\RmaRow::class,
            ['element' => $this->_rootElement->find($locator, Locator::SELECTOR_XPATH)]
        );
    }
}
