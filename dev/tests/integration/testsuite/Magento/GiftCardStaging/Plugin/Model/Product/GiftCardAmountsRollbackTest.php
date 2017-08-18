<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardStaging\Plugin\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\GiftCard\Api\Data\GiftcardAmountInterfaceFactory;
use Magento\GiftCard\Model\Catalog\Product\Type\Giftcard;
use Magento\GiftCard\Model\Giftcard as GiftCardModel;
use Magento\Staging\Model\Update\VersionHistory;

class GiftCardAmountsRollbackTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppArea adminhtml
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Staging/_files/staging_update.php
     */
    public function testSaveGiftCardWithAmount()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var ProductRepositoryInterface $productRepository */
        $productRepository = $objectManager->create(ProductRepositoryInterface::class);
        $giftCardAmountFactory = $objectManager->create(GiftcardAmountInterfaceFactory::class);
        /** @var VersionHistory $versionHistory */
        $versionHistory = $objectManager->get(VersionHistory::class);

        //Preconditions
        $versionHistory->setCurrentId(175);

        //Create test product
        $product = $objectManager->create(Product::class);
        $amountsData = [
            [
                'value' => 7,
                'website_id' => 0,
            ],
            [
                'value' => 17,
                'website_id' => 0,
            ]
        ];

        $amounts = [];
        foreach ($amountsData as $amountData) {
            $amounts[] = $giftCardAmountFactory->create(['data' => $amountData]);
        }

        $product->setTypeId(Giftcard::TYPE_GIFTCARD)
            ->setAttributeSetId(4)
            ->setWebsiteIds([1])
            ->setName('Simple Gift Card')
            ->setSku('gift-card-with-amount')
            ->setVisibility(Visibility::VISIBILITY_BOTH)
            ->setStatus(Status::STATUS_ENABLED)
            ->setStockData(['use_config_manage_stock' => 0])
            ->setCanSaveCustomOptions(true)
            ->setHasOptions(true)
            ->setAllowOpenAmount(0);

        $extensionAttributes = $product->getExtensionAttributes();
        $extensionAttributes->setGiftcardAmounts($amounts);
        $product->setExtensionAttributes($extensionAttributes);

        $product->setCustomAttribute('giftcard_type', GiftCardModel::TYPE_VIRTUAL);
        $productRepository->save($product);

        //Verification
        $product = $productRepository->get('gift-card-with-amount', false, null, true);
        $amounts = $product->getGiftcardAmounts();

        $this->assertNotEmpty($amounts);
        $this->assertEquals(7, $amounts[0]['value']);
        $this->assertEquals(17, $amounts[1]['value']);
    }
}
