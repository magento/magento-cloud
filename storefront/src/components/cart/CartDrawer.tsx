'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { X, Trash2, Plus, Minus, ArrowRight, Zap, ShoppingBag, ShieldCheck, Tag } from 'lucide-react';
import { useCart } from '../../context/CartContext';
import { useCurrency } from '../../context/CurrencyContext';

export const CartDrawer: React.FC = () => {
  const {
    cart,
    isCartOpen,
    setIsCartOpen,
    updateQuantity,
    removeFromCart,
    subtotalAed,
    discountAed,
    vatAed,
    totalAed,
    freeShippingProgress,
    amountNeededForFreeShippingAed,
    appliedCoupon,
    applyCoupon,
    removeCoupon,
  } = useCart();

  const { formatPrice, formatBnpl } = useCurrency();
  const [couponInput, setCouponInput] = useState('');
  const [couponFeedback, setCouponFeedback] = useState<{ success: boolean; message: string } | null>(null);

  if (!isCartOpen) return null;

  const handleApplyCoupon = (e: React.FormEvent) => {
    e.preventDefault();
    if (!couponInput) return;
    const res = applyCoupon(couponInput);
    setCouponFeedback(res);
    if (res.success) setCouponInput('');
  };

  return (
    <div
      style={{
        position: 'fixed',
        inset: 0,
        zIndex: 50,
        display: 'flex',
        justifyContent: 'flex-end',
        background: 'rgba(0, 0, 0, 0.75)',
        backdropFilter: 'blur(8px)',
      }}
      onClick={() => setIsCartOpen(false)}
    >
      <div
        style={{
          width: '100%',
          maxWidth: '460px',
          height: '100%',
          background: '#111827',
          borderLeft: '1px solid var(--border-subtle)',
          display: 'flex',
          flexDirection: 'column',
          boxShadow: '-10px 0 40px rgba(0,0,0,0.8)',
          position: 'relative',
        }}
        onClick={(e) => e.stopPropagation()}
        className="animate-fade-in"
      >
        {/* Header */}
        <div style={{ padding: '1.25rem 1.5rem', borderBottom: '1px solid var(--border-subtle)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
            <ShoppingBag size={22} color="var(--accent-cyan)" />
            <h2 style={{ fontSize: '1.2rem', color: '#fff' }}>Your Shopping Bag ({cart.length})</h2>
          </div>

          <button
            onClick={() => setIsCartOpen(false)}
            style={{ background: 'none', border: 'none', color: '#9ca3af', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}
          >
            <X size={22} />
          </button>
        </div>

        {/* Free Shipping Progress Meter */}
        <div style={{ background: '#0b0f19', padding: '0.85rem 1.5rem', borderBottom: '1px solid var(--border-subtle)' }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', fontSize: '0.78rem', marginBottom: '0.4rem' }}>
            <span style={{ color: '#e2e8f0', display: 'flex', alignItems: 'center', gap: '0.35rem' }}>
              <Zap size={14} color="#f59e0b" />
              {freeShippingProgress >= 100 ? (
                <strong style={{ color: '#10b981' }}>🎉 Qualified for FREE UAE Delivery!</strong>
              ) : (
                <span>
                  Add <strong>AED {amountNeededForFreeShippingAed}</strong> more for Free UAE Delivery
                </span>
              )}
            </span>
            <span style={{ fontWeight: 700, color: 'var(--accent-cyan)' }}>{freeShippingProgress}%</span>
          </div>

          <div style={{ width: '100%', height: '6px', background: '#1f2937', borderRadius: '999px', overflow: 'hidden' }}>
            <div
              style={{
                width: `${freeShippingProgress}%`,
                height: '100%',
                background: freeShippingProgress >= 100 ? 'linear-gradient(90deg, #10b981, #34d399)' : 'linear-gradient(90deg, #0ea5e9, #38bdf8)',
                transition: 'width 0.3s ease',
              }}
            />
          </div>
        </div>

        {/* Cart Item List */}
        <div style={{ flex: 1, overflowY: 'auto', padding: '1.25rem 1.5rem', display: 'flex', flexDirection: 'column', gap: '1rem' }}>
          {cart.length === 0 ? (
            <div style={{ margin: 'auto', textAlign: 'center', padding: '2rem 1rem' }}>
              <ShoppingBag size={48} color="#4b5563" style={{ margin: '0 auto 1rem' }} />
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '1.1rem', marginBottom: '0.5rem' }}>Your bag is empty</div>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.85rem', marginBottom: '1.5rem' }}>
                Discover our luxury electronics and lifestyle collections.
              </p>
              <button
                onClick={() => setIsCartOpen(false)}
                className="btn-primary"
                style={{ width: '100%' }}
              >
                Browse Shop
              </button>
            </div>
          ) : (
            cart.map((item) => (
              <div
                key={item.id}
                style={{
                  display: 'flex',
                  gap: '1rem',
                  padding: '0.85rem',
                  background: '#1e293b',
                  borderRadius: '12px',
                  border: '1px solid var(--border-subtle)',
                }}
              >
                <img
                  src={item.product.featuredImage}
                  alt={item.product.title}
                  style={{ width: '70px', height: '70px', objectFit: 'cover', borderRadius: '8px', background: '#0b0f19' }}
                />

                <div style={{ flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                  <div>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: '0.5rem' }}>
                      <Link
                        href={`/product/${item.product.slug}`}
                        onClick={() => setIsCartOpen(false)}
                        style={{ color: '#fff', fontWeight: 600, fontSize: '0.88rem', textDecoration: 'none', lineHeight: '1.3' }}
                      >
                        {item.product.title}
                      </Link>
                      <button
                        onClick={() => removeFromCart(item.id)}
                        style={{ background: 'none', border: 'none', color: '#9ca3af', cursor: 'pointer', padding: '0.2rem' }}
                      >
                        <Trash2 size={15} />
                      </button>
                    </div>

                    {item.selectedVariant && (
                      <div style={{ fontSize: '0.75rem', color: 'var(--accent-cyan)', marginTop: '0.2rem' }}>
                        Variant: {item.selectedVariant.name}
                      </div>
                    )}
                  </div>

                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: '0.65rem' }}>
                    {/* Quantity modifier */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', background: '#0f172a', padding: '0.2rem 0.4rem', borderRadius: '6px', border: '1px solid var(--border-subtle)' }}>
                      <button
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                        style={{ background: 'none', border: 'none', color: '#cbd5e1', cursor: 'pointer', display: 'flex', alignItems: 'center' }}
                      >
                        <Minus size={13} />
                      </button>
                      <span style={{ fontSize: '0.8rem', fontWeight: 700, minWidth: '18px', textAlign: 'center', color: '#fff' }}>
                        {item.quantity}
                      </span>
                      <button
                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                        style={{ background: 'none', border: 'none', color: '#cbd5e1', cursor: 'pointer', display: 'flex', alignItems: 'center' }}
                      >
                        <Plus size={13} />
                      </button>
                    </div>

                    <div style={{ fontSize: '0.95rem', fontWeight: 700, color: 'var(--accent-gold)' }}>
                      {formatPrice(item.unitPriceAed * item.quantity)}
                    </div>
                  </div>
                </div>
              </div>
            ))
          )}
        </div>

        {/* Footer & Checkout Action */}
        {cart.length > 0 && (
          <div style={{ padding: '1.25rem 1.5rem', background: '#0b0f19', borderTop: '1px solid var(--border-subtle)', display: 'flex', flexDirection: 'column', gap: '0.85rem' }}>
            {/* Promo Code Input */}
            <form onSubmit={handleApplyCoupon} style={{ display: 'flex', gap: '0.4rem' }}>
              <input
                type="text"
                placeholder="Discount code (e.g. DUBAI10)"
                value={couponInput}
                onChange={(e) => setCouponInput(e.target.value)}
                style={{ flex: 1, background: '#1f2937', border: '1px solid var(--border-subtle)', borderRadius: '8px', color: '#fff', padding: '0.45rem 0.75rem', fontSize: '0.8rem', outline: 'none' }}
              />
              <button
                type="submit"
                style={{ background: '#374151', color: '#fff', border: 'none', borderRadius: '8px', padding: '0.45rem 0.85rem', fontSize: '0.78rem', fontWeight: 600, cursor: 'pointer' }}
              >
                Apply
              </button>
            </form>

            {/* Coupon Feedback */}
            {couponFeedback && (
              <div style={{ fontSize: '0.75rem', color: couponFeedback.success ? '#10b981' : '#f43f5e' }}>
                {couponFeedback.message}
              </div>
            )}

            {appliedCoupon && (
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: 'rgba(16,185,129,0.1)', padding: '0.35rem 0.65rem', borderRadius: '6px', fontSize: '0.78rem', color: '#10b981' }}>
                <span style={{ display: 'flex', alignItems: 'center', gap: '0.3rem' }}>
                  <Tag size={13} /> {appliedCoupon.code} ({appliedCoupon.description})
                </span>
                <button onClick={removeCoupon} style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', fontSize: '0.75rem', fontWeight: 700 }}>
                  Remove
                </button>
              </div>
            )}

            {/* Totals Breakdown */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.35rem', fontSize: '0.82rem', color: 'var(--text-secondary)' }}>
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
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.05rem', fontWeight: 800, color: '#fff', borderTop: '1px solid var(--border-subtle)', paddingTop: '0.4rem', marginTop: '0.2rem' }}>
                <span>Total Amount:</span>
                <span style={{ color: 'var(--accent-gold)' }}>{formatPrice(totalAed)}</span>
              </div>
            </div>

            {/* Tabby Breakdown on Cart */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem', fontSize: '0.75rem', color: '#94a3b8', background: 'rgba(59, 252, 135, 0.08)', padding: '0.4rem', borderRadius: '8px' }}>
              <span style={{ padding: '0.05rem 0.35rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontWeight: 800, fontSize: '0.65rem' }}>
                tabby
              </span>
              <span>or 4 monthly payments of <strong>{formatBnpl(totalAed, 4)}</strong></span>
            </div>

            {/* Checkout CTA */}
            <Link
              href="/checkout"
              onClick={() => setIsCartOpen(false)}
              className="btn-primary"
              style={{ width: '100%', padding: '0.85rem', fontSize: '1rem' }}
            >
              <span>Proceed to UAE Checkout</span>
              <ArrowRight size={18} />
            </Link>

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem', fontSize: '0.72rem', color: 'var(--text-muted)' }}>
              <ShieldCheck size={14} color="#10b981" />
              <span>256-Bit Encrypted • FTA Tax Invoice Generated Automatically</span>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
