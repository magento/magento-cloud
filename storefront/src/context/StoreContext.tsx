'use client';

import React, { createContext, useContext, useEffect, useState } from 'react';
import {
  AmazonSettings,
  OdooSettings,
  Order,
  PaymentGatewaySettings,
  Product,
  StoreIdentity,
  WarehouseSettings,
} from '../lib/types/commerce';
import { DEFAULT_STORE_IDENTITY, INITIAL_PRODUCTS } from '../lib/magento/mockData';
import { DEFAULT_PAYMENT_SETTINGS } from '../lib/integrations/uaePayments';
import { DEFAULT_ODOO_SETTINGS, OdooSyncLog } from '../lib/integrations/odooConnector';
import { DEFAULT_AMAZON_SETTINGS } from '../lib/integrations/amazonConnector';

interface StoreContextType {
  // Hydration state
  isLoading: boolean;

  // Store Identity & Branding
  storeIdentity: StoreIdentity;
  updateStoreIdentity: (updates: Partial<StoreIdentity>) => void;

  // Products & Catalog
  products: Product[];
  addProduct: (product: Omit<Product, 'id' | 'createdAt' | 'updatedAt'>) => Product;
  updateProduct: (id: string, updates: Partial<Product>) => void;
  deleteProduct: (id: string) => void;
  updateStock: (productId: string, newStock: number, variantId?: string) => void;
  bulkImportProducts: (newProducts: Partial<Product>[]) => { successCount: number; errors: string[] };
  exportProductsCsv: () => string;

  // Online Warehouse & Settings
  warehouseSettings: WarehouseSettings;
  updateWarehouseSettings: (updates: Partial<WarehouseSettings>) => void;

  // Payment Channels & Bank Settings
  paymentSettings: PaymentGatewaySettings;
  updatePaymentSettings: (updates: Partial<PaymentGatewaySettings>) => void;

  // Odoo ERP Integration
  odooSettings: OdooSettings;
  updateOdooSettings: (updates: Partial<OdooSettings>) => void;
  odooLogs: OdooSyncLog[];
  addOdooLog: (log: OdooSyncLog) => void;

  // Amazon.ae Integration
  amazonSettings: AmazonSettings;
  updateAmazonSettings: (updates: Partial<AmazonSettings>) => void;

  // Orders & Fulfillment
  orders: Order[];
  createOrder: (order: Order) => void;
  updateOrderStatus: (orderId: string, status: Order['orderStatus'], trackingNumber?: string) => void;
  getOrderById: (orderId: string) => Order | undefined;

  // Global Search & UI state
  searchQuery: string;
  setSearchQuery: (query: string) => void;
  selectedCategory: string;
  setSelectedCategory: (category: string) => void;
}

