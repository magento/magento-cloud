<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardImportExport\Model;

use Magento\CatalogImportExport\Model\AbstractProductExportImportTestCase;

class GiftCardTest extends AbstractProductExportImportTestCase
{
    public function exportImportDataProvider()
    {
        return [
            'gift-card' => [
                [
                    'Magento/GiftCard/_files/gift_card.php'
                ],
                [
                    'gift-card',
                ],
            ],
            'gift-card-with-message' => [
                [
                    'Magento/GiftCard/_files/gift_card_with_available_message.php'
                ],
                [
                    'gift-card-with-allowed-messages',
                ]
            ]
        ];
    }

    public function importReplaceDataProvider()
    {
        return $this->exportImportDataProvider();
    }

    /**
     * @param array $skus
     */
    protected function modifyData($skus)
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create($skus, $this);
    }

    /**
     * @param array $fixtures
     * @param string[] $skus
     * @param string[] $skippedAttributes
     * @dataProvider exportImportDataProvider
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @todo remove after MAGETWO-49466 resolved
     */
    public function testExport($fixtures, $skus, $skippedAttributes = [], $rollbackFixtures = [])
    {
        $this->markTestSkipped('Uncomment after MAGETWO-49467 resolved');
    }

    /**
     * @param array $fixtures
     * @param string[] $skus
     * @dataProvider exportImportDataProvider
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @todo remove after MAGETWO-49466 resolved
     */
    public function testImportDelete($fixtures, $skus, $skippedAttributes = [], $rollbackFixtures = [])
    {
        $this->markTestSkipped('Uncomment after MAGETWO-49467 resolved');
    }

    /**
     * @param array $fixtures
     * @param string[] $skus
     * @param string[] $skippedAttributes
     * @dataProvider importReplaceDataProvider
     */
    public function testImportReplace($fixtures, $skus, $skippedAttributes = [], $rollbackFixtures = [])
    {
        $this->markTestSkipped('Uncomment after MAGETWO-49467 resolved');
    }

    /**
     * @param \Magento\Catalog\Model\Product $expectedProduct
     * @param \Magento\Catalog\Model\Product $actualProduct
     */
    protected function assertEqualsSpecificAttributes($expectedProduct, $actualProduct)
    {
        foreach ($this->getFieldsToCompare() as $fieldKey => $fieldValue) {
            if (is_array($fieldValue)) {
                if (count($expectedProduct->getData($fieldKey)) > 0) {
                    foreach ($fieldValue as $field) {
                        $valueMatchFound = false;
                        foreach ($expectedProduct->getData($fieldKey) as $expectedData) {
                            $this->assertArrayHasKey($field, $expectedData);
                            foreach ($actualProduct->getData($fieldKey) as $actualData) {
                                $this->assertArrayHasKey($field, $actualData);
                                if ($expectedData[$field] == $actualData[$field]) {
                                    $valueMatchFound = true;
                                    break 2;
                                }
                            }
                        }
                        $this->assertTrue($valueMatchFound, $fieldKey . ' not found');
                    }
                }
            } else {
                $this->assertEquals($expectedProduct->getData($fieldKey), $actualProduct->getData($fieldKey));
            }
        }
    }

    /**
     * Get array of GiftCard Product field mapping to compare
     *
     * @return array
     */
    private function getFieldsToCompare()
    {
        return [
            'sku' => false,
            'giftcard_type' => false,
            'is_redeemable' => false,
            'lifetime' => false,
            'allow_message' => false,
            'giftcard_amounts' => ['value']
         ];
    }
}
