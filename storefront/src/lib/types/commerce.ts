export type CurrencyCode = 'AED' | 'USD' | 'INR';

export interface ExchangeRates {
  base: 'AED';
  rates: {
    AED: number; // 1.0000
    USD: number; // ~0.2723 (fixed peg: 3.6725 AED/USD)
    INR: number; // ~23.15 (live market rate)
  };
  lastUpdated: string;
  source: 'live' | 'fixed' | 'manual';
}

export type Emirate =
  | 'Dubai'
  | 'Abu Dhabi'
  | 'Sharjah'
  | 'Ajman'
  | 'Ras Al Khaimah'
  | 'Fujairah'
  | 'Umm Al Quwain';

export interface ProductVariant {
  id: string;
  sku: string;
  barcode?: string;
  name: string; // e.g., "Midnight Black / 256GB" or "Size 42 / Navy"
  options: {
    [key: string]: string; // e.g., { "Color": "Midnight Black", "Size": "256GB" }
  };
  priceAdjustmentAed: number; // difference from base price in AED
  stock: number;
  image?: string;
}

export interface ProductReview {
  id: string;
  author: string;
  rating: number; // 1 to 5
  date: string;
  title: string;
  comment: string;
  verifiedPurchase: boolean;
  location?: string; // e.g. "Dubai, UAE"
}

export interface Product {
  id: string;
  sku: string;
  barcode?: string;
  title: string;
  slug: string;
  description: string;
  shortDescription?: string;
  category: string;
  brand: string;
  basePriceAed: number; // Base price in UAE Dirhams (AED)
  compareAtPriceAed?: number;
  costPriceAed?: number;
  featuredImage: string;
  galleryImages: string[];
  stock: number; // Online warehouse available inventory
  lowStockThreshold: number;
  hasVariants: boolean;
  variants: ProductVariant[];
  tags: string[];
  rating: number;
  reviewCount: number;
  reviews?: ProductReview[];
  amazonAsin?: string; // e.g. "B09G9FPHY6" for Amazon.ae sync
  amazonPriceAed?: number;
  odooProductId?: number;
  isFeatured?: boolean;
  isTrending?: boolean;
  isFlashDeal?: boolean;
  flashDealEndsAt?: string;
  createdAt: string;
  updatedAt: string;
}

export interface CartItem {
  id: string; // unique item id in cart (product.id + variant.id)
  product: Product;
  selectedVariant?: ProductVariant;
  quantity: number;
  unitPriceAed: number;
}

export interface DiscountCoupon {
  code: string;
  type: 'percentage' | 'fixed_aed';
  value: number; // e.g. 10 (%) or 50 (AED)
  minSpendAed?: number;
  description: string;
}

export interface ShippingMethod {
  id: 'standard' | 'express_dubai' | 'amazon_mcf' | 'click_collect';
  name: string;
  description: string;
  priceAed: number;
  estimatedDelivery: string;
  isAmazonFulfillment?: boolean;
}

export type PaymentMethodType =
  | 'stripe'
  | 'tabby'
  | 'tamara'
  | 'bank_transfer'
  | 'cod';

export interface BankAccountDetails {
  bankName: string;
  accountTitle: string;
  accountNumber: string;
  iban: string; // e.g. AE070331234567890123456
  swiftBic: string;
  routingCode?: string;
  branchName?: string;
}

export interface CustomerAddress {
  fullName: string;
  email: string;
  phone: string; // UAE format: +971 50 ...
  emirate: Emirate;
  area: string; // e.g., Downtown, Marina, Business Bay, Al Reem Island
  streetAddress: string;
  buildingVilla: string;
  apartmentSuite?: string;
  postalCode?: string;
  country: string; // "United Arab Emirates"
  trnNumber?: string; // For B2B buyers
}

export interface OrderItem {
  productId: string;
  sku: string;
  title: string;
  variantName?: string;
  quantity: number;
  unitPriceAed: number;
  totalPriceAed: number;
  image: string;
}

