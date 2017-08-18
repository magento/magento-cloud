<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\Block\Sandbox\Profile;

use Magento\Mtf\Block\Block;

/**
 * Profile entry block.
 */
class Entry extends Block
{
    /**
     * Profile selector in profiles table.
     *
     * @var string
     */
    protected $profileSelector = ".//td[contains(text(), '%s')]";

    /**
     * "Open Editable Version" button selector.
     *
     * @var string
     */
    protected $openEditableSelector = '#openEditProfile';

    /**
     * "Customer Response Pages" link selector.
     *
     * @var string
     */
    protected $responsePagesSelector = '#responsePageTitle';

    /**
     * "Promote to Active" button selector.
     *
     * @var string
     */
    protected $promoteToActiveSelector = '#activateProfile';

    /**
     * Click "Open Editable Version" button.
     *
     * @return void
     */
    public function openEditableVersion()
    {
        $this->_rootElement->find($this->openEditableSelector)->click();
    }

    /**
     * Open Customer Response Pages page.
     *
     * @return void
     */
    public function goToCustomerResponsePages()
    {
        $this->_rootElement->find($this->responsePagesSelector)->click();
    }

    /**
     * Click "Promote to Active" button.
     *
     * @return void
     */
    public function activateProfile()
    {
        $this->_rootElement->find($this->promoteToActiveSelector)->click();
    }
}
