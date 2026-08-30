'use client';

import React, { useState, Suspense } from 'react';
import Link from 'next/link';
import { useSearchParams } from 'next/navigation';
import {
  User,
  Package,
  Heart,
  MapPin,
  Eye,
  Truck,
  CheckCircle2,
  ExternalLink,
  ShoppingBag,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCart } from '../../context/CartContext';
import { useCurrency } from '../../context/CurrencyContext';
import { ProductCard } from '../../components/products/ProductCard';

function AccountContent() {
  const searchParams = useSearchParams();
  const defaultTab = searchParams.get('tab') || 'orders';
  const [activeTab, setActiveTab] = useState<'orders' | 'wishlist' | 'addresses'>(defaultTab as any);

  const { orders, products, storeIdentity } = useStore();
  const { wishlist } = useCart();
  const { formatPrice } = useCurrency();

  const wishlistProducts = products.filter((p) => wishlist.includes(p.id));

  return (
    <div className="container" style={{ padding: '2.5rem 1.5rem 5rem' }}>
      {/* Profile Header */}
      <div style={{ background: 'linear-gradient(135deg, #111827, #1e293b)', border: '1px solid var(--border-subtle)', borderRadius: '20px', padding: '1.75rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1.25rem', marginBottom: '2rem' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
          <div style={{ width: '60px', height: '60px', borderRadius: '50%', background: 'linear-gradient(135deg, #0ea5e9, #0284c7)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: '1.5rem', fontWeight: 800 }}>
            <User size={30} />
          </div>
          <div>
            <h1 style={{ fontSize: '1.4rem', color: '#fff' }}>Mohammed Al Hashimi</h1>
            <div style={{ fontSize: '0.82rem', color: 'var(--text-secondary)' }}>
              VIP UAE Shopper • Downtown Dubai • Member since 2026
            </div>
          </div>
        </div>

        <div style={{ display: 'flex', gap: '0.75rem' }}>
          <Link href="/admin" className="btn-secondary" style={{ padding: '0.5rem 1rem', fontSize: '0.82rem' }}>
            Store Admin Center
          </Link>
        </div>
      </div>

      {/* Tabs Navigation */}
      <div style={{ display: 'flex', gap: '0.75rem', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '0.75rem', marginBottom: '2rem' }}>
        <button
          onClick={() => setActiveTab('orders')}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.4rem',
            padding: '0.6rem 1.2rem',
            borderRadius: '10px',
            background: activeTab === 'orders' ? 'rgba(14,165,233,0.15)' : 'transparent',
            color: activeTab === 'orders' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
            fontWeight: activeTab === 'orders' ? 700 : 500,
            border: 'none',
            cursor: 'pointer',
            fontSize: '0.9rem',
          }}
        >
          <Package size={17} />
          <span>My Orders ({orders.length})</span>
        </button>

        <button
          onClick={() => setActiveTab('wishlist')}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.4rem',
            padding: '0.6rem 1.2rem',
            borderRadius: '10px',
            background: activeTab === 'wishlist' ? 'rgba(14,165,233,0.15)' : 'transparent',
            color: activeTab === 'wishlist' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
            fontWeight: activeTab === 'wishlist' ? 700 : 500,
            border: 'none',
            cursor: 'pointer',
            fontSize: '0.9rem',
          }}
        >
          <Heart size={17} />
          <span>Saved Wishlist ({wishlist.length})</span>
        </button>

        <button
          onClick={() => setActiveTab('addresses')}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.4rem',
            padding: '0.6rem 1.2rem',
            borderRadius: '10px',
            background: activeTab === 'addresses' ? 'rgba(14,165,233,0.15)' : 'transparent',
            color: activeTab === 'addresses' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
            fontWeight: activeTab === 'addresses' ? 700 : 500,
            border: 'none',
            cursor: 'pointer',
            fontSize: '0.9rem',
          }}
        >
          <MapPin size={17} />
          <span>Emirates Addresses</span>
        </button>
      </div>

      {/* Tab: Orders */}
      {activeTab === 'orders' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          {orders.length === 0 ? (
            <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '3rem 2rem', textAlign: 'center' }}>
              <Package size={48} color="#4b5563" style={{ margin: '0 auto 1rem' }} />
              <h3 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '0.5rem' }}>No orders yet</h3>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.85rem', marginBottom: '1.5rem' }}>
                Your order history and FTA Tax Invoices will appear here.
              </p>
              <Link href="/shop" className="btn-primary">
                Start Shopping
              </Link>
            </div>
          ) : (
            orders.map((o) => (
              <div key={o.id} style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.5rem' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '1rem', marginBottom: '1rem' }}>
                  <div>
                    <div style={{ fontSize: '1.1rem', fontWeight: 800, color: 'var(--accent-cyan)' }}>{o.orderNumber}</div>
                    <div style={{ fontSize: '0.78rem', color: 'var(--text-secondary)' }}>Placed on {new Date(o.createdAt).toLocaleDateString()}</div>
                  </div>

                  <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                    <span style={{ padding: '0.25rem 0.65rem', borderRadius: '999px', background: 'rgba(16,185,129,0.15)', color: '#10b981', fontSize: '0.75rem', fontWeight: 700, textTransform: 'capitalize' }}>
                      ● {o.orderStatus}
                    </span>
                    <span style={{ fontSize: '1.2rem', fontWeight: 800, color: 'var(--accent-gold)' }}>
                      {formatPrice(o.totalAmountAed)}
                    </span>
                  </div>
                </div>

                {/* Fulfillment Carrier & Tracking */}
                <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '0.85rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '0.5rem', marginBottom: '1rem' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.82rem', color: '#cbd5e1' }}>
                    <Truck size={16} color="var(--accent-cyan)" />
                    <span>Fulfillment: <strong>{o.carrier}</strong> (Tracking ID: <span style={{ color: '#fbbf24', fontFamily: 'monospace' }}>{o.trackingNumber}</span>)</span>
                  </div>
                  <div style={{ fontSize: '0.75rem', color: '#10b981', fontWeight: 600 }}>
                    {o.shippingMethod.estimatedDelivery}
                  </div>
                </div>

                {/* Items */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.65rem' }}>
                  {o.items.map((i) => (
                    <div key={i.sku} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: '0.85rem' }}>
                      <span style={{ color: '#fff' }}>{i.quantity}x {i.title} {i.variantName ? `(${i.variantName})` : ''}</span>
                      <span style={{ fontWeight: 700, color: '#e2e8f0' }}>{formatPrice(i.totalPriceAed)}</span>
                    </div>
                  ))}
                </div>
              </div>
            ))
          )}
        </div>
      )}

      {/* Tab: Wishlist */}
      {activeTab === 'wishlist' && (
        <div>
          {wishlistProducts.length === 0 ? (
            <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '3rem 2rem', textAlign: 'center' }}>
              <Heart size={48} color="#4b5563" style={{ margin: '0 auto 1rem' }} />
              <h3 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '0.5rem' }}>Your wishlist is empty</h3>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.85rem', marginBottom: '1.5rem' }}>
                Tap the heart icon on any product to save it here for later.
              </p>
              <Link href="/shop" className="btn-primary">
                Explore Catalog
              </Link>
            </div>
          ) : (
            <div className="grid-products">
              {wishlistProducts.map((p) => (
                <ProductCard key={p.id} product={p} />
              ))}
            </div>
          )}
        </div>
      )}

      {/* Tab: Addresses */}
      {activeTab === 'addresses' && (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          <div style={{ background: '#111827', border: '2px solid var(--accent-cyan)', borderRadius: '14px', padding: '1.5rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.75rem' }}>
              <span style={{ fontWeight: 700, color: '#fff', fontSize: '0.95rem' }}>Primary Residence (Dubai)</span>
              <span style={{ padding: '0.15rem 0.5rem', borderRadius: '4px', background: 'rgba(14,165,233,0.15)', color: 'var(--accent-cyan)', fontSize: '0.7rem', fontWeight: 700 }}>
                DEFAULT
              </span>
            </div>
            <div style={{ fontSize: '0.85rem', color: '#cbd5e1', lineHeight: '1.5' }}>
              Mohammed Al Hashimi <br />
              Burj Residences Tower 2, Apt 1402 <br />
              Downtown Dubai, Dubai, UAE <br />
              Phone: +971 50 123 4567
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default function AccountPage() {
  return (
    <Suspense fallback={<div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center', color: '#fff' }}>Loading Account...</div>}>
      <AccountContent />
    </Suspense>
  );
}
