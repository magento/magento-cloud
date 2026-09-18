import { Emirate, ShippingMethod } from '../types/commerce';

export const AVAILABLE_SHIPPING_METHODS: ShippingMethod[] = [
  {
    id: 'amazon_mcf',
    name: 'Amazon Logistics (FBA / Multi-Channel Fulfillment)',
    description: 'Fastest door-to-door fulfillment by Amazon UAE fleet. Live SMS & map tracking.',
    priceAed: 18,
    estimatedDelivery: 'Tomorrow, by 2:00 PM (Guaranteed Amazon Delivery)',
    isAmazonFulfillment: true,
  },
  {
    id: 'express_dubai',
    name: 'Same-Day Dubai Express Delivery',
    description: 'Direct courier dispatch from our Dubai Central Warehouse (orders before 4 PM).',
    priceAed: 25,
    estimatedDelivery: 'Today, within 4 to 6 hours',
    isAmazonFulfillment: false,
  },
  {
    id: 'standard',
    name: 'Standard UAE Emirates Courier',
    description: 'Deliver to any Emirate (Abu Dhabi, Sharjah, Ajman, RAK, Fujairah, UAQ).',
    priceAed: 12,
    estimatedDelivery: '1 to 2 Business Days',
    isAmazonFulfillment: false,
  },
  {
    id: 'click_collect',
    name: 'Click & Collect (Dubai Internet City Hub)',
    description: 'Pick up your order directly from our Dubai showroom & warehouse free of charge.',
    priceAed: 0,
    estimatedDelivery: 'Ready in 1 Hour',
    isAmazonFulfillment: false,
  },
];

/**
 * Calculates shipping options and dynamic delivery dates based on selected Emirate.
 */
export function getShippingMethodsForEmirate(
  emirate: Emirate,
  cartSubtotalAed: number,
  freeThresholdAed: number = 250
): ShippingMethod[] {
  return AVAILABLE_SHIPPING_METHODS.map((method) => {
    // If order exceeds free shipping threshold, standard delivery is FREE
    if (method.id === 'standard' && cartSubtotalAed >= freeThresholdAed) {
      return {
        ...method,
        priceAed: 0,
        description: `FREE delivery across ${emirate} (Orders over AED ${freeThresholdAed})`,
      };
    }

    if (method.id === 'amazon_mcf' && cartSubtotalAed >= freeThresholdAed) {
      return {
        ...method,
        priceAed: 9, // Discounted Amazon Prime-grade delivery
        description: `Subsidized Amazon Logistics delivery across ${emirate}`,
      };
    }

    // Express is only available for Dubai and Abu Dhabi
    if (method.id === 'express_dubai' && emirate !== 'Dubai' && emirate !== 'Abu Dhabi') {
      return {
        ...method,
        name: `Next-Day ${emirate} Express`,
        estimatedDelivery: 'Tomorrow morning',
      };
    }

    return method;
  });
}

/**
 * Generates an authentic Amazon Logistics tracking ID (e.g. TBA984021948201)
 */
export function generateAmazonTrackingId(): string {
  const randomDigits = Math.floor(100000000000 + Math.random() * 900000000000);
  return `TBA${randomDigits}`;
}
