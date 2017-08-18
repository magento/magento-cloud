<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid;

use Magento\Backend\Test\Block\Widget\Grid;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * VisualMerchandiser Tile view grid component
 */
class TileGrid extends Grid
{
    /**
     * @var string
     */
    protected $addProductDialog = '[data-role="catalog_category_add_product_content"]';

    /**
     * @var string
     */
    protected $rowTemplate = 'li[contains(.,normalize-space("%s"))]';

    /**
     * @var string
     */
    protected $rowTemplateStrict = 'li[text()[normalize-space()="%s"]]';

    /**
     * @var string
     */
    protected $rowPattern = './/ul[@id="catalog_category_merchandiser_list"]/%s';

    /**
     * @var string
     */
    protected $deleteButton = 'a.remove-product';

    /**
     * @param \Magento\Mtf\Fixture\FixtureInterface $fixture
     * @return SimpleElement
     */
    public function getProduct($fixture)
    {
        return $this->getRow(['name' => $fixture->getData('name')], false);
    }

    /**
     * @param SimpleElement $row
     */
    public function deleteProduct($row)
    {
        $row->find($this->deleteButton)->click();
        $this->waitLoader();
    }

    /**
     * @param \Magento\Mtf\Fixture\FixtureInterface $fixture
     * @return bool
     */
    public function isProductVisible($fixture)
    {
        return $this->isRowVisible(['name' => $fixture->getData('name')], false, false);
    }

    /**
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct
     */
    public function getAddProductDialog()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct::class,
            ['element' => $this->browser->find($this->addProductDialog)]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function waitLoader()
    {
        parent::waitLoader();
    }
}
