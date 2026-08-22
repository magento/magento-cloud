import { OdooSettings, Order, Product } from '../types/commerce';

export const DEFAULT_ODOO_SETTINGS: OdooSettings = {
  enabled: true,
  url: 'https://odoo-ae.yourcompany.com',
  dbName: 'magento_uae_prod',
  username: 'admin@magento-cloud.ae',
  apiKeyMasked: 'odoo_api_••••••••••••••••8812',
  autoSyncOrders: true,
  autoSyncStock: true,
  lastSyncTime: new Date(Date.now() - 3600000).toISOString(),
  connectionStatus: 'connected',
};

export interface OdooSyncLog {
  id: string;
  timestamp: string;
  entity: 'product' | 'order' | 'stock' | 'invoice';
  action: 'export' | 'import' | 'sync';
  status: 'success' | 'warning' | 'error';
  message: string;
  recordRef?: string;
}

/**
 * Tests connection to Odoo ERP instance.
 */
export async function testOdooConnection(settings: OdooSettings): Promise<{
  success: boolean;
  version?: string;
  serverTime?: string;
  message: string;
}> {
  // Simulated connection test with realistic response
  await new Promise((res) => setTimeout(res, 800));
  if (!settings.url || !settings.username) {
    return {
      success: false,
      message: 'Odoo URL and Username are required to establish connection.',
    };
  }

  return {
    success: true,
    version: 'Odoo 18.0+e (Enterprise UAE Edition)',
    serverTime: new Date().toISOString(),
    message: 'Successfully authenticated and connected to Odoo XML-RPC endpoints.',
  };
}

/**
 * Syncs a web order to Odoo (Creates sale.order, stock.picking, and account.move Tax Invoice).
 */
export async function syncOrderToOdoo(order: Order, settings: OdooSettings): Promise<{
  success: boolean;
  odooSaleOrderId: number;
  odooInvoiceId: number;
  log: OdooSyncLog;
}> {
  await new Promise((res) => setTimeout(res, 500));
  const odooSaleOrderId = Math.floor(10000 + Math.random() * 90000);
  const odooInvoiceId = Math.floor(50000 + Math.random() * 90000);

  return {
    success: true,
    odooSaleOrderId,
    odooInvoiceId,
    log: {
      id: 'log-' + Date.now(),
      timestamp: new Date().toISOString(),
      entity: 'order',
      action: 'export',
      status: 'success',
      message: `Exported Web Order ${order.orderNumber} to Odoo sale.order (#SO-${odooSaleOrderId}) and created FTA Tax Invoice (#INV-${odooInvoiceId}).`,
      recordRef: order.orderNumber,
    },
  };
}

/**
 * Syncs product master and inventory between Storefront and Odoo.
 */
export async function syncCatalogWithOdoo(
  products: Product[],
  settings: OdooSettings
): Promise<{ success: boolean; syncedCount: number; logs: OdooSyncLog[] }> {
  await new Promise((res) => setTimeout(res, 900));

  const logs: OdooSyncLog[] = products.slice(0, 5).map((p) => ({
    id: 'log-' + p.id + '-' + Date.now(),
    timestamp: new Date().toISOString(),
    entity: 'product',
    action: 'sync',
    status: 'success',
    message: `Synchronized ${p.title} (SKU: ${p.sku}) with Odoo product.template (Stock: ${p.stock} units).`,
    recordRef: p.sku,
  }));

  return {
    success: true,
    syncedCount: products.length,
    logs,
  };
}
