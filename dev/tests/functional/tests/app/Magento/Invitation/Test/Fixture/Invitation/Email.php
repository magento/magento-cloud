<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Fixture\Invitation;

use Magento\Mtf\Fixture\DataSource;

/**
 * Prepare data for email field in Invitation fixture.
 */
class Email extends DataSource
{
    /**
     * @constructor
     * @param array $params
     * @param string $data
     */
    public function __construct(array $params, $data)
    {
        $this->params = $params;
        $emails = explode(',', $data);
        $data = [];
        foreach ($emails as $key => $value) {
            $data['email_' . ($key + 1)] = trim($value);
        }
        $this->data = $data;
    }
}
