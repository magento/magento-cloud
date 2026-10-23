import { db } from '@/lib/db';
import { orders as ordersTable, products as productsTable, storeSettings } from '@/lib/db/schema';
import { eq } from 'drizzle-orm';
import type {
  AmazonSettings,
  OdooSettings,
  Order,
  PaymentGatewaySettings,
  Product,
  StoreIdentity,
  WarehouseSettings,
} from '@/lib/types/commerce';
import { DEFAULT_STORE_IDENTITY, INITIAL_PRODUCTS } from '@/lib/magento/mockData';
import { DEFAULT_PAYMENT_SETTINGS } from '@/lib/integrations/uaePayments';
import { DEFAULT_ODOO_SETTINGS } from '@/lib/integrations/odooConnector';
import { DEFAULT_AMAZON_SETTINGS } from '@/lib/integrations/amazonConnector';

/** All configurable store settings kept as a single JSONB row. */
export interface StoreSettingsData {
  storeIdentity: StoreIdentity;
  warehouseSettings: WarehouseSettings;
  paymentSettings: PaymentGatewaySettings;
  odooSettings: OdooSettings;
  amazonSettings: AmazonSettings;
}

export const DEFAULT_WAREHOUSE_SETTINGS: WarehouseSettings = {
  onlineWarehouseName: 'Dubai Central Online Fulfillment Hub',
  safetyBufferStock: 2,
  enableLowStockUrgencyBadge: true,
  allowBackorders: false,
  locations: [
    { id: 'loc-dxb', name: 'Dubai Central Warehouse (DIC)', city: 'Dubai', isPrimary: true },
    { id: 'loc-auh', name: 'Abu Dhabi Regional Hub (Mussafah)', city: 'Abu Dhabi', isPrimary: false },
    { id: 'loc-jafza', name: 'JAFZA Freezone Bulk Depot', city: 'Dubai', isPrimary: false },
  ],
};

const DEFAULT_SETTINGS: StoreSettingsData = {
  storeIdentity: DEFAULT_STORE_IDENTITY,
  warehouseSettings: DEFAULT_WAREHOUSE_SETTINGS,
  paymentSettings: DEFAULT_PAYMENT_SETTINGS,
  odooSettings: DEFAULT_ODOO_SETTINGS,
  amazonSettings: DEFAULT_AMAZON_SETTINGS,
};

const SETTINGS_ID = 'store';

/**
 * Ensures the products and settings rows exist. On the very first run the
 * database is empty, so we seed it with the bundled demo catalog and defaults.
 */
export async function ensureSeeded(): Promise<void> {
  const existingSettings = await db
    .select({ id: storeSettings.id })
    .from(storeSettings)
    .where(eq(storeSettings.id, SETTINGS_ID))
    .limit(1);

  if (existingSettings.length === 0) {
    await db.insert(storeSettings).values({ id: SETTINGS_ID, data: DEFAULT_SETTINGS }).onConflictDoNothing();
  }

  const anyProduct = await db.select({ id: productsTable.id }).from(productsTable).limit(1);
  if (anyProduct.length === 0) {
    // Seed demo catalog. Skip conflicts so concurrent bootstraps are safe.
    for (const p of INITIAL_PRODUCTS) {
      await db.insert(productsTable).values({ id: p.id, data: p }).onConflictDoNothing();
    }
  }
}

export async function getSettings(): Promise<StoreSettingsData> {
  const rows = await db.select().from(storeSettings).where(eq(storeSettings.id, SETTINGS_ID)).limit(1);
  if (rows.length === 0) return DEFAULT_SETTINGS;
  return { ...DEFAULT_SETTINGS, ...(rows[0].data as StoreSettingsData) };
}

export async function saveSettings(next: StoreSettingsData): Promise<StoreSettingsData> {
  await db
    .insert(storeSettings)
    .values({ id: SETTINGS_ID, data: next, updatedAt: new Date() })
    .onConflictDoUpdate({ target: storeSettings.id, set: { data: next, updatedAt: new Date() } });
  return next;
}

export async function getProducts(): Promise<Product[]> {
  const rows = await db.select().from(productsTable).orderBy(productsTable.createdAt);
  // Newest first to match previous UI ordering.
  return rows.map((r) => r.data as Product).reverse();
}

export async function upsertProduct(product: Product): Promise<Product> {
  await db
    .insert(productsTable)
    .values({ id: product.id, data: product, updatedAt: new Date() })
    .onConflictDoUpdate({ target: productsTable.id, set: { data: product, updatedAt: new Date() } });
  return product;
}

export async function deleteProduct(id: string): Promise<void> {
  await db.delete(productsTable).where(eq(productsTable.id, id));
}

export async function getOrders(): Promise<Order[]> {
  const rows = await db.select().from(ordersTable).orderBy(ordersTable.createdAt);
  return rows.map((r) => r.data as Order).reverse();
}

export async function upsertOrder(order: Order): Promise<Order> {
  await db
    .insert(ordersTable)
    .values({ id: order.id, data: order, updatedAt: new Date() })
    .onConflictDoUpdate({ target: ordersTable.id, set: { data: order, updatedAt: new Date() } });
  return order;
}
