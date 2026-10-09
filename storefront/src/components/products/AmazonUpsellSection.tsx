'use client';

import React, { useState } from 'react';
import { Plus, Check, ShoppingBag, Zap } from 'lucide-react';
import { Product } from '../../lib/types/commerce';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { useCart } from '../../context/CartContext';
import { getAmazonUpsellBundle } from '../../lib/integrations/amazonConnector';

interface AmazonUpsellSectionProps {
  product: Product;
}

export const AmazonUpsellSection: React.FC<AmazonUpsellSectionProps> = ({ product }) => {
  const { products } = useStore();
  const { formatPrice } = useCurrency();
  const { addToCart } = useCart();

  const bundleData = getAmazonUpsellBundle(product, products);
  const [selectedItemIds, setSelectedItemIds] = useState<string[]>([
    product.id,
    ...bundleData.bundleItems.map((b) => b.id),
  ]);
  const [isAdded, setIsAdded] = useState(false);

  const toggleItem = (id: string) => {
    if (id === product.id) return; // Main product cannot be unselected
    setSelectedItemIds((prev) => (prev.includes(id) ? prev.filter((i) => i !== id) : [...prev, id]));
  };

  const allItems = [bundleData.mainProduct, ...bundleData.bundleItems];
  const activeItems = allItems.filter((item) => selectedItemIds.includes(item.id));

  const rawTotalAed = activeItems.reduce((sum, item) => sum + item.basePriceAed, 0);
  const hasBundleDiscount = activeItems.length > 1;
  const discountedTotalAed = hasBundleDiscount ? rawTotalAed * 0.9 : rawTotalAed;

  const handleAddBundle = () => {
    activeItems.forEach((item) => {
      addToCart(item, 1);
    });
    setIsAdded(true);
    setTimeout(() => setIsAdded(false), 2000);
  };

  if (bundleData.bundleItems.length === 0) return null;

  return (
    <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.5rem', marginTop: '2.5rem' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '0.75rem', marginBottom: '1.25rem' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
          <span style={{ padding: '0.2rem 0.5rem', borderRadius: '6px', background: '#232f3e', color: '#ff9900', fontWeight: 800, fontSize: '0.75rem', border: '1px solid rgba(255,153,0,0.3)' }}>
            Amazon.ae Sync
          </span>
          <h3 style={{ fontSize: '1.15rem', color: '#fff' }}>Frequently Bought Together on Amazon</h3>
        </div>

        {hasBundleDiscount && (
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.3rem', background: 'rgba(16, 185, 129, 0.15)', color: '#10b981', fontSize: '0.8rem', fontWeight: 700, padding: '0.25rem 0.65rem', borderRadius: '999px', border: '1px solid rgba(16, 185, 129, 0.3)' }}>
            <Zap size={14} /> Extra 10% Bundle Discount Applied
          </span>
        )}
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.5rem', alignItems: 'center' }}>
        {/* Images & Plus connectors */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', flexWrap: 'wrap' }}>
          {allItems.map((item, idx) => (
            <React.Fragment key={item.id}>
              {idx > 0 && <Plus size={18} color="var(--text-muted)" />}
              <div
                onClick={() => toggleItem(item.id)}
                style={{
                  position: 'relative',
                  width: '90px',
                  height: '90px',
                  borderRadius: '12px',
                  background: '#0b0f19',
                  border: selectedItemIds.includes(item.id) ? '2px solid var(--accent-cyan)' : '1px solid var(--border-subtle)',
                  overflow: 'hidden',
                  cursor: item.id === product.id ? 'default' : 'pointer',
                  opacity: selectedItemIds.includes(item.id) ? 1 : 0.4,
                  transition: 'var(--transition-fast)',
                }}
                title={item.title}
              >
                <img src={item.featuredImage} alt={item.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                {selectedItemIds.includes(item.id) && (
                  <div style={{ position: 'absolute', top: '4px', right: '4px', background: 'var(--accent-cyan)', color: '#fff', borderRadius: '50%', width: '18px', height: '18px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <Check size={12} />
                  </div>
                )}
              </div>
            </React.Fragment>
          ))}
        </div>

        {/* Price & Action */}
        <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1.25rem', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
          <div>
            <div style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>Total Bundle Price ({activeItems.length} items):</div>
            <div style={{ display: 'flex', alignItems: 'baseline', gap: '0.5rem' }}>
              <span style={{ fontSize: '1.4rem', fontWeight: 800, color: 'var(--text-primary)' }}>
                {formatPrice(discountedTotalAed)}
              </span>
              {hasBundleDiscount && (
                <span style={{ fontSize: '0.9rem', color: 'var(--text-muted)', textDecoration: 'line-through' }}>
                  {formatPrice(rawTotalAed)}
                </span>
              )}
            </div>
          </div>

          <button
            onClick={handleAddBundle}
            style={{
              background: isAdded ? '#10b981' : 'linear-gradient(135deg, #f59e0b, #d97706)',
              color: '#0b0f19',
              border: 'none',
              borderRadius: '10px',
              padding: '0.75rem 1.25rem',
              fontWeight: 800,
              fontSize: '0.9rem',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              transition: 'var(--transition-fast)',
            }}
          >
            {isAdded ? (
              <>
                <Check size={18} />
                <span>Added to Cart!</span>
              </>
            ) : (
              <>
                <ShoppingBag size={18} />
                <span>Add Selected Bundle to Cart</span>
              </>
            )}
          </button>
        </div>
      </div>
    </div>
  );
};
