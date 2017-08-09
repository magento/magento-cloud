<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\Constraint;

/**
 * Assert no access to admin ui controller.
 */
class AssertAdminUrlNoAccess extends \Magento\Ui\Test\Constraint\AssertAdminUrlNoAccess
{
    /**
     * Urls to check.
     *
     * @var array
     */
    protected $urls = [
        'mui/index/render/handle/bulk_bulk_details_modal/buttons/1/?namespace=support_report_listing'
        . '&filters%5Bplaceholder%5D=true&paging%5BpageSize%5D=20&paging%5Bcurrent%5D=1&sorting%5Bfield%5D=report_id'
        . '&sorting%5Bdirection%5D=asc&isAjax=true',
    ];
}
