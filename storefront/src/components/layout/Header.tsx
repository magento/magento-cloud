'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import {
  ShoppingBag,
  Heart,
  Search,
  SlidersHorizontal,
  User,
  Settings,
  Sparkles,
  Zap,
  Globe,
  Check,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { useCart } from '../../context/CartContext';
import { CurrencyCode } from '../../lib/types/commerce';

export const Header: React.FC = () => {
  const pathname = usePathname();
  const router = useRouter();
  const { storeIdentity, searchQuery, setSearchQuery, selectedCategory, setSelectedCategory, products } = useStore();
  const { currency, setCurrency, rates, refreshLiveRates, isLoadingRates } = useCurrency();
  const { totalItemCount, wishlist, setIsCartOpen, freeShippingProgress, amountNeededForFreeShippingAed } = useCart();

  const [isCurrencyDropdownOpen, setIsCurrencyDropdownOpen] = useState(false);
  const [isSearchFocused, setIsSearchFocused] = useState(false);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (pathname !== '/shop') {
      router.push(`/shop?q=${encodeURIComponent(searchQuery)}`);
    }
  };

  const currencyList: { code: CurrencyCode; label: string; symbol: string }[] = [
    { code: 'AED', label: 'UAE Dirham (د.إ)', symbol: 'AED' },
    { code: 'USD', label: 'US Dollar', symbol: '$' },
    { code: 'INR', label: 'Indian Rupee', symbol: '₹' },
  ];

  // Quick search suggestions
  const searchSuggestions = searchQuery.trim()
    ? products.filter((p) => p.title.toLowerCase().includes(searchQuery.toLowerCase())).slice(0, 4)
    : [];

  return (
    <header style={{ position: 'sticky', top: 0, zIndex: 40, background: 'rgba(11, 15, 25, 0.92)', backdropFilter: 'blur(16px)', borderBottom: '1px solid var(--border-subtle)' }}>
      {/* Announcement Bar */}
      <div style={{ background: 'linear-gradient(90deg, #0f172a, #1e293b, #0f172a)', borderBottom: '1px solid rgba(255,255,255,0.06)', padding: '0.4rem 1rem', fontSize: '0.8rem' }}>
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '0.5rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', color: '#e2e8f0' }}>
            <span style={{ display: 'inline-flex', padding: '0.15rem 0.4rem', borderRadius: '4px', background: 'rgba(245,158,11,0.2)', color: '#fbbf24', fontWeight: 700, fontSize: '0.72rem' }}>
              UAE FTA COMPLIANT
            </span>
            <span>{storeIdentity.announcementText}</span>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '1.25rem', color: 'var(--text-secondary)' }}>
            {/* Free Shipping Progress in bar */}
            <div style={{ display: 'none', alignItems: 'center', gap: '0.4rem' }} className="hidden sm:flex">
              <Zap size={14} color="#f59e0b" />
              <span>
                {freeShippingProgress >= 100 ? (
                  <strong style={{ color: '#10b981' }}>FREE UAE Delivery Unlocked!</strong>
                ) : (
                  <span>
                    Add <strong>AED {amountNeededForFreeShippingAed}</strong> for Free UAE Delivery
                  </span>
                )}
              </span>
            </div>

            {/* Currency Selector */}
            <div style={{ position: 'relative' }}>
              <button
                onClick={() => setIsCurrencyDropdownOpen(!isCurrencyDropdownOpen)}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: '0.35rem',
                  background: 'rgba(255,255,255,0.06)',
                  border: '1px solid var(--border-subtle)',
                  borderRadius: '6px',
                  padding: '0.2rem 0.55rem',
                  color: '#f9fafb',
                  fontSize: '0.78rem',
                  fontWeight: 600,
                  cursor: 'pointer',
                }}
              >
                <Globe size={13} color="var(--accent-cyan)" />
                <span>{currency}</span>
              </button>

              {isCurrencyDropdownOpen && (
                <div
                  style={{
                    position: 'absolute',
                    right: 0,
                    top: '100%',
                    marginTop: '0.3rem',
                    background: '#1f2937',
                    border: '1px solid var(--border-hover)',
                    borderRadius: '8px',
                    padding: '0.4rem',
                    boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
                    minWidth: '180px',
                    zIndex: 50,
                  }}
                >
                  <div style={{ padding: '0.25rem 0.5rem', fontSize: '0.7rem', color: '#9ca3af', borderBottom: '1px solid rgba(255,255,255,0.08)', marginBottom: '0.3rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span>Select Currency</span>
                    <button
                      onClick={(e) => {
                        e.stopPropagation();
                        refreshLiveRates();
                      }}
                      style={{ background: 'none', border: 'none', color: 'var(--accent-cyan)', fontSize: '0.68rem', cursor: 'pointer' }}
                    >
                      {isLoadingRates ? 'Updating...' : 'Live FX Sync'}
                    </button>
                  </div>
                  {currencyList.map((c) => (
                    <button
                      key={c.code}
                      onClick={() => {
                        setCurrency(c.code);
                        setIsCurrencyDropdownOpen(false);
                      }}
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        width: '100%',
                        padding: '0.4rem 0.6rem',
                        background: currency === c.code ? 'rgba(14,165,233,0.15)' : 'transparent',
                        color: currency === c.code ? 'var(--accent-cyan)' : '#f9fafb',
                        border: 'none',
                        borderRadius: '6px',
                        fontSize: '0.8rem',
                        cursor: 'pointer',
                        textAlign: 'left',
                      }}
                    >
                      <span>
                        <strong>{c.code}</strong> - {c.label}
                      </span>
                      {currency === c.code && <Check size={14} />}
                    </button>
                  ))}
                  <div style={{ marginTop: '0.3rem', padding: '0.3rem 0.5rem', fontSize: '0.68rem', color: '#6b7280', borderTop: '1px solid rgba(255,255,255,0.08)' }}>
                    Actual Peg: 1 USD = 3.6725 AED <br />
                    Live Rate: 1 AED = {rates.rates.INR.toFixed(2)} INR
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Main Navbar */}
      <div className="container" style={{ padding: '0.85rem 1.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '1.5rem' }}>
        {/* Brand Logo & Name */}
        <Link href="/" style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', textDecoration: 'none', color: 'inherit' }}>
          <div style={{ width: '40px', height: '40px', borderRadius: '10px', background: 'linear-gradient(135deg, #0284c7, #0ea5e9)', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: 'var(--shadow-glow)', overflow: 'hidden' }}>
            <Sparkles size={22} color="#ffffff" />
          </div>
          <div>
            <div style={{ fontSize: '1.25rem', fontWeight: 800, letterSpacing: '-0.02em', background: 'linear-gradient(135deg, #ffffff 40%, #cbd5e1 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent' }}>
              {storeIdentity.storeName}
            </div>
            <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)', fontWeight: 500 }}>
              {storeIdentity.tagline}
            </div>
          </div>
        </Link>

        {/* Search Bar with Live Suggestions */}
        <div style={{ flex: 1, maxWidth: '540px', position: 'relative' }}>
          <form onSubmit={handleSearchSubmit}>
            <div style={{ position: 'relative', display: 'flex', alignItems: 'center' }}>
              <input
                type="text"
                placeholder="Search products, brands (Apple, Sony), Amazon ASINs..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                onFocus={() => setIsSearchFocused(true)}
                onBlur={() => setTimeout(() => setIsSearchFocused(false), 250)}
                className="input-field"
                style={{
                  paddingLeft: '2.5rem',
                  paddingRight: '4rem',
                  borderRadius: 'var(--radius-full)',
                  background: '#111827',
                  border: '1px solid rgba(255,255,255,0.12)',
                  fontSize: '0.88rem',
                }}
              />
              <Search size={16} color="var(--text-muted)" style={{ position: 'absolute', left: '1rem' }} />
              <button
                type="submit"
                style={{
                  position: 'absolute',
                  right: '0.35rem',
                  background: 'linear-gradient(135deg, var(--accent-cyan), #0284c7)',
                  color: '#fff',
                  border: 'none',
                  borderRadius: 'var(--radius-full)',
                  padding: '0.35rem 0.85rem',
                  fontSize: '0.78rem',
                  fontWeight: 600,
                  cursor: 'pointer',
                }}
              >
                Search
              </button>
            </div>
          </form>

          {/* Autocomplete dropdown */}
          {isSearchFocused && searchSuggestions.length > 0 && (
            <div
              style={{
                position: 'absolute',
                top: '100%',
                left: 0,
                right: 0,
                marginTop: '0.4rem',
                background: '#1e293b',
                border: '1px solid var(--border-hover)',
                borderRadius: '12px',
                padding: '0.5rem',
                boxShadow: '0 15px 35px rgba(0,0,0,0.6)',
                zIndex: 50,
              }}
            >
              <div style={{ padding: '0.3rem 0.6rem', fontSize: '0.72rem', color: '#94a3b8', fontWeight: 600, textTransform: 'uppercase' }}>
                Instant Matches
              </div>
              {searchSuggestions.map((prod) => (
                <Link
                  key={prod.id}
                  href={`/product/${prod.slug}`}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.75rem',
                    padding: '0.5rem 0.6rem',
                    borderRadius: '8px',
                    textDecoration: 'none',
                    color: '#f8fafc',
                    transition: 'background 0.15s',
                  }}
                  onMouseEnter={(e) => (e.currentTarget.style.background = '#334155')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}
                >
                  <img src={prod.featuredImage} alt={prod.title} style={{ width: '36px', height: '36px', objectFit: 'cover', borderRadius: '6px' }} />
                  <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ fontSize: '0.85rem', fontWeight: 600, whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                      {prod.title}
                    </div>
                    <div style={{ fontSize: '0.75rem', color: 'var(--accent-gold)' }}>
                      AED {prod.basePriceAed.toLocaleString()} • {prod.brand}
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          )}
        </div>

        {/* Action Badges & Navigation */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
          {/* Shop link */}
          <Link
            href="/shop"
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.4rem',
              color: pathname === '/shop' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
              textDecoration: 'none',
              fontSize: '0.9rem',
              fontWeight: 600,
              padding: '0.4rem 0.75rem',
              borderRadius: '8px',
            }}
          >
            <SlidersHorizontal size={16} />
            <span>Catalog</span>
          </Link>

          {/* Admin Control Center Link */}
          <Link
            href="/admin"
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.4rem',
              background: pathname === '/admin' ? 'rgba(245,158,11,0.2)' : 'rgba(255,255,255,0.05)',
              border: '1px solid ' + (pathname === '/admin' ? 'var(--accent-gold)' : 'var(--border-subtle)'),
              color: pathname === '/admin' ? '#fbbf24' : '#e2e8f0',
              textDecoration: 'none',
              fontSize: '0.82rem',
              fontWeight: 700,
              padding: '0.45rem 0.85rem',
              borderRadius: '8px',
              transition: 'var(--transition-fast)',
            }}
            title="Master Admin Control Center: Stock, Branding, Odoo, Amazon, Payments"
          >
            <Settings size={15} color="#f59e0b" />
            <span>Admin Center</span>
          </Link>

          {/* Customer Account */}
          <Link
            href="/account"
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              width: '38px',
              height: '38px',
              borderRadius: '10px',
              background: 'var(--bg-surface-elevated)',
              border: '1px solid var(--border-subtle)',
              color: 'var(--text-secondary)',
              textDecoration: 'none',
            }}
            title="Customer Portal & Orders"
          >
            <User size={18} />
          </Link>

          {/* Wishlist */}
          <Link
            href="/account?tab=wishlist"
            style={{
              position: 'relative',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              width: '38px',
              height: '38px',
              borderRadius: '10px',
              background: 'var(--bg-surface-elevated)',
              border: '1px solid var(--border-subtle)',
              color: wishlist.length > 0 ? '#f43f5e' : 'var(--text-secondary)',
              textDecoration: 'none',
            }}
            title="Wishlist"
          >
            <Heart size={18} fill={wishlist.length > 0 ? '#f43f5e' : 'none'} />
            {wishlist.length > 0 && (
              <span
                style={{
                  position: 'absolute',
                  top: '-4px',
                  right: '-4px',
                  background: '#f43f5e',
                  color: '#fff',
                  fontSize: '0.68rem',
                  fontWeight: 700,
                  width: '18px',
                  height: '18px',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                }}
              >
                {wishlist.length}
              </span>
            )}
          </Link>

          {/* Cart Trigger */}
          <button
            onClick={() => setIsCartOpen(true)}
            style={{
              position: 'relative',
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem',
              background: 'linear-gradient(135deg, #0ea5e9, #0284c7)',
              color: '#ffffff',
              border: 'none',
              borderRadius: '10px',
              padding: '0.5rem 0.95rem',
              fontWeight: 700,
              fontSize: '0.88rem',
              cursor: 'pointer',
              boxShadow: 'var(--shadow-glow)',
            }}
          >
            <ShoppingBag size={18} />
            <span style={{ display: 'none' }} className="hidden sm:inline">Cart</span>
            {totalItemCount > 0 && (
              <span
                style={{
                  background: '#ffffff',
                  color: '#0284c7',
                  fontSize: '0.75rem',
                  fontWeight: 800,
                  padding: '0.1rem 0.45rem',
                  borderRadius: '999px',
                }}
              >
                {totalItemCount}
              </span>
            )}
          </button>
        </div>
      </div>
    </header>
  );
};
