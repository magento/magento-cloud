'use client';

import React from 'react';
import Link from 'next/link';
import { motion } from 'framer-motion';
import { ShoppingBag, Heart, Star, Zap, Check } from 'lucide-react';
import { Product } from '../../lib/types/commerce';
import { useCurrency } from '../../context/CurrencyContext';
import { useCart } from '../../context/CartContext';
import { useStore } from '../../context/StoreContext';
import { Magnetic } from '../ui/Magnetic';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  const { formatPrice, formatBnpl } = useCurrency();
  const { addToCart, toggleWishlist, isInWishlist } = useCart();
  const { warehouseSettings } = useStore();

  const isFavorited = isInWishlist(product.id);
  const isLowStock = product.stock > 0 && product.stock <= product.lowStockThreshold;
  const isOutOfStock = product.stock <= 0;

  const handleQuickAdd = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (isOutOfStock) return;
    const defaultVariant = product.variants && product.variants.length > 0 ? product.variants[0] : undefined;
    addToCart(product, 1, defaultVariant);
  };

  const handleWishlistClick = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    toggleWishlist(product.id);
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-50px" }}
      transition={{ duration: 0.5, ease: "easeOut" }}
      whileHover={{ y: -8, scale: 1.02, transition: { type: 'spring', stiffness: 400, damping: 25 } }}
      className="glass-panel group"
      style={{
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        position: 'relative',
        willChange: 'transform, opacity',
      }}
    >
      {/* Product Image Container */}
      <Link href={`/product/${product.slug}`} style={{ position: 'relative', display: 'block', paddingTop: '80%', background: '#0b0f19', overflow: 'hidden' }}>
        <img
          src={product.featuredImage}
          alt={product.title}
          style={{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            objectFit: 'cover',
            transition: 'transform 0.7s cubic-bezier(0.16, 1, 0.3, 1)',
            willChange: 'transform',
          }}
          className="group-hover:scale-110"
        />

        {/* Top Badges */}
        <div style={{ position: 'absolute', top: '0.65rem', left: '0.65rem', display: 'flex', flexDirection: 'column', gap: '0.35rem', zIndex: 10 }}>
          {product.isFlashDeal && (
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.25rem', background: '#f43f5e', color: '#fff', fontSize: '0.68rem', fontWeight: 800, padding: '0.2rem 0.55rem', borderRadius: '6px', textTransform: 'uppercase', boxShadow: '0 4px 10px rgba(244, 63, 94, 0.4)' }}>
              <Zap size={11} fill="#fff" /> Flash Deal
            </span>
          )}

          {product.amazonAsin && (
            <span style={{ background: 'rgba(35, 47, 62, 0.9)', backdropFilter: 'blur(4px)', color: '#ff9900', fontSize: '0.68rem', fontWeight: 700, padding: '0.2rem 0.5rem', borderRadius: '6px', border: '1px solid rgba(255,153,0,0.3)' }}>
              Amazon.ae
            </span>
          )}
        </div>

        {/* Wishlist Button */}
        <Magnetic pullFactor={0.5}>
          <button
            onClick={handleWishlistClick}
            style={{
              position: 'absolute',
              top: '0.65rem',
              right: '0.65rem',
              zIndex: 10,
              width: '32px',
              height: '32px',
              borderRadius: '50%',
              background: 'rgba(17, 24, 39, 0.65)',
              backdropFilter: 'blur(12px)',
              WebkitBackdropFilter: 'blur(12px)',
              border: '1px solid rgba(255, 255, 255, 0.15)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: isFavorited ? '#f43f5e' : '#e2e8f0',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
            }}
          >
            <Heart size={16} fill={isFavorited ? '#f43f5e' : 'none'} />
          </button>
        </Magnetic>

        {/* Stock Alert Badge */}
        {warehouseSettings.enableLowStockUrgencyBadge && isLowStock && (
          <div style={{ position: 'absolute', bottom: '0.5rem', left: '0.5rem', right: '0.5rem', background: 'rgba(239, 68, 68, 0.9)', backdropFilter: 'blur(4px)', color: '#fff', fontSize: '0.7rem', fontWeight: 700, padding: '0.2rem 0.5rem', borderRadius: '4px', textAlign: 'center' }}>
            Only {product.stock} left in Dubai Hub!
          </div>
        )}
      </Link>

      {/* Product Content Details */}
      <div style={{ padding: '1.25rem', display: 'flex', flexDirection: 'column', flex: 1, zIndex: 2 }}>
        {/* Brand & Category */}
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: '0.75rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
          <span style={{ fontWeight: 600 }}>{product.brand}</span>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.2rem', color: '#fbbf24', fontWeight: 700 }}>
            <Star size={13} fill="#fbbf24" />
            <span>{product.rating.toFixed(1)}</span>
            <span style={{ color: 'var(--text-muted)' }}>({product.reviewCount})</span>
          </div>
        </div>

        {/* Title */}
        <Link href={`/product/${product.slug}`} style={{ textDecoration: 'none', color: '#f9fafb', fontSize: '0.95rem', fontWeight: 700, lineHeight: '1.4', marginBottom: '0.65rem', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
          {product.title}
        </Link>

        {/* Price Section */}
        <div style={{ marginTop: 'auto', paddingTop: '0.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'baseline', gap: '0.5rem', flexWrap: 'wrap' }}>
            <span style={{ fontSize: '1.25rem', fontWeight: 800, color: 'var(--text-primary)' }}>
              {formatPrice(product.basePriceAed)}
            </span>
            {product.compareAtPriceAed && product.compareAtPriceAed > product.basePriceAed && (
              <span style={{ fontSize: '0.82rem', color: 'var(--text-muted)', textDecoration: 'line-through' }}>
                {formatPrice(product.compareAtPriceAed)}
              </span>
            )}
          </div>

          {/* Tabby 4x Installment breakdown */}
          <div style={{ marginTop: '0.45rem', display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.72rem', color: '#94a3b8' }}>
            <span style={{ padding: '0.1rem 0.4rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontWeight: 800, fontSize: '0.65rem' }}>
              tabby
            </span>
            <span>4 payments of <strong>{formatBnpl(product.basePriceAed, 4)}</strong></span>
          </div>
        </div>

        {/* Action Button */}
        <Magnetic pullFactor={0.1}>
          <button
            onClick={handleQuickAdd}
            disabled={isOutOfStock}
            style={{
              marginTop: '1rem',
              width: '100%',
              padding: '0.7rem',
              borderRadius: '10px',
              background: isOutOfStock ? '#374151' : 'linear-gradient(135deg, var(--accent-cyan), #0284c7)',
              color: '#ffffff',
              fontWeight: 700,
              fontSize: '0.85rem',
              border: 'none',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.4rem',
              cursor: isOutOfStock ? 'not-allowed' : 'pointer',
              transition: 'all 0.2s',
              boxShadow: isOutOfStock ? 'none' : '0 4px 15px rgba(14, 165, 233, 0.25)',
            }}
          >
            <ShoppingBag size={16} />
            <span>{isOutOfStock ? 'Out of Stock' : 'Add to Cart'}</span>
          </button>
        </Magnetic>
      </div>
    </motion.div>
  );
};
