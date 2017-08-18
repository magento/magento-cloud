<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block;

/**
 * Cms Page block for the content on the frontend.
 */
class Page extends \Magento\Cms\Test\Block\Page
{
    /**
     * Cms menu
     *
     * @var string
     */
    protected $cmsMenu = ".cms-menu";

    /**
     * Check is visible cms menu
     *
     * @return bool
     */
    public function cmsMenuIsVisible()
    {
        return $this->_rootElement->find($this->cmsMenu)->isVisible();
    }
}
