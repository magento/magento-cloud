'use client';

import React from 'react';
import Link from 'next/link';
import { ShoppingBag, ArrowRight, Trash2, Plus, Minus, ShieldCheck, Zap } from 'lucide-react';
import { useCart } from '../../context/CartContext';
import { useCurrency } from '../../context/CurrencyContext';
import { useStore } from '../../context/StoreContext';

export default function CartPage() {
  const { cart, subtotalAed, discountAed, vatAed, totalAed, updateQuantity, removeFromCart, freeShippingProgress, amountNeededForFreeShippingAed } = useCart();
  const { formatPrice, formatBnpl } = useCurrency();

  if (cart.length === 0) {
    return (
      <div className="container" style={{ padding: '5rem 1.5rem', textAlign: 'center' }}>
        <ShoppingBag size={56} color="#4b5563" style={{ margin: '0 auto 1.25rem' }} />
        <h1 style={{ fontSize: '1.75rem', color: '#fff', marginBottom: '0.5rem' }}>Your shopping bag is empty</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '2rem' }}>Discover our latest UAE electronics and luxury collections.</p>
        <Link href="/shop" className="btn-primary">
          Browse Catalog
        </Link>
      </div>
    );
  }

  return (
    <div className="container" style={{ padding: '2rem 1.5rem 5rem' }}>
      <h1 style={{ fontSize: '1.85rem', color: '#fff', marginBottom: '2rem' }}>Shopping Bag ({cart.length} items)</h1>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '2rem', alignItems: 'flex-start' }}>
        {/* Item List */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
          {/* Free Shipping Alert */}
          <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.8rem', color: '#cbd5e1', marginBottom: '0.4rem' }}>
              <span style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
                <Zap size={14} color="#f59e0b" />
                {freeShippingProgress >= 100 ? (
                  <strong style={{ color: '#10b981' }}>FREE UAE Delivery Unlocked!</strong>
                ) : (
                  <span>Add <strong>AED {amountNeededForFreeShippingAed}</strong> more for FREE UAE Delivery</span>
                )}
              </span>
              <span style={{ fontWeight: 700, color: 'var(--accent-cyan)' }}>{freeShippingProgress}%</span>
            </div>
            <div style={{ width: '100%', height: '6px', background: '#1f2937', borderRadius: '999px', overflow: 'hidden' }}>
              <div style={{ width: `${freeShippingProgress}%`, height: '100%', background: 'linear-gradient(90deg, #0ea5e9, #10b981)' }} />
            </div>
          </div>

          {cart.map((item) => (
            <div key={item.id} style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.25rem', display: 'flex', gap: '1.25rem', alignItems: 'center' }}>
              <img src={item.product.featuredImage} alt={item.product.title} style={{ width: '80px', height: '80px', objectFit: 'cover', borderRadius: '10px', background: '#0b0f19' }} />
              <div style={{ flex: 1 }}>
                <Link href={`/product/${item.product.slug}`} style={{ fontSize: '1rem', fontWeight: 700, color: '#fff', textDecoration: 'none' }}>
                  {item.product.title}
                </Link>
                {item.selectedVariant && (
                  <div style={{ fontSize: '0.78rem', color: 'var(--accent-cyan)', marginTop: '0.2rem' }}>
                    Variant: {item.selectedVariant.name}
                  </div>
                )}
                <div style={{ fontSize: '0.85rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
                  Unit: {formatPrice(item.unitPriceAed)}
                </div>
              </div>

              <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', background: '#0b0f19', padding: '0.3rem 0.6rem', borderRadius: '8px', border: '1px solid var(--border-subtle)' }}>
                <button onClick={() => updateQuantity(item.id, item.quantity - 1)} style={{ background: 'none', border: 'none', color: '#fff', cursor: 'pointer' }}>
                  <Minus size={14} />
                </button>
                <span style={{ fontWeight: 800, minWidth: '18px', textAlign: 'center', color: '#fff' }}>{item.quantity}</span>
                <button onClick={() => updateQuantity(item.id, item.quantity + 1)} style={{ background: 'none', border: 'none', color: '#fff', cursor: 'pointer' }}>
                  <Plus size={14} />
                </button>
              </div>

              <div style={{ fontSize: '1.1rem', fontWeight: 800, color: 'var(--accent-gold)', minWidth: '100px', textAlign: 'right' }}>
                {formatPrice(item.unitPriceAed * item.quantity)}
              </div>

              <button onClick={() => removeFromCart(item.id)} style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', padding: '0.4rem' }}>
                <Trash2 size={16} />
              </button>
            </div>
          ))}
        </div>

        {/* Summary Card */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.75rem', display: 'flex', flexDirection: 'column', gap: '1rem' }}>
          <h3 style={{ fontSize: '1.2rem', color: '#fff', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '0.75rem' }}>
            Order Calculation
          </h3>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', fontSize: '0.9rem', color: 'var(--text-secondary)' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
              <span>Subtotal:</span>
              <span style={{ color: '#fff', fontWeight: 600 }}>{formatPrice(subtotalAed)}</span>
            </div>
            {discountAed > 0 && (
              <div style={{ display: 'flex', justifyContent: 'space-between', color: '#10b981' }}>
                <span>Discount:</span>
                <span>-{formatPrice(discountAed)}</span>
              </div>
            )}
            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
              <span>UAE VAT (5% FTA Standard):</span>
              <span style={{ color: '#fff', fontWeight: 600 }}>{formatPrice(vatAed)}</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.25rem', fontWeight: 800, color: '#fff', borderTop: '1px solid var(--border-subtle)', paddingTop: '0.75rem', marginTop: '0.25rem' }}>
              <span>Total:</span>
              <span style={{ color: 'var(--accent-gold)' }}>{formatPrice(totalAed)}</span>
            </div>
          </div>

          {/* Tabby breakdown */}
          <div style={{ background: 'rgba(59, 252, 135, 0.08)', padding: '0.6rem', borderRadius: '8px', fontSize: '0.78rem', color: '#cbd5e1', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ padding: '0.1rem 0.4rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontWeight: 800, fontSize: '0.7rem' }}>
              tabby
            </span>
            <span>Split in 4 payments of <strong>{formatBnpl(totalAed, 4)}</strong></span>
          </div>

          <Link href="/checkout" className="btn-primary" style={{ padding: '0.9rem', fontSize: '1rem', width: '100%' }}>
            <span>Proceed to UAE Checkout</span>
            <ArrowRight size={18} />
          </Link>

          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem', fontSize: '0.75rem', color: 'var(--text-muted)' }}>
            <ShieldCheck size={15} color="#10b981" />
            <span>Encrypted Checkout • FTA Tax Invoice with QR Code</span>
          </div>
        </div>
      </div>
    </div>
  );
}