const DEFAULT_WAREHOUSE_SETTINGS: WarehouseSettings = {
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

const StoreContext = createContext<StoreContextType | undefined>(undefined);

// ---- persistence helpers (fire-and-forget; local state is optimistic) ----

async function persistSettings(patch: Record<string, unknown>) {
  try {
    await fetch('/api/settings', {
      method: 'PATCH',
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify(patch),
    });
  } catch (e) {
    console.error('[v0] persist settings failed', e);
  }
}

async function persistProducts(products: Product[]) {
  try {
    await fetch('/api/products', {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify({ products }),
    });
  } catch (e) {
    console.error('[v0] persist products failed', e);
  }
}

async function deleteProductRemote(id: string) {
  try {
    await fetch(`/api/products?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
  } catch (e) {
    console.error('[v0] delete product failed', e);
  }
}

export const StoreProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [storeIdentity, setStoreIdentity] = useState<StoreIdentity>(DEFAULT_STORE_IDENTITY);
  const [products, setProducts] = useState<Product[]>(INITIAL_PRODUCTS);
  const [warehouseSettings, setWarehouseSettings] = useState<WarehouseSettings>(DEFAULT_WAREHOUSE_SETTINGS);
  const [paymentSettings, setPaymentSettings] = useState<PaymentGatewaySettings>(DEFAULT_PAYMENT_SETTINGS);
  const [odooSettings, setOdooSettings] = useState<OdooSettings>(DEFAULT_ODOO_SETTINGS);
  const [amazonSettings, setAmazonSettings] = useState<AmazonSettings>(DEFAULT_AMAZON_SETTINGS);
  const [orders, setOrders] = useState<Order[]>([]);
  const [odooLogs, setOdooLogs] = useState<OdooSyncLog[]>([]);
  const [searchQuery, setSearchQuery] = useState<string>('');
  const [selectedCategory, setSelectedCategory] = useState<string>('All Products');

  // Hydrate all store state from the database via the bootstrap endpoint.
  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        const res = await fetch('/api/bootstrap', { cache: 'no-store' });
        if (!res.ok) throw new Error(`bootstrap ${res.status}`);
        const data = await res.json();
        if (cancelled) return;
        if (data.settings) {
          if (data.settings.storeIdentity) setStoreIdentity(data.settings.storeIdentity);
          if (data.settings.warehouseSettings) setWarehouseSettings(data.settings.warehouseSettings);
          if (data.settings.paymentSettings) setPaymentSettings(data.settings.paymentSettings);
          if (data.settings.odooSettings) setOdooSettings(data.settings.odooSettings);
          if (data.settings.amazonSettings) setAmazonSettings(data.settings.amazonSettings);
        }
        if (Array.isArray(data.products)) setProducts(data.products);
        if (Array.isArray(data.orders)) setOrders(data.orders);
      } catch (e) {
        console.error('[v0] store hydration failed, using defaults', e);
      } finally {
        if (!cancelled) setIsLoading(false);
      }
    })();
    return () => {
      cancelled = true;
    };
  }, []);

  const updateStoreIdentity = (updates: Partial<StoreIdentity>) => {
    setStoreIdentity((prev) => {
      const updated = { ...prev, ...updates };
      void persistSettings({ storeIdentity: updated });
      return updated;
    });
  };

  const addProduct = (newProdData: Omit<Product, 'id' | 'createdAt' | 'updatedAt'>): Product => {
    const id = 'prod-' + Date.now();
    const newProduct: Product = {
      ...newProdData,
      id,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };
    setProducts((prev) => [newProduct, ...prev]);
    void persistProducts([newProduct]);
    return newProduct;
  };

  const updateProduct = (id: string, updates: Partial<Product>) => {
    setProducts((prev) => {
      const updated = prev.map((p) => (p.id === id ? { ...p, ...updates, updatedAt: new Date().toISOString() } : p));
      const changed = updated.find((p) => p.id === id);
      if (changed) void persistProducts([changed]);
      return updated;
    });
  };

  const deleteProduct = (id: string) => {
    setProducts((prev) => prev.filter((p) => p.id !== id));
    void deleteProductRemote(id);
  };

  const updateStock = (productId: string, newStock: number, variantId?: string) => {
    setProducts((prev) => {
      const updated = prev.map((p) => {
        if (p.id !== productId) return p;
        if (variantId && p.variants) {
          const updatedVariants = p.variants.map((v) => (v.id === variantId ? { ...v, stock: newStock } : v));
          const totalVariantStock = updatedVariants.reduce((sum, v) => sum + v.stock, 0);
          return { ...p, stock: totalVariantStock, variants: updatedVariants, updatedAt: new Date().toISOString() };
        }
        return { ...p, stock: newStock, updatedAt: new Date().toISOString() };
      });
      const changed = updated.find((p) => p.id === productId);
      if (changed) void persistProducts([changed]);
      return updated;
    });
  };

  const bulkImportProducts = (items: Partial<Product>[]): { successCount: number; errors: string[] } => {
    const errors: string[] = [];
    let successCount = 0;
    const toAdd: Product[] = [];

    items.forEach((item, idx) => {
      if (!item.title || !item.basePriceAed) {
        errors.push(`Row ${idx + 1}: Missing Title or Base Price`);
        return;
      }
      const prod: Product = {
        id: 'prod-bulk-' + Date.now() + '-' + idx,
        sku: item.sku || `SKU-${Math.floor(100000 + Math.random() * 900000)}`,
        barcode: item.barcode || '',
        title: item.title,
        slug: item.slug || item.title.toLowerCase().replace(/[^a-z0-9]+/g, '-'),
        description: item.description || item.title,
        shortDescription: item.shortDescription || '',
        category: item.category || 'General',
        brand: item.brand || 'Store Brand',
        basePriceAed: Number(item.basePriceAed),
        compareAtPriceAed: item.compareAtPriceAed ? Number(item.compareAtPriceAed) : undefined,
        costPriceAed: item.costPriceAed ? Number(item.costPriceAed) : undefined,
        featuredImage: item.featuredImage || 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
        galleryImages: item.galleryImages && item.galleryImages.length ? item.galleryImages : [item.featuredImage || 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800'],
        stock: Number(item.stock) || 10,
        lowStockThreshold: Number(item.lowStockThreshold) || 3,
        hasVariants: Boolean(item.variants && item.variants.length > 0),
        variants: item.variants || [],
        tags: item.tags || [],
        rating: 5.0,
        reviewCount: 0,
        amazonAsin: item.amazonAsin || '',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
      };
      toAdd.push(prod);
      successCount++;
    });

    if (toAdd.length > 0) {
      setProducts((prev) => [...toAdd, ...prev]);
      void persistProducts(toAdd);
    }

    return { successCount, errors };
  };

  const exportProductsCsv = (): string => {
    const headers = ['id', 'sku', 'title', 'category', 'brand', 'basePriceAed', 'stock', 'amazonAsin', 'barcode'];
    const rows = products.map((p) => [
      p.id,
      `"${p.sku}"`,
      `"${p.title.replace(/"/g, '""')}"`,
      `"${p.category}"`,
      `"${p.brand}"`,
      p.basePriceAed,
      p.stock,
      `"${p.amazonAsin || ''}"`,
      `"${p.barcode || ''}"`,
    ]);
    return [headers.join(','), ...rows.map((r) => r.join(','))].join('\n');
  };

  const updateWarehouseSettings = (updates: Partial<WarehouseSettings>) => {
    setWarehouseSettings((prev) => {
      const updated = { ...prev, ...updates };
      void persistSettings({ warehouseSettings: updated });
      return updated;
    });
  };

  const updatePaymentSettings = (updates: Partial<PaymentGatewaySettings>) => {
    setPaymentSettings((prev) => {
      const updated = { ...prev, ...updates };
      void persistSettings({ paymentSettings: updated });
      return updated;
    });
  };

  const updateOdooSettings = (updates: Partial<OdooSettings>) => {
    setOdooSettings((prev) => {
      const updated = { ...prev, ...updates };
      void persistSettings({ odooSettings: updated });
      return updated;
    });
  };

  const addOdooLog = (log: OdooSyncLog) => {
    setOdooLogs((prev) => [log, ...prev.slice(0, 49)]);
  };

  const updateAmazonSettings = (updates: Partial<AmazonSettings>) => {
    setAmazonSettings((prev) => {
      const updated = { ...prev, ...updates };
      void persistSettings({ amazonSettings: updated });
      return updated;
    });
  };

  const createOrder = (order: Order) => {
    setOrders((prev) => [order, ...prev]);
    // Optimistically deduct stock locally; the server recomputes authoritatively.
    setProducts((prev) =>
      prev.map((p) => {
        const item = order.items.find((i) => i.productId === p.id);
        if (!item) return p;
        return { ...p, stock: Math.max(0, (p.stock || 0) - item.quantity) };
      }),
    );
    // Server persists the order and decrements stock in the database.
    (async () => {
      try {
        await fetch('/api/orders', {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          body: JSON.stringify({ order }),
        });
      } catch (e) {
        console.error('[v0] create order failed', e);
      }
    })();
  };

  const updateOrderStatus = (orderId: string, status: Order['orderStatus'], trackingNumber?: string) => {
    setOrders((prev) => {
      const updated = prev.map((o) =>
        o.id === orderId ? { ...o, orderStatus: status, trackingNumber: trackingNumber || o.trackingNumber } : o,
      );
      const changed = updated.find((o) => o.id === orderId);
      if (changed) {
        (async () => {
          try {
            await fetch('/api/orders', {
              method: 'PATCH',
              headers: { 'content-type': 'application/json' },
              body: JSON.stringify({ order: changed }),
            });
          } catch (e) {
            console.error('[v0] update order status failed', e);
          }
        })();
      }
      return updated;
    });
  };

  const getOrderById = (orderId: string) => {
    return orders.find((o) => o.id === orderId || o.orderNumber === orderId);
  };

  return (
    <StoreContext.Provider
      value={{
        isLoading,
        storeIdentity,
        updateStoreIdentity,
        products,
        addProduct,
        updateProduct,
        deleteProduct,
        updateStock,
        bulkImportProducts,
        exportProductsCsv,
        warehouseSettings,
        updateWarehouseSettings,
        paymentSettings,
        updatePaymentSettings,
        odooSettings,
        updateOdooSettings,
        odooLogs,
        addOdooLog,
        amazonSettings,
        updateAmazonSettings,
        orders,
        createOrder,
        updateOrderStatus,
        getOrderById,
        searchQuery,
        setSearchQuery,
        selectedCategory,
        setSelectedCategory,
      }}
    >
      {children}
    </StoreContext.Provider>
  );
};

export const useStore = () => {
  const context = useContext(StoreContext);
  if (!context) {
    throw new Error('useStore must be used within a StoreProvider');
  }
  return context;
};
