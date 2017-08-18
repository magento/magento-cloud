<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block;

use Magento\Mtf\Block\Form;

/**
 * Class Behaviour
 * Create new wish list form
 */
class Behaviour extends Form
{
    /**
     * Save button button css selector
     *
     * @var string
     */
    protected $saveButton = '[type="submit"]';

    /**
     * Save wish list
     *
     * @return void
     */
    public function save()
    {
        $this->_rootElement->find($this->saveButton)->click();
    }
}
