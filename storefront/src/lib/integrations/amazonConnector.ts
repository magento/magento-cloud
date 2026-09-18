import { AmazonSettings, Product } from '../types/commerce';

export const DEFAULT_AMAZON_SETTINGS: AmazonSettings = {
  enabled: true,
  sellerId: 'A37Q892UAE_MERCHANT',
  marketplaceId: 'A2VIGQ35RCS4UG', // Amazon.ae UAE Marketplace ID
  spApiEndpoint: 'https://sellingpartnerapi-eu.amazon.com',
  enableAmazonDeliveryMcf: true,
  enableAmazonUpsells: true,
  enableBuyOnAmazonButton: true,
  defaultMcfCarrier: 'Amazon Logistics UAE',
  lastSyncTime: new Date(Date.now() - 1800000).toISOString(),
  connectionStatus: 'connected',
};

/**
 * Generates an Amazon.ae product URL for the given ASIN.
 */
export function getAmazonProductUrl(asin: string): string {
  return `https://www.amazon.ae/dp/${asin}?tag=magentouaestore-21`;
}

/**
 * Returns upsell / cross-sell bundle recommendations for a given product.
 */
export function getAmazonUpsellBundle(
  currentProduct: Product,
  allProducts: Product[]
): {
  mainProduct: Product;
  bundleItems: Product[];
  totalBundlePriceAed: number;
  bundleDiscountPercent: number;
  discountedBundlePriceAed: number;
} {
  // Find 1 or 2 complementary products from the same or related categories
  const candidates = allProducts.filter(
    (p) => p.id !== currentProduct.id && (p.category === currentProduct.category || p.brand === currentProduct.brand)
  );

  const selectedBundleItems = candidates.length >= 2 ? candidates.slice(0, 2) : allProducts.filter((p) => p.id !== currentProduct.id).slice(0, 2);

  const rawTotal = currentProduct.basePriceAed + selectedBundleItems.reduce((sum, item) => sum + item.basePriceAed, 0);
  const bundleDiscountPercent = 10; // 10% bundle discount
  const discounted = rawTotal * (1 - bundleDiscountPercent / 100);

  return {
    mainProduct: currentProduct,
    bundleItems: selectedBundleItems,
    totalBundlePriceAed: rawTotal,
    bundleDiscountPercent,
    discountedBundlePriceAed: Number(discounted.toFixed(2)),
  };
}
