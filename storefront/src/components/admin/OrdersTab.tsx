'use client';

import React, { useState } from 'react';
import { Package, Truck, Check, Eye, X, Download, ShieldCheck, Printer } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { Order } from '../../lib/types/commerce';
import { getPaymentMethodLabel } from '../../lib/integrations/uaePayments';

export const OrdersTab: React.FC = () => {
  const { orders, updateOrderStatus, storeIdentity } = useStore();
  const { formatPrice } = useCurrency();
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);

  const statusColors: Record<Order['orderStatus'], { bg: string; text: string }> = {
    processing: { bg: 'rgba(245,158,11,0.15)', text: '#f59e0b' },
    confirmed: { bg: 'rgba(14,165,233,0.15)', text: 'var(--accent-cyan)' },
    packed: { bg: 'rgba(168,85,247,0.15)', text: '#c084fc' },
    shipped: { bg: 'rgba(59,130,246,0.15)', text: '#60a5fa' },
    delivered: { bg: 'rgba(16,185,129,0.15)', text: '#10b981' },
    cancelled: { bg: 'rgba(244,63,94,0.15)', text: '#f43f5e' },
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Top Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <Package size={20} color="var(--accent-cyan)" />
            <span>Orders & UAE Tax Invoices ({orders.length})</span>
          </h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Process customer orders, update Amazon Logistics tracking numbers, and view FTA-compliant Tax Invoices.
          </p>
        </div>
      </div>

      {/* Orders Table */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '0.85rem' }}>
          <thead>
            <tr style={{ background: '#0b0f19', borderBottom: '1px solid var(--border-subtle)', color: 'var(--text-muted)' }}>
              <th style={{ padding: '0.85rem 1rem' }}>Order Ref</th>
              <th style={{ padding: '0.85rem 1rem' }}>Customer & Emirate</th>
              <th style={{ padding: '0.85rem 1rem' }}>Items</th>
              <th style={{ padding: '0.85rem 1rem' }}>Total (AED)</th>
              <th style={{ padding: '0.85rem 1rem' }}>Payment</th>
              <th style={{ padding: '0.85rem 1rem' }}>Fulfillment Status</th>
              <th style={{ padding: '0.85rem 1rem', textAlign: 'right' }}>Action</th>
            </tr>
          </thead>
          <tbody>
            {orders.length === 0 ? (
              <tr>
                <td colSpan={7} style={{ padding: '3rem', textAlign: 'center', color: 'var(--text-secondary)' }}>
                  No orders placed yet. Place a test order through the storefront checkout!
                </td>
              </tr>
            ) : (
              orders.map((o) => (
                <tr key={o.id} style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td style={{ padding: '0.85rem 1rem', fontWeight: 700, color: 'var(--accent-cyan)' }}>
                    {o.orderNumber}
                    <div style={{ fontSize: '0.72rem', color: 'var(--text-muted)', fontWeight: 400 }}>
                      {new Date(o.createdAt).toLocaleDateString()}
                    </div>
                  </td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    <div style={{ color: '#fff', fontWeight: 600 }}>{o.customer.fullName}</div>
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>{o.customer.emirate} ({o.customer.phone})</div>
                  </td>
                  <td style={{ padding: '0.85rem 1rem', color: '#cbd5e1' }}>
                    {o.items.reduce((s, i) => s + i.quantity, 0)} items ({o.items[0]?.title.substring(0, 20)}...)
                  </td>
                  <td style={{ padding: '0.85rem 1rem', fontWeight: 700, color: '#fbbf24' }}>
                    AED {o.totalAmountAed.toLocaleString()}
                  </td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    <span style={{ fontSize: '0.75rem', color: '#e2e8f0' }}>{getPaymentMethodLabel(o.paymentMethod).split('(')[0]}</span>
                    {o.odooSynced && (
                      <div style={{ fontSize: '0.68rem', color: '#10b981' }}>✓ Odoo Synced</div>
                    )}
                  </td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    <select
                      value={o.orderStatus}
                      onChange={(e) => updateOrderStatus(o.id, e.target.value as Order['orderStatus'])}
                      style={{
                        background: statusColors[o.orderStatus]?.bg || '#1f2937',
                        color: statusColors[o.orderStatus]?.text || '#fff',
                        border: '1px solid var(--border-subtle)',
                        borderRadius: '6px',
                        padding: '0.25rem 0.5rem',
                        fontSize: '0.75rem',
                        fontWeight: 700,
                        cursor: 'pointer',
                      }}
                    >
                      <option value="confirmed">Confirmed</option>
                      <option value="packed">Packed</option>
                      <option value="shipped">Shipped</option>
                      <option value="delivered">Delivered</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </td>
                  <td style={{ padding: '0.85rem 1rem', textAlign: 'right' }}>
                    <button
                      onClick={() => setSelectedOrder(o)}
                      className="btn-secondary"
                      style={{ padding: '0.35rem 0.65rem', fontSize: '0.75rem' }}
                    >
                      <Eye size={13} />
                      <span>View Invoice</span>
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Tax Invoice Modal */}
      {selectedOrder && (
        <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.8)', backdropFilter: 'blur(8px)', zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '1.5rem' }}>
          <div style={{ background: '#ffffff', color: '#0f172a', borderRadius: '16px', width: '100%', maxWidth: '650px', maxHeight: '90vh', overflowY: 'auto', padding: '2rem', position: 'relative' }}>
            <button
              onClick={() => setSelectedOrder(null)}
              style={{ position: 'absolute', top: '1.25rem', right: '1.25rem', background: '#f1f5f9', border: 'none', borderRadius: '50%', width: '32px', height: '32px', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#475569', cursor: 'pointer' }}
            >
              <X size={18} />
            </button>

            {/* Header */}
            <div style={{ display: 'flex', justifyContent: 'space-between', borderBottom: '2px solid #e2e8f0', paddingBottom: '1.25rem', marginBottom: '1.25rem' }}>
              <div>
                <h3 style={{ fontSize: '1.4rem', fontWeight: 800, color: '#0f172a' }}>{storeIdentity.storeName}</h3>
                <div style={{ fontSize: '0.8rem', color: '#64748b' }}>{storeIdentity.companyLegalName}</div>
                <div style={{ fontSize: '0.8rem', color: '#64748b' }}>{storeIdentity.address}</div>
                <div style={{ fontSize: '0.85rem', fontWeight: 700, color: '#0284c7', marginTop: '0.35rem' }}>
                  UAE TRN: {selectedOrder.taxInvoice.trnSeller}
                </div>
              </div>

              <div style={{ textAlign: 'right' }}>
                <div style={{ fontSize: '1.2rem', fontWeight: 800, color: '#0284c7' }}>TAX INVOICE / فاتورة ضريبية</div>
                <div style={{ fontSize: '0.85rem', fontWeight: 600 }}>{selectedOrder.taxInvoice.invoiceNumber}</div>
                <div style={{ fontSize: '0.8rem', color: '#64748b' }}>Date: {new Date(selectedOrder.taxInvoice.date).toLocaleDateString()}</div>
              </div>
            </div>

            {/* Customer Details */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem', background: '#f8fafc', padding: '1rem', borderRadius: '8px', marginBottom: '1.25rem', fontSize: '0.82rem' }}>
              <div>
                <span style={{ color: '#64748b', fontWeight: 600 }}>Billed To:</span>
                <div style={{ fontWeight: 700, color: '#0f172a' }}>{selectedOrder.customer.fullName}</div>
                <div>{selectedOrder.customer.streetAddress}, {selectedOrder.customer.buildingVilla}</div>
                <div>{selectedOrder.customer.area}, {selectedOrder.customer.emirate}, UAE</div>
                <div>Phone: {selectedOrder.customer.phone}</div>
                {selectedOrder.customer.trnNumber && <div>Customer TRN: {selectedOrder.customer.trnNumber}</div>}
              </div>

              <div>
                <span style={{ color: '#64748b', fontWeight: 600 }}>Fulfillment Details:</span>
                <div>Carrier: <strong>{selectedOrder.carrier}</strong></div>
                <div>Tracking: <strong>{selectedOrder.trackingNumber}</strong></div>
                <div>Payment Method: <strong>{getPaymentMethodLabel(selectedOrder.paymentMethod)}</strong></div>
              </div>
            </div>

            {/* Items Table */}
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.82rem', marginBottom: '1.25rem' }}>
              <thead>
                <tr style={{ background: '#f1f5f9', borderBottom: '1px solid #cbd5e1' }}>
                  <th style={{ padding: '0.5rem', textAlign: 'left' }}>Item Description</th>
                  <th style={{ padding: '0.5rem', textAlign: 'center' }}>Qty</th>
                  <th style={{ padding: '0.5rem', textAlign: 'right' }}>Unit Price (AED)</th>
                  <th style={{ padding: '0.5rem', textAlign: 'right' }}>Total (AED)</th>
                </tr>
              </thead>
              <tbody>
                {selectedOrder.items.map((i) => (
                  <tr key={i.sku} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '0.5rem' }}>
                      <strong>{i.title}</strong> (SKU: {i.sku})
                    </td>
                    <td style={{ padding: '0.5rem', textAlign: 'center' }}>{i.quantity}</td>
                    <td style={{ padding: '0.5rem', textAlign: 'right' }}>{i.unitPriceAed.toLocaleString()}</td>
                    <td style={{ padding: '0.5rem', textAlign: 'right' }}>{i.totalPriceAed.toLocaleString()}</td>
                  </tr>
                ))}
              </tbody>
            </table>

            {/* Tax Totals */}
            <div style={{ marginLeft: 'auto', maxWidth: '280px', fontSize: '0.85rem', display: 'flex', flexDirection: 'column', gap: '0.35rem' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <span>Subtotal (Net):</span>
                <span>AED {selectedOrder.taxInvoice.subtotalAed.toLocaleString()}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <span>Shipping:</span>
                <span>AED {selectedOrder.taxInvoice.shippingAed.toLocaleString()}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', color: '#0284c7', fontWeight: 600 }}>
                <span>UAE VAT (5% FTA):</span>
                <span>AED {selectedOrder.taxInvoice.vatAmountAed.toLocaleString()}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', borderTop: '2px solid #0f172a', paddingTop: '0.4rem', fontSize: '1rem', fontWeight: 800 }}>
                <span>Total Amount Due:</span>
                <span>AED {selectedOrder.taxInvoice.grandTotalAed.toLocaleString()}</span>
              </div>
            </div>

            {/* Actions */}
            <div style={{ marginTop: '1.5rem', display: 'flex', justifyContent: 'flex-end', gap: '0.75rem' }}>
              <button
                type="button"
                onClick={() => window.print()}
                style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', background: '#0284c7', color: '#fff', border: 'none', borderRadius: '8px', padding: '0.5rem 1rem', fontWeight: 600, fontSize: '0.85rem', cursor: 'pointer' }}
              >
                <Printer size={15} />
                <span>Print Invoice</span>
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
