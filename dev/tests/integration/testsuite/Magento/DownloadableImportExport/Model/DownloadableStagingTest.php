<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\DownloadableImportExport\Model;

class DownloadableStagingTest extends DownloadableTest
{
    /**
     * @param array $skus
     */
    protected function modifyData($skus)
    {
        $this->objectManager->get(\Magento\CatalogImportExport\Model\Version::class)->create($skus, $this);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     */
    public function prepareProduct($product)
    {
        $extensionAttributes = $product->getExtensionAttributes();
        $downloadableProductLinks = $extensionAttributes->getDownloadableProductLinks();
        /** @var \Magento\Downloadable\Api\Data\LinkInterfaceFactory $linkFactory */
        $linkFactory = $this->objectManager->get(\Magento\Downloadable\Api\Data\LinkInterfaceFactory::class);
        $links = [];
        foreach ($downloadableProductLinks as $link) {
            $linkData = $link->getData();
            unset(
                $linkData['id'],
                $linkData['link_id']
            );
            $link = $linkFactory->create(['data' => $linkData]);
            $links[] = $link;
        }
        $extensionAttributes->getDownloadableProductLinks($links);
        $product->setExtensionAttributes($extensionAttributes);

        $downloadableProductSamples = $extensionAttributes->getDownloadableProductSamples();
        if ($downloadableProductSamples) {
            /** @var \Magento\Downloadable\Api\Data\SampleInterfaceFactory $sampleFactory */
            $sampleFactory = $this->objectManager->create(\Magento\Downloadable\Api\Data\SampleInterfaceFactory::class);
            $samples = [];
            foreach ($downloadableProductSamples as $sample) {
                $sampleData = $sample->getData();
                unset(
                    $sampleData['id'],
                    $sampleData['sample_id']
                );
                $sample = $sampleFactory->create(['data' => $sampleData]);
                $samples[] = $sample;
            }
            $extensionAttributes->setDownloadableProductSamples($samples);
        }
        $product->unsDownloadableLinks()
            ->unsDownloadableSamples();
    }
}
