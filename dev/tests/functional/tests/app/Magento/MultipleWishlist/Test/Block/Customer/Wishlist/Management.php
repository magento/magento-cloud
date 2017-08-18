<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Customer\Wishlist;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class Management
 * Management wish list block on 'My Wish List' page
 */
class Management extends Block
{
    /**
     * Button "Create New Wish List" selector
     *
     * @var string
     */
    protected $addWishlist = '#wishlist-create-button';

    /**
     * Wish list select selector
     *
     * @var string
     */
    protected $wishlistSelect = '#wishlists-select span';

    /**
     * Options multiple wish list
     *
     * @var string
     */
    protected $wishlistOptions = '.wishlist-select-items';

    /**
     * Item wish list
     *
     * @var string
     */
    protected $wishlistItem = './/a[@title="%s"]';

    /**
     * Notice type selector
     *
     * @var string
     */
    protected $noticeType = '.wishlist-notice';

    /**
     * Locator value for "Delete Wish List" button.
     *
     * @var string
     */
    protected $removeButton = 'button.remove';

    /**
     * Button 'Edit' css selector
     *
     * @var string
     */
    protected $editButton = '.action.edit';

    /**
     * Selector for confirm.
     *
     * @var string
     */
    protected $confirmModal = '._show[data-role=modal]';

    /**
     * Create new wish list
     *
     * @return void
     */
    public function clickCreateNewWishlist()
    {
        $this->_rootElement->find($this->addWishlist)->click();
    }

    /**
     * Selected item wish list by name
     *
     * @param string $wishlistName
     * @return void
     */
    public function selectedWishlistByName($wishlistName)
    {
        $this->_rootElement->find(sprintf($this->wishlistItem, $wishlistName), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Is visible wish list by name
     *
     * @param string $wishlistName
     * @return bool
     */
    public function isWishlistVisible($wishlistName)
    {
        $this->waitWishlistToLoad();
        return $this->_rootElement->find(sprintf($this->wishlistItem, $wishlistName), Locator::SELECTOR_XPATH)
            ->isVisible();
    }

    /**
     * Notice type visibility
     *
     * @param string $type
     * @return bool
     */
    public function isNoticeTypeVisible($type)
    {
        $this->waitWishlistToLoad();
        return $this->_rootElement->find($this->noticeType . '.' . $type)->isVisible();
    }

    /**
     * Delete wish list
     *
     * @return void
     */
    public function removeWishlist()
    {
        $this->_rootElement->find($this->removeButton)->click();
        $element = $this->browser->find($this->confirmModal);
        /** @var \Magento\Ui\Test\Block\Adminhtml\Modal $modal */
        $modal = $this->blockFactory->create(\Magento\Ui\Test\Block\Adminhtml\Modal::class, ['element' => $element]);
        $modal->acceptAlert();
    }

    /**
     * Remove button is visible
     *
     * @return bool
     */
    public function isRemoveButtonVisible()
    {
        $this->waitWishlistToLoad();
        return $this->_rootElement->find($this->removeButton)->isVisible();
    }

    /**
     * Click Edit wish list button
     *
     * @return void
     */
    public function editWishlist()
    {
        $this->_rootElement->find($this->editButton)->click();
    }

    /**
     * Wait multiple wish list to load.
     *
     * @return void
     */
    protected function waitWishlistToLoad()
    {
        $element = $this->addWishlist;
        $parent = $this->_rootElement;
        $parent->waitUntil(
            function () use ($parent, $element) {
                $element = $parent->find($element);
                return $element->isVisible() ? true : null;
            }
        );
    }
}
