'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import {
  ArrowRight,
  Zap,
  ShieldCheck,
  Truck,
  Sparkles,
  ChevronRight,
  TrendingUp,
  Smartphone,
  Laptop,
  Headphones,
  Watch,
  Flame,
} from 'lucide-react';
import { useStore } from '../context/StoreContext';
import { useCurrency } from '../context/CurrencyContext';
import { ProductCard } from '../components/products/ProductCard';
import { CATEGORIES } from '../lib/magento/mockData';

export default function HomePage() {
  const { storeIdentity, products } = useStore();
  const { formatPrice, currency } = useCurrency();

  // Flash deal countdown timer
  const [timeLeft, setTimeLeft] = useState({ hours: 47, minutes: 59, seconds: 30 });

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev.seconds > 0) return { ...prev, seconds: prev.seconds - 1 };
        if (prev.minutes > 0) return { ...prev, minutes: 59, seconds: 59 };
        if (prev.hours > 0) return { hours: prev.hours - 1, minutes: 59, seconds: 59 };
        return prev;
      });
    }, 1000);
    return () => clearInterval(timer);
  }, []);

  const flashDeals = products.filter((p) => p.isFlashDeal);
  const featuredProducts = products.filter((p) => p.isFeatured || p.isTrending);

  const categoryIcons: Record<string, React.ReactNode> = {
    Smartphones: <Smartphone size={24} color="var(--accent-cyan)" />,
    Laptops: <Laptop size={24} color="var(--accent-gold)" />,
    Audio: <Headphones size={24} color="#10b981" />,
    Wearables: <Watch size={24} color="#f43f5e" />,
    'Beauty & Lifestyle': <Sparkles size={24} color="#c084fc" />,
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '4rem', paddingBottom: '4rem' }}>
      {/* Hero Section */}
      <section style={{ position: 'relative', overflow: 'hidden', padding: '3.5rem 0 4.5rem', background: 'radial-gradient(ellipse at 70% 20%, rgba(14, 165, 233, 0.15), transparent 60%), radial-gradient(ellipse at 20% 80%, rgba(245, 158, 11, 0.12), transparent 50%), #070a12', borderBottom: '1px solid var(--border-subtle)' }}>
        <div className="container" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '3rem', alignItems: 'center' }}>
          {/* Left Hero Content */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
            <div style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', background: 'rgba(255,255,255,0.06)', border: '1px solid var(--border-subtle)', borderRadius: '999px', padding: '0.35rem 0.85rem', width: 'fit-content' }}>
              <span style={{ width: '8px', height: '8px', borderRadius: '50%', background: '#10b981' }}></span>
              <span style={{ fontSize: '0.78rem', fontWeight: 600, color: '#e2e8f0' }}>
                {storeIdentity.heroBadgeText || 'Dubai Central Warehouse • Same-Day Dispatch'}
              </span>
            </div>

            <h1 style={{ fontSize: 'clamp(2.2rem, 5vw, 3.5rem)', lineHeight: '1.1', fontWeight: 800, letterSpacing: '-0.03em' }}>
              {storeIdentity.heroTitle || 'Next-Gen Tech & Luxury Essentials'} <br />
              <span style={{ background: 'linear-gradient(135deg, #38bdf8 0%, #0284c7 50%, #f59e0b 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent' }}>
                {storeIdentity.heroHighlight || 'Delivered Across UAE'}
              </span>
            </h1>

            <p style={{ fontSize: '1.05rem', color: 'var(--text-secondary)', lineHeight: '1.6', maxWidth: '520px' }}>
              {storeIdentity.heroDescription ||
                'Official TRA-approved devices with manufacturer warranties. Enjoy Amazon Logistics delivery, 5% UAE VAT Invoices, and split your bill in 4 with Tabby.'}
            </p>

            {/* CTAs */}
            <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', marginTop: '0.5rem' }}>
              <Link href={storeIdentity.heroCtaPrimaryLink || '/shop'} className="btn-primary" style={{ padding: '0.85rem 1.75rem', fontSize: '1rem' }}>
                <span>{storeIdentity.heroCtaPrimaryText || 'Explore Catalog'}</span>
                <ArrowRight size={18} />
              </Link>
              <Link href={storeIdentity.heroCtaSecondaryLink || '/admin'} className="btn-secondary" style={{ padding: '0.85rem 1.5rem', fontSize: '0.95rem' }}>
                <span>{storeIdentity.heroCtaSecondaryText || 'Admin Control Center'}</span>
              </Link>
            </div>

            {/* Micro badges */}
            <div style={{ display: 'flex', gap: '1.5rem', flexWrap: 'wrap', paddingTop: '1rem', borderTop: '1px solid rgba(255,255,255,0.08)', fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
                <Truck size={16} color="var(--accent-cyan)" />
                <span>Amazon MCF & Dubai Express</span>
              </div>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
                <ShieldCheck size={16} color="var(--accent-gold)" />
                <span>UAE License: {storeIdentity.tradeLicenseNumber}</span>
              </div>
            </div>
          </div>

          {/* Right Hero Featured Showcase Card */}
          {products[0] && (
            <div style={{ position: 'relative' }}>
              <div style={{ position: 'absolute', inset: '-15px', background: 'radial-gradient(circle, rgba(14,165,233,0.3) 0%, transparent 70%)', filter: 'blur(30px)', zIndex: 0 }}></div>
              <div style={{ position: 'relative', zIndex: 1, background: '#111827', border: '1px solid var(--border-hover)', borderRadius: '24px', padding: '1.75rem', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.7)' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
                  <span style={{ padding: '0.25rem 0.65rem', borderRadius: '999px', background: 'rgba(245,158,11,0.15)', color: '#fbbf24', fontSize: '0.75rem', fontWeight: 800, textTransform: 'uppercase' }}>
                    ★ Spotlight of the Week
                  </span>
                  <span style={{ fontSize: '0.8rem', color: '#10b981', fontWeight: 700 }}>
                    In Stock (Dubai Hub)
                  </span>
                </div>

                <Link href={`/product/${products[0].slug}`} style={{ display: 'block', textDecoration: 'none' }}>
                  <img
                    src={products[0].featuredImage}
                    alt={products[0].title}
                    style={{ width: '100%', height: '240px', objectFit: 'cover', borderRadius: '16px', marginBottom: '1.25rem', background: '#0b0f19' }}
                  />

                  <div style={{ fontSize: '1.25rem', fontWeight: 800, color: '#fff', marginBottom: '0.4rem' }}>
                    {products[0].title}
                  </div>
                  <p style={{ fontSize: '0.85rem', color: 'var(--text-secondary)', marginBottom: '1rem' }}>
                    {products[0].shortDescription}
                  </p>

                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: '0.75rem', borderTop: '1px solid var(--border-subtle)' }}>
                    <div>
                      <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Special UAE Launch Price:</div>
                      <div style={{ fontSize: '1.5rem', fontWeight: 800, color: 'var(--accent-gold)' }}>
                        {formatPrice(products[0].basePriceAed)}
                      </div>
                    </div>

                    <span className="btn-primary" style={{ padding: '0.5rem 1rem', fontSize: '0.85rem' }}>
                      Shop Now
                    </span>
                  </div>
                </Link>
              </div>
            </div>
          )}
        </div>
      </section>

      {/* Flash Deals with Live Countdown */}
      {flashDeals.length > 0 && (
        <section className="container">
          <div style={{ background: 'linear-gradient(135deg, #1e1b4b, #111827)', border: '1px solid rgba(244,63,94,0.3)', borderRadius: '20px', padding: '1.75rem', marginBottom: '1rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', marginBottom: '1.5rem' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                <div style={{ width: '40px', height: '40px', borderRadius: '10px', background: '#f43f5e', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <Flame size={22} color="#fff" />
                </div>
                <div>
                  <h2 style={{ fontSize: '1.35rem', color: '#fff' }}>Ramadan Flash Deals & Limited Offers</h2>
                  <div style={{ fontSize: '0.8rem', color: '#fda4af' }}>Special discounts with instant Tabby 4x split</div>
                </div>
              </div>

              {/* Countdown Ticker */}
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                <span style={{ fontSize: '0.8rem', color: '#cbd5e1', fontWeight: 600 }}>Offer Ends In:</span>
                <div style={{ display: 'flex', gap: '0.35rem' }}>
                  <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '6px', padding: '0.3rem 0.6rem', fontWeight: 800, fontSize: '0.9rem', color: '#fbbf24' }}>
                    {String(timeLeft.hours).padStart(2, '0')}h
                  </div>
                  <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '6px', padding: '0.3rem 0.6rem', fontWeight: 800, fontSize: '0.9rem', color: '#fbbf24' }}>
                    {String(timeLeft.minutes).padStart(2, '0')}m
                  </div>
                  <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '6px', padding: '0.3rem 0.6rem', fontWeight: 800, fontSize: '0.9rem', color: '#f43f5e' }}>
                    {String(timeLeft.seconds).padStart(2, '0')}s
                  </div>
                </div>
              </div>
            </div>

            <div className="grid-products">
              {flashDeals.map((prod) => (
                <ProductCard key={prod.id} product={prod} />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Category Explorer */}
      <section className="container">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
          <div>
            <h2 style={{ fontSize: '1.5rem', color: '#fff' }}>Shop by Department</h2>
            <p style={{ fontSize: '0.85rem', color: 'var(--text-secondary)' }}>Curated luxury collections tailored for UAE lifestyle</p>
          </div>
          <Link href="/shop" style={{ display: 'flex', alignItems: 'center', gap: '0.3rem', color: 'var(--accent-cyan)', fontSize: '0.88rem', fontWeight: 600, textDecoration: 'none' }}>
            <span>View All</span>
            <ChevronRight size={16} />
          </Link>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '1rem' }}>
          {CATEGORIES.filter((c) => c !== 'All Products').map((cat) => (
            <Link
              key={cat}
              href={`/shop?cat=${encodeURIComponent(cat)}`}
              style={{
                background: '#111827',
                border: '1px solid var(--border-subtle)',
                borderRadius: '14px',
                padding: '1.25rem',
                textDecoration: 'none',
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                textAlign: 'center',
                gap: '0.75rem',
                transition: 'var(--transition-normal)',
              }}
              className="glow-hover"
            >
              <div style={{ width: '48px', height: '48px', borderRadius: '12px', background: '#0b0f19', border: '1px solid var(--border-subtle)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                {categoryIcons[cat] || <Sparkles size={24} color="var(--accent-cyan)" />}
              </div>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.92rem' }}>{cat}</div>
              <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>
                {products.filter((p) => p.category === cat).length} Products
              </div>
            </Link>
          ))}
        </div>
      </section>

      {/* Featured Bestsellers */}
      <section className="container">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
            <TrendingUp size={22} color="var(--accent-cyan)" />
            <div>
              <h2 style={{ fontSize: '1.5rem', color: '#fff' }}>Trending in UAE</h2>
              <p style={{ fontSize: '0.85rem', color: 'var(--text-secondary)' }}>Highest rated electronics and accessories in Dubai & Abu Dhabi</p>
            </div>
          </div>
          <Link href="/shop" style={{ display: 'flex', alignItems: 'center', gap: '0.3rem', color: 'var(--accent-cyan)', fontSize: '0.88rem', fontWeight: 600, textDecoration: 'none' }}>
            <span>Explore All</span>
            <ChevronRight size={16} />
          </Link>
        </div>

        <div className="grid-products">
          {featuredProducts.map((prod) => (
            <ProductCard key={prod.id} product={prod} />
          ))}
        </div>
      </section>
    </div>
  );
}
