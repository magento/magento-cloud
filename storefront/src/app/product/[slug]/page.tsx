'use client';

import React, { useState, use } from 'react';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import {
  ShoppingBag,
  Heart,
  Star,
  Truck,
  ShieldCheck,
  RotateCcw,
  ExternalLink,
  Zap,
  Check,
  Share2,
  Plus,
  Minus,
  MessageCircle,
} from 'lucide-react';
import { useStore } from '../../../context/StoreContext';
import { useCurrency } from '../../../context/CurrencyContext';
import { useCart } from '../../../context/CartContext';
import { ProductVariant } from '../../../lib/types/commerce';
import { AmazonUpsellSection } from '../../../components/products/AmazonUpsellSection';
import { getAmazonProductUrl } from '../../../lib/integrations/amazonConnector';

export default function ProductDetailPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = use(params);
  const { products, storeIdentity } = useStore();
  const { formatPrice, formatBnpl } = useCurrency();
  const { addToCart, toggleWishlist, isInWishlist } = useCart();

  const product = products.find((p) => p.slug === slug);
  if (!product) {
    notFound();
  }

  const [selectedImage, setSelectedImage] = useState(product.featuredImage);
  const [selectedVariant, setSelectedVariant] = useState<ProductVariant | undefined>(
    product.variants && product.variants.length > 0 ? product.variants[0] : undefined
  );
  const [quantity, setQuantity] = useState(1);
  const [isCopied, setIsCopied] = useState(false);

  const isFavorited = isInWishlist(product.id);
  const currentPriceAed = product.basePriceAed + (selectedVariant ? selectedVariant.priceAdjustmentAed : 0);
  const currentStock = selectedVariant ? selectedVariant.stock : product.stock;
  const isOutOfStock = currentStock <= 0;

  const handleShare = () => {
    navigator.clipboard.writeText(window.location.href);
    setIsCopied(true);
    setTimeout(() => setIsCopied(false), 2000);
  };

  const handleAddToCart = () => {
    if (isOutOfStock) return;
    addToCart(product, quantity, selectedVariant);
  };

  return (
    <div className="container" style={{ padding: '2rem 1.5rem 5rem' }}>
      {/* Breadcrumbs */}
      <nav style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.82rem', color: 'var(--text-secondary)', marginBottom: '2rem' }}>
        <Link href="/" style={{ color: 'inherit', textDecoration: 'none' }}>Home</Link>
        <span>/</span>
        <Link href={`/shop?cat=${encodeURIComponent(product.category)}`} style={{ color: 'inherit', textDecoration: 'none' }}>
          {product.category}
        </Link>
        <span>/</span>
        <span style={{ color: '#fff', fontWeight: 600 }}>{product.title}</span>
      </nav>

      {/* Main PDP Grid */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '3rem', alignItems: 'flex-start' }}>
        {/* Left: Product Images Gallery */}
        <div>
          <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '20px', overflow: 'hidden', padding: '1rem', position: 'relative', marginBottom: '1rem' }}>
            <img
              src={selectedImage}
              alt={product.title}
              style={{ width: '100%', height: '420px', objectFit: 'contain', borderRadius: '12px', background: '#0b0f19' }}
            />

            {product.isFlashDeal && (
              <span style={{ position: 'absolute', top: '1.25rem', left: '1.25rem', display: 'inline-flex', alignItems: 'center', gap: '0.3rem', background: '#f43f5e', color: '#fff', fontSize: '0.75rem', fontWeight: 800, padding: '0.3rem 0.75rem', borderRadius: '8px' }}>
                <Zap size={13} fill="#fff" /> Ramadan Flash Deal
              </span>
            )}
          </div>

          {/* Thumbnails */}
          {product.galleryImages && product.galleryImages.length > 1 && (
            <div style={{ display: 'flex', gap: '0.75rem', overflowX: 'auto' }}>
              {product.galleryImages.map((img, idx) => (
                <div
                  key={idx}
                  onClick={() => setSelectedImage(img)}
                  style={{
                    width: '75px',
                    height: '75px',
                    borderRadius: '10px',
                    background: '#111827',
                    border: selectedImage === img ? '2px solid var(--accent-cyan)' : '1px solid var(--border-subtle)',
                    overflow: 'hidden',
                    cursor: 'pointer',
                    flexShrink: 0,
                  }}
                >
                  <img src={img} alt="thumbnail" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Right: Product Details & Purchase Form */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          {/* Brand & Ratings */}
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '0.5rem' }}>
            <span style={{ padding: '0.2rem 0.6rem', borderRadius: '6px', background: 'rgba(14,165,233,0.15)', color: 'var(--accent-cyan)', fontSize: '0.8rem', fontWeight: 700 }}>
              {product.brand}
            </span>

            <div style={{ display: 'flex', alignItems: 'center', gap: '0.3rem', color: '#fbbf24', fontSize: '0.85rem', fontWeight: 700 }}>
              <Star size={16} fill="#fbbf24" />
              <span>{product.rating.toFixed(1)}</span>
              <span style={{ color: 'var(--text-muted)' }}>({product.reviewCount} Verified UAE Reviews)</span>
            </div>
          </div>

          <h1 style={{ fontSize: '1.85rem', lineHeight: '1.25', color: '#fff' }}>{product.title}</h1>
          <p style={{ fontSize: '0.9rem', color: 'var(--text-secondary)', lineHeight: '1.5' }}>{product.description}</p>

          {/* Pricing Box */}
          <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.25rem' }}>
            <div style={{ display: 'flex', alignItems: 'baseline', gap: '0.75rem', flexWrap: 'wrap' }}>
              <span style={{ fontSize: '2rem', fontWeight: 800, color: 'var(--text-primary)' }}>
                {formatPrice(currentPriceAed)}
              </span>
              {product.compareAtPriceAed && product.compareAtPriceAed > currentPriceAed && (
                <span style={{ fontSize: '1.1rem', color: 'var(--text-muted)', textDecoration: 'line-through' }}>
                  {formatPrice(product.compareAtPriceAed)}
                </span>
              )}
              <span style={{ fontSize: '0.78rem', color: '#10b981', fontWeight: 700, background: 'rgba(16,185,129,0.15)', padding: '0.2rem 0.5rem', borderRadius: '6px' }}>
                5% UAE VAT Included
              </span>
            </div>

            {/* Tabby & Tamara BNPL Widgets */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '0.75rem', marginTop: '1rem' }}>
              {/* Tabby */}
              <div style={{ background: '#0b0f19', border: '1px solid rgba(59,252,135,0.3)', borderRadius: '10px', padding: '0.75rem', display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
                <span style={{ padding: '0.15rem 0.4rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontWeight: 800, fontSize: '0.7rem' }}>
                  tabby
                </span>
                <div style={{ fontSize: '0.78rem', color: '#cbd5e1' }}>
                  4 interest-free payments of <strong>{formatBnpl(currentPriceAed, 4)}</strong>
                </div>
              </div>

              {/* Tamara */}
              <div style={{ background: '#0b0f19', border: '1px solid rgba(246,147,113,0.3)', borderRadius: '10px', padding: '0.75rem', display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
                <span style={{ padding: '0.15rem 0.4rem', borderRadius: '4px', background: '#fce0c6', color: '#2c1006', fontWeight: 800, fontSize: '0.7rem' }}>
                  tamara
                </span>
                <div style={{ fontSize: '0.78rem', color: '#cbd5e1' }}>
                  Split in 3 payments of <strong>{formatBnpl(currentPriceAed, 3)}</strong>
                </div>
              </div>
            </div>
          </div>

          {/* Variant Selector */}
          {product.hasVariants && product.variants && product.variants.length > 0 && (
            <div>
              <div style={{ fontSize: '0.85rem', fontWeight: 700, color: '#fff', marginBottom: '0.6rem' }}>
                Select Configuration / Variant:
              </div>
              <div style={{ display: 'flex', gap: '0.6rem', flexWrap: 'wrap' }}>
                {product.variants.map((v) => (
                  <button
                    key={v.id}
                    onClick={() => setSelectedVariant(v)}
                    style={{
                      padding: '0.5rem 0.85rem',
                      borderRadius: '8px',
                      border: selectedVariant?.id === v.id ? '2px solid var(--accent-cyan)' : '1px solid var(--border-subtle)',
                      background: selectedVariant?.id === v.id ? 'rgba(14,165,233,0.15)' : '#111827',
                      color: selectedVariant?.id === v.id ? '#fff' : 'var(--text-secondary)',
                      fontWeight: selectedVariant?.id === v.id ? 700 : 500,
                      fontSize: '0.82rem',
                      cursor: 'pointer',
                    }}
                  >
                    {v.name} {v.priceAdjustmentAed > 0 ? `(+AED ${v.priceAdjustmentAed})` : ''}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Stock Status */}
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.85rem' }}>
            <div style={{ width: '10px', height: '10px', borderRadius: '50%', background: isOutOfStock ? '#f43f5e' : currentStock <= product.lowStockThreshold ? '#f59e0b' : '#10b981' }} />
            <span style={{ color: isOutOfStock ? '#f43f5e' : currentStock <= product.lowStockThreshold ? '#f59e0b' : '#10b981', fontWeight: 700 }}>
              {isOutOfStock ? 'Out of Stock' : currentStock <= product.lowStockThreshold ? `Only ${currentStock} left in Dubai Hub - Order soon!` : `In Stock at Dubai Central Warehouse (${currentStock} units)`}
            </span>
          </div>

          {/* Add to Cart Actions */}
          <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center', flexWrap: 'wrap' }}>
            {/* Quantity Selector */}
            <div style={{ display: 'flex', alignItems: 'center', background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '0.35rem 0.6rem', gap: '0.75rem' }}>
              <button
                onClick={() => setQuantity(Math.max(1, quantity - 1))}
                style={{ background: 'none', border: 'none', color: '#fff', cursor: 'pointer' }}
              >
                <Minus size={15} />
              </button>
              <span style={{ fontWeight: 800, minWidth: '20px', textAlign: 'center', color: '#fff' }}>{quantity}</span>
              <button
                onClick={() => setQuantity(quantity + 1)}
                style={{ background: 'none', border: 'none', color: '#fff', cursor: 'pointer' }}
              >
                <Plus size={15} />
              </button>
            </div>

            {/* Main Add to Cart */}
            <button
              onClick={handleAddToCart}
              disabled={isOutOfStock}
              className="btn-primary"
              style={{ flex: 1, minWidth: '180px', padding: '0.85rem 1.5rem', fontSize: '1rem' }}
            >
              <ShoppingBag size={18} />
              <span>{isOutOfStock ? 'Out of Stock' : 'Add to Shopping Bag'}</span>
            </button>

            {/* Wishlist */}
            <button
              onClick={() => toggleWishlist(product.id)}
              style={{
                width: '46px',
                height: '46px',
                borderRadius: '10px',
                background: '#111827',
                border: '1px solid var(--border-subtle)',
                color: isFavorited ? '#f43f5e' : '#cbd5e1',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
              }}
              title="Add to Wishlist"
            >
              <Heart size={20} fill={isFavorited ? '#f43f5e' : 'none'} />
            </button>

            {/* Share */}
            <button
              onClick={handleShare}
              style={{
                width: '46px',
                height: '46px',
                borderRadius: '10px',
                background: '#111827',
                border: '1px solid var(--border-subtle)',
                color: '#cbd5e1',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
              }}
              title="Share Link"
            >
              {isCopied ? <Check size={18} color="#10b981" /> : <Share2 size={18} />}
            </button>
          </div>

          {/* Secondary Buy on Amazon.ae Button */}
          {product.amazonAsin && (
            <a
              href={getAmazonProductUrl(product.amazonAsin)}
              target="_blank"
              rel="noreferrer"
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '0.5rem',
                background: '#232f3e',
                color: '#ff9900',
                border: '1px solid rgba(255,153,0,0.4)',
                borderRadius: '10px',
                padding: '0.75rem',
                fontWeight: 700,
                fontSize: '0.88rem',
                textDecoration: 'none',
                transition: 'var(--transition-fast)',
              }}
            >
              <span>Or Buy on Amazon.ae ({formatPrice(product.amazonPriceAed || product.basePriceAed)})</span>
              <ExternalLink size={15} />
            </a>
          )}

          {/* Trust Highlights */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '0.75rem', marginTop: '0.5rem', fontSize: '0.8rem', color: '#cbd5e1' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <Truck size={16} color="var(--accent-cyan)" />
              <span>Amazon MCF & Same-Day Dubai</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <ShieldCheck size={16} color="var(--accent-gold)" />
              <span>100% Genuine UAE Version</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <RotateCcw size={16} color="#10b981" />
              <span>14-Day Free Returns</span>
            </div>
          </div>
        </div>
      </div>

      {/* Frequently Bought Together on Amazon.ae Bundle */}
      <AmazonUpsellSection product={product} />

      {/* Verified Reviews */}
      {product.reviews && product.reviews.length > 0 && (
        <section style={{ marginTop: '3.5rem', background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '2rem' }}>
          <h3 style={{ fontSize: '1.3rem', color: '#fff', marginBottom: '1.25rem' }}>
            Customer Reviews & Ratings ({product.reviews.length})
          </h3>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
            {product.reviews.map((rev) => (
              <div key={rev.id} style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1.25rem' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.4rem' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                    <span style={{ fontWeight: 700, color: '#fff' }}>{rev.author}</span>
                    {rev.verifiedPurchase && (
                      <span style={{ fontSize: '0.7rem', color: '#10b981', background: 'rgba(16,185,129,0.15)', padding: '0.1rem 0.4rem', borderRadius: '4px' }}>
                        ✓ Verified UAE Buyer
                      </span>
                    )}
                  </div>
                  <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>{rev.date} • {rev.location}</span>
                </div>

                <div style={{ display: 'flex', gap: '0.2rem', color: '#fbbf24', marginBottom: '0.35rem' }}>
                  {[...Array(rev.rating)].map((_, i) => (
                    <Star key={i} size={14} fill="#fbbf24" />
                  ))}
                </div>

                <div style={{ fontWeight: 600, color: '#e2e8f0', fontSize: '0.9rem', marginBottom: '0.25rem' }}>{rev.title}</div>
                <p style={{ fontSize: '0.82rem', color: 'var(--text-secondary)', lineHeight: '1.5' }}>{rev.comment}</p>
              </div>
            ))}
          </div>
        </section>
      )}
    </div>
  );
}
