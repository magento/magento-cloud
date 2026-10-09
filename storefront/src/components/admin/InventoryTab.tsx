'use client';

import React, { useState } from 'react';
import { Layers, Warehouse, AlertTriangle, Check, Save, RefreshCw } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';

export const InventoryTab: React.FC = () => {
  const { products, updateStock, warehouseSettings, updateWarehouseSettings } = useStore();
  const { formatPrice } = useCurrency();

  const [localSettings, setLocalSettings] = useState(warehouseSettings);
  const [stockEdits, setStockEdits] = useState<{ [productId: string]: number }>({});
  const [isSaved, setIsSaved] = useState(false);

  const handleStockChange = (prodId: string, val: number) => {
    setStockEdits((prev) => ({ ...prev, [prodId]: val }));
  };

  const handleSaveAllStock = () => {
    Object.entries(stockEdits).forEach(([id, qty]) => {
      updateStock(id, qty);
    });
    updateWarehouseSettings(localSettings);
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2000);
  };

  const totalUnits = products.reduce((sum, p) => sum + p.stock, 0);
  const lowStockCount = products.filter((p) => p.stock > 0 && p.stock <= p.lowStockThreshold).length;
  const outOfStockCount = products.filter((p) => p.stock <= 0).length;

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Top Warehouse Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff' }}>Online Warehouse & Multi-Hub Inventory</h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Track real-time warehouse inventory, allocate stock buffers, and manage low-stock thresholds.
          </p>
        </div>

        <button onClick={handleSaveAllStock} className="btn-primary" style={{ padding: '0.65rem 1.25rem' }}>
          {isSaved ? (
            <>
              <Check size={18} />
              <span>Inventory Updated!</span>
            </>
          ) : (
            <>
              <Save size={18} />
              <span>Save Stock Changes</span>
            </>
          )}
        </button>
      </div>

      {/* Warehouse KPI Stats */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1rem' }}>
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1.25rem' }}>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>Total Stock Units in Hub</div>
          <div style={{ fontSize: '1.6rem', fontWeight: 800, color: 'var(--accent-cyan)', marginTop: '0.25rem' }}>{totalUnits} Units</div>
          <div style={{ fontSize: '0.72rem', color: '#94a3b8' }}>Distributed across 3 UAE hubs</div>
        </div>

        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1.25rem' }}>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>Low Stock Urgency Items</div>
          <div style={{ fontSize: '1.6rem', fontWeight: 800, color: '#f59e0b', marginTop: '0.25rem' }}>{lowStockCount} SKUs</div>
          <div style={{ fontSize: '0.72rem', color: '#f59e0b' }}>Threshold &lt;= 5 units</div>
        </div>

        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1.25rem' }}>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>Out of Stock Items</div>
          <div style={{ fontSize: '1.6rem', fontWeight: 800, color: '#f43f5e', marginTop: '0.25rem' }}>{outOfStockCount} SKUs</div>
          <div style={{ fontSize: '0.72rem', color: '#f43f5e' }}>Replenish immediately</div>
        </div>
      </div>

      {/* Warehouse Distribution Locations */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.25rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '1rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <Warehouse size={16} color="var(--accent-cyan)" />
          <span>Fulfillment Warehouses & Virtual Locations</span>
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1rem' }}>
          {localSettings.locations.map((loc) => (
            <div key={loc.id} style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span style={{ fontWeight: 700, color: '#fff', fontSize: '0.88rem' }}>{loc.name}</span>
                {loc.isPrimary && (
                  <span style={{ padding: '0.15rem 0.45rem', borderRadius: '4px', background: 'rgba(14,165,233,0.15)', color: 'var(--accent-cyan)', fontSize: '0.68rem', fontWeight: 700 }}>
                    Primary Hub
                  </span>
                )}
              </div>
              <div style={{ fontSize: '0.78rem', color: 'var(--text-secondary)', marginTop: '0.25rem' }}>City: {loc.city}</div>
            </div>
          ))}
        </div>
      </div>

      {/* Quick Stock Modifier Table */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', overflowX: 'auto' }}>
        <div style={{ padding: '1rem 1.25rem', borderBottom: '1px solid var(--border-subtle)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h4 style={{ fontSize: '0.95rem', color: '#fff' }}>Fast Inline Stock Adjuster</h4>
          <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Changes save automatically when clicking "Save Stock Changes"</span>
        </div>

        <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '0.85rem' }}>
          <thead>
            <tr style={{ background: '#0b0f19', borderBottom: '1px solid var(--border-subtle)', color: 'var(--text-muted)' }}>
              <th style={{ padding: '0.85rem 1rem' }}>Product Title</th>
              <th style={{ padding: '0.85rem 1rem' }}>SKU</th>
              <th style={{ padding: '0.85rem 1rem' }}>Base Price (AED)</th>
              <th style={{ padding: '0.85rem 1rem' }}>Current Stock</th>
              <th style={{ padding: '0.85rem 1rem' }}>Adjust Stock Qty</th>
              <th style={{ padding: '0.85rem 1rem' }}>Status</th>
            </tr>
          </thead>
          <tbody>
            {products.map((p) => {
              const currentVal = stockEdits[p.id] !== undefined ? stockEdits[p.id] : p.stock;
              return (
                <tr key={p.id} style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td style={{ padding: '0.85rem 1rem', color: '#fff', fontWeight: 600 }}>{p.title}</td>
                  <td style={{ padding: '0.85rem 1rem', color: 'var(--accent-cyan)' }}>{p.sku}</td>
                  <td style={{ padding: '0.85rem 1rem', color: '#fbbf24', fontWeight: 700 }}>AED {p.basePriceAed.toLocaleString()}</td>
                  <td style={{ padding: '0.85rem 1rem' }}>{p.stock} units</td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    <input
                      type="number"
                      min={0}
                      value={currentVal}
                      onChange={(e) => handleStockChange(p.id, Number(e.target.value))}
                      style={{
                        width: '80px',
                        background: '#1f2937',
                        border: '1px solid var(--border-subtle)',
                        borderRadius: '6px',
                        color: '#fff',
                        padding: '0.35rem 0.5rem',
                        fontWeight: 700,
                        fontSize: '0.85rem',
                      }}
                    />
                  </td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    {currentVal > p.lowStockThreshold ? (
                      <span style={{ color: '#10b981', fontSize: '0.75rem', fontWeight: 700 }}>In Stock</span>
                    ) : currentVal > 0 ? (
                      <span style={{ color: '#f59e0b', fontSize: '0.75rem', fontWeight: 700 }}>Low Stock</span>
                    ) : (
                      <span style={{ color: '#f43f5e', fontSize: '0.75rem', fontWeight: 700 }}>Out of Stock</span>
                    )}
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </div>
  );
};
