'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useSearchParams } from 'next/navigation';
import {
  Store,
  Package,
  Layers,
  Globe,
  Database,
  Truck,
  CreditCard,
  ShoppingBag,
  ArrowUpRight,
  Sparkles,
  TrendingUp,
  AlertCircle,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { StoreSettingsTab } from './StoreSettingsTab';
import { ProductManagerTab } from './ProductManagerTab';
import { InventoryTab } from './InventoryTab';
import { CurrencyTab } from './CurrencyTab';
import { AmazonTab } from './AmazonTab';
import { OdooTab } from './OdooTab';
import { PaymentsTab } from './PaymentsTab';
import { OrdersTab } from './OrdersTab';

type AdminTab = 'store' | 'products' | 'inventory' | 'currency' | 'amazon' | 'odoo' | 'payments' | 'orders';

export const AdminDashboard: React.FC = () => {
  const searchParams = useSearchParams();
  const initialTab = (searchParams.get('tab') as AdminTab) || 'products';
  const [activeTab, setActiveTab] = useState<AdminTab>(initialTab);

  const { storeIdentity, products, orders, odooSettings, amazonSettings } = useStore();
  const { formatPrice } = useCurrency();

  useEffect(() => {
    const tabFromUrl = searchParams.get('tab') as AdminTab;
    if (tabFromUrl) setActiveTab(tabFromUrl);
  }, [searchParams]);

  const totalRevenueAed = orders.reduce((sum, o) => sum + o.totalAmountAed, 0);
  const lowStockCount = products.filter((p) => p.stock > 0 && p.stock <= p.lowStockThreshold).length;

  const tabs: { id: AdminTab; label: string; icon: React.ReactNode; badge?: string | number }[] = [
    { id: 'products', label: 'Products & Variants', icon: <Package size={17} />, badge: products.length },
    { id: 'inventory', label: 'Online Warehouse Stock', icon: <Layers size={17} />, badge: lowStockCount > 0 ? `${lowStockCount} Low` : undefined },
    { id: 'orders', label: 'Orders & Tax Invoices', icon: <ShoppingBag size={17} />, badge: orders.length },
    { id: 'currency', label: 'Multi-Currency (AED/USD/INR)', icon: <Globe size={17} /> },
    { id: 'store', label: 'Store Identity & Branding', icon: <Store size={17} /> },
    { id: 'payments', label: 'UAE Payment & Bank IBAN', icon: <CreditCard size={17} /> },
    { id: 'amazon', label: 'Amazon.ae & MCF Logistics', icon: <Truck size={17} /> },
    { id: 'odoo', label: 'Odoo 18 ERP Connector', icon: <Database size={17} /> },
  ];

  return (
    <div className="container" style={{ padding: '2rem 1.5rem 5rem' }}>
      {/* KPI Overview Banner */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '1.25rem', marginBottom: '2.5rem' }}>
        <div style={{ background: 'linear-gradient(135deg, #111827, #1e293b)', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.25rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', color: 'var(--text-secondary)', fontSize: '0.78rem' }}>
            <span>Total Store Revenue</span>
            <TrendingUp size={16} color="var(--accent-cyan)" />
          </div>
          <div style={{ fontSize: '1.6rem', fontWeight: 800, color: '#fff', marginTop: '0.35rem' }}>
            {formatPrice(totalRevenueAed)}
          </div>
          <div style={{ fontSize: '0.72rem', color: '#10b981', marginTop: '0.2rem' }}>
            From {orders.length} completed customer orders
          </div>
        </div>

        <div style={{ background: 'linear-gradient(135deg, #111827, #1e293b)', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.25rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', color: 'var(--text-secondary)', fontSize: '0.78rem' }}>
            <span>Active SKUs & Catalog</span>
            <Package size={16} color="var(--accent-gold)" />
          </div>
          <div style={{ fontSize: '1.6rem', fontWeight: 800, color: '#fff', marginTop: '0.35rem' }}>
            {products.length} Products
          </div>
          <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
            Real-time online warehouse tracking
          </div>
        </div>

        <div style={{ background: 'linear-gradient(135deg, #111827, #1e293b)', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.25rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', color: 'var(--text-secondary)', fontSize: '0.78rem' }}>
            <span>Odoo 18 ERP Sync</span>
            <Database size={16} color="#10b981" />
          </div>
          <div style={{ fontSize: '1.3rem', fontWeight: 800, color: odooSettings.enabled ? '#10b981' : 'var(--text-muted)', marginTop: '0.35rem' }}>
            {odooSettings.enabled ? 'Connected' : 'Disconnected'}
          </div>
          <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
            Auto-Sync SO & Tax Invoices
          </div>
        </div>

        <div style={{ background: 'linear-gradient(135deg, #111827, #1e293b)', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.25rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', color: 'var(--text-secondary)', fontSize: '0.78rem' }}>
            <span>Amazon.ae & MCF Fleet</span>
            <Truck size={16} color="#ff9900" />
          </div>
          <div style={{ fontSize: '1.3rem', fontWeight: 800, color: '#ff9900', marginTop: '0.35rem' }}>
            Active (UAE)
          </div>
          <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
            FBA Speeds & Bundles Enabled
          </div>
        </div>
      </div>

      {/* Tabs Navigation */}
      <div style={{ display: 'flex', gap: '0.5rem', overflowX: 'auto', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '0.75rem', marginBottom: '2rem' }}>
        {tabs.map((t) => (
          <button
            key={t.id}
            onClick={() => setActiveTab(t.id)}
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem',
              padding: '0.65rem 1rem',
              borderRadius: '10px',
              border: '1px solid ' + (activeTab === t.id ? 'var(--accent-cyan)' : 'transparent'),
              background: activeTab === t.id ? 'rgba(14,165,233,0.15)' : 'rgba(255,255,255,0.03)',
              color: activeTab === t.id ? '#fff' : 'var(--text-secondary)',
              fontWeight: activeTab === t.id ? 700 : 500,
              fontSize: '0.85rem',
              cursor: 'pointer',
              whiteSpace: 'nowrap',
              transition: 'var(--transition-fast)',
            }}
          >
            {t.icon}
            <span>{t.label}</span>
            {t.badge !== undefined && (
              <span
                style={{
                  padding: '0.1rem 0.4rem',
                  borderRadius: '999px',
                  background: activeTab === t.id ? 'var(--accent-cyan)' : '#374151',
                  color: activeTab === t.id ? '#fff' : '#cbd5e1',
                  fontSize: '0.68rem',
                  fontWeight: 800,
                }}
              >
                {t.badge}
              </span>
            )}
          </button>
        ))}
      </div>

      {/* Active Tab View */}
      <div className="animate-fade-in">
        {activeTab === 'products' && <ProductManagerTab />}
        {activeTab === 'inventory' && <InventoryTab />}
        {activeTab === 'orders' && <OrdersTab />}
        {activeTab === 'currency' && <CurrencyTab />}
        {activeTab === 'store' && <StoreSettingsTab />}
        {activeTab === 'payments' && <PaymentsTab />}
        {activeTab === 'amazon' && <AmazonTab />}
        {activeTab === 'odoo' && <OdooTab />}
      </div>
    </div>
  );
};
