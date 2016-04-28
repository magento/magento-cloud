<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block\Adminhtml\Page\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element;

/**
 * Class Versions
 * Versions tab on New Cms page.
 */
class Versions extends Tab
{
    /**
     * Versions block selector
     *
     * @var string
     */
    protected $versions = '#versions';

    /**
     * Returns versions grid
     *
     * @return \Magento\VersionsCms\Test\Block\Adminhtml\Page\Edit\Tab\VersionsGrid
     */
    public function getVersionsGrid()
    {
        return $this->blockFactory->create(
            'Magento\VersionsCms\Test\Block\Adminhtml\Page\Edit\Tab\VersionsGrid',
            ['element' => $this->_rootElement->find($this->versions)]
        );
    }
}
