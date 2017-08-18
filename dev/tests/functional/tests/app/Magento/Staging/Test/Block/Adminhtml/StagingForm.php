<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Form;

/**
 * Update campaign form.
 */
class StagingForm extends Form
{
    /**
     * Error message css selector.
     *
     * @var string
     */
    private $errorMessage = '.message-error';

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->browser->find($this->errorMessage)->getText();
    }
}