export interface TaxInvoice {
  invoiceNumber: string; // e.g. "INV-2026-0042"
  trnSeller: string; // UAE Tax Registration Number (e.g. 100234567800003)
  date: string;
  subtotalAed: number;
  vatRatePercent: number; // 5%
  vatAmountAed: number;
  shippingAed: number;
  discountAed: number;
  grandTotalAed: number;
  grandTotalSelectedCurrency: number;
  currencyCode: CurrencyCode;
  exchangeRateUsed: number;
  qrPayload: string; // UAE FTA QR code standard payload
}

export interface Order {
  id: string;
  orderNumber: string; // e.g. "ORD-98214"
  createdAt: string;
  customer: CustomerAddress;
  items: OrderItem[];
  subtotalAed: number;
  vatAmountAed: number;
  shippingAmountAed: number;
  discountAmountAed: number;
  totalAmountAed: number;
  currency: CurrencyCode;
  currencyRate: number;
  totalInSelectedCurrency: number;
  shippingMethod: ShippingMethod;
  paymentMethod: PaymentMethodType;
  paymentStatus: 'paid' | 'pending_verification' | 'pending_cod' | 'failed';
  paymentReference?: string; // e.g. "FTS-981248-AE" or Stripe Charge ID
  orderStatus: 'processing' | 'confirmed' | 'packed' | 'shipped' | 'delivered' | 'cancelled';
  trackingNumber?: string; // Amazon MCF tracking or Courier tracking
  carrier?: string; // "Amazon Logistics", "Aramex", "Courier Express"
  taxInvoice: TaxInvoice;
  odooSynced: boolean;
  odooSaleOrderId?: number;
  amazonMcfFulfillmentOrderId?: string;
  paymentReceiptUrl?: string; // Uploaded payment proof for Bank Transfer
}

export interface StoreIdentity {
  storeName: string;
  tagline: string;
  announcementText: string;
  logoUrl: string;
  faviconUrl: string;
  primaryColor: string; // Hex e.g. #0284c7
  accentColor: string; // Hex e.g. #f59e0b
  supportEmail: string;
  supportPhone: string;
  whatsappNumber: string; // e.g. "+971501234567"
  companyLegalName: string;
  tradeLicenseNumber: string;
  uaeTrn: string; // 15-digit UAE TRN
  address: string;
  freeShippingThresholdAed: number;
  defaultCurrency: CurrencyCode;
}

export interface PaymentGatewaySettings {
  stripe: {
    enabled: boolean;
    publishableKey: string;
    secretKeyMasked: string;
    testMode: boolean;
  };
  tabby: {
    enabled: boolean;
    publicKey: string;
    merchantCode: string;
    installments: 4;
  };
  tamara: {
    enabled: boolean;
    apiTokenMasked: string;
    installments: 3;
  };
  bankTransfer: {
    enabled: boolean;
    accounts: BankAccountDetails[];
    instructions: string;
  };
  cod: {
    enabled: boolean;
    feeAed: number;
    allowEmirates: Emirate[];
  };
}

export interface OdooSettings {
  enabled: boolean;
  url: string;
  dbName: string;
  username: string;
  apiKeyMasked: string;
  autoSyncOrders: boolean;
  autoSyncStock: boolean;
  lastSyncTime?: string;
  connectionStatus: 'connected' | 'disconnected' | 'error';
}

export interface AmazonSettings {
  enabled: boolean;
  sellerId: string;
  marketplaceId: string; // "A2VIGQ35RCS4UG" for Amazon.ae
  spApiEndpoint: string;
  enableAmazonDeliveryMcf: boolean;
  enableAmazonUpsells: boolean;
  enableBuyOnAmazonButton: boolean;
  defaultMcfCarrier: string;
  lastSyncTime?: string;
  connectionStatus: 'connected' | 'disconnected' | 'error';
}

export interface WarehouseSettings {
  onlineWarehouseName: string;
  safetyBufferStock: number; // e.g. reserve 2 items before marking out of stock
  enableLowStockUrgencyBadge: boolean;
  allowBackorders: boolean;
  locations: {
    id: string;
    name: string;
    city: string;
    isPrimary: boolean;
  }[];
}
