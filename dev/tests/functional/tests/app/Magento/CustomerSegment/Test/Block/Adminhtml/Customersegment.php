<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;

/**
 * Class Customersegment
 * Customer Segment actions block
 */
class Customersegment extends Block
{
    /**
     * Click save and continue button on form
     *
     * @return void
     */
    public function clickSaveAndContinue()
    {
        $this->_rootElement->find('#save_and_continue_edit')->click();
    }
}
