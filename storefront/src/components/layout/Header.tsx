'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { motion, useScroll, useMotionValueEvent } from 'framer-motion';
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
import { Magnetic } from '../ui/Magnetic';

export const Header: React.FC = () => {
  const pathname = usePathname();
  const router = useRouter();
  const { storeIdentity, searchQuery, setSearchQuery, products } = useStore();
  const { currency, setCurrency, rates, refreshLiveRates, isLoadingRates } = useCurrency();
  const { totalItemCount, wishlist, setIsCartOpen, freeShippingProgress, amountNeededForFreeShippingAed } = useCart();

  const [isCurrencyDropdownOpen, setIsCurrencyDropdownOpen] = useState(false);
  const [isSearchFocused, setIsSearchFocused] = useState(false);
  
  const { scrollY } = useScroll();
  const [hidden, setHidden] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);

  useMotionValueEvent(scrollY, "change", (latest) => {
    const previous = scrollY.getPrevious() ?? 0;
    if (latest > previous && latest > 150) {
      setHidden(true);
    } else {
      setHidden(false);
    }
    
    if (latest > 20) {
      setIsScrolled(true);
    } else {
      setIsScrolled(false);
    }
  });

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

  const searchSuggestions = searchQuery.trim()
    ? products.filter((p) => p.title.toLowerCase().includes(searchQuery.toLowerCase())).slice(0, 4)
    : [];

  return (
    <motion.header 
      variants={{
        visible: { y: 0, opacity: 1 },
        hidden: { y: "-100%", opacity: 0 }
      }}
      animate={hidden ? "hidden" : "visible"}
      transition={{ duration: 0.35, ease: "easeInOut" }}
      style={{ 
        position: 'fixed', 
        top: isScrolled ? '1rem' : 0,
        left: 0, 
        right: 0, 
        zIndex: 50,
        display: 'flex',
        justifyContent: 'center',
        pointerEvents: 'none',
        padding: isScrolled ? '0 1rem' : 0,
      }}
    >
      <div 
        className={isScrolled ? "glass-magnetic" : ""}
        style={{ 
          width: '100%', 
          maxWidth: isScrolled ? '1200px' : '100%',
          borderRadius: isScrolled ? '100px' : '0px',
          background: isScrolled ? 'var(--bg-glass-gradient)' : 'rgba(11, 15, 25, 0.95)',
          backdropFilter: 'blur(24px)',
          WebkitBackdropFilter: 'blur(24px)',
          border: isScrolled ? '1px solid rgba(255,255,255,0.1)' : 'none',
          borderBottom: !isScrolled ? '1px solid var(--border-subtle)' : undefined,
          boxShadow: isScrolled ? '0 20px 40px rgba(0,0,0,0.4)' : 'none',
          transition: 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
          pointerEvents: 'auto',
          overflow: 'visible',
        }}
      >
        {/* Announcement Bar (Only show when not scrolled) */}
        {!isScrolled && (
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
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Main Navbar */}
        <div style={{ padding: isScrolled ? '0.6rem 1.5rem' : '0.85rem 1.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '1.5rem', maxWidth: isScrolled ? '100%' : '1280px', margin: '0 auto' }}>
          
          {/* Brand Logo & Name */}
          <Link href="/" style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', textDecoration: 'none', color: 'inherit' }}>
            <Magnetic pullFactor={0.2}>
              <div style={{ width: isScrolled ? '36px' : '40px', height: isScrolled ? '36px' : '40px', borderRadius: '50%', background: 'linear-gradient(135deg, #0284c7, #0ea5e9)', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: 'var(--shadow-glow)', transition: 'all 0.3s' }}>
                <Sparkles size={isScrolled ? 18 : 22} color="#ffffff" />
              </div>
            </Magnetic>
            <div className="hidden md:block">
              <div style={{ fontSize: isScrolled ? '1.1rem' : '1.25rem', fontWeight: 800, letterSpacing: '-0.02em', background: 'linear-gradient(135deg, #ffffff 40%, #cbd5e1 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent', transition: 'font-size 0.3s' }}>
                {storeIdentity.storeName}
              </div>
              {!isScrolled && (
                <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)', fontWeight: 500 }}>
                  {storeIdentity.tagline}
                </div>
              )}
            </div>
          </Link>

          {/* Search Bar with Live Suggestions */}
          <div style={{ flex: 1, maxWidth: '440px', position: 'relative' }} className="hidden sm:block">
            <form onSubmit={handleSearchSubmit}>
              <div style={{ position: 'relative', display: 'flex', alignItems: 'center' }}>
                <input
                  type="text"
                  placeholder="Search products..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  onFocus={() => setIsSearchFocused(true)}
                  onBlur={() => setTimeout(() => setIsSearchFocused(false), 250)}
                  className="input-field"
                  style={{
                    paddingLeft: '2.5rem',
                    paddingRight: '1rem',
                    borderRadius: '99px',
                    background: isScrolled ? 'rgba(0,0,0,0.2)' : '#111827',
                    border: '1px solid rgba(255,255,255,0.08)',
                    fontSize: '0.85rem',
                    paddingTop: '0.5rem',
                    paddingBottom: '0.5rem',
                  }}
                />
                <Search size={15} color="var(--text-muted)" style={{ position: 'absolute', left: '1rem' }} />
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
                  background: 'rgba(30, 41, 59, 0.95)',
                  backdropFilter: 'blur(20px)',
                  border: '1px solid var(--border-hover)',
                  borderRadius: '16px',
                  padding: '0.5rem',
                  boxShadow: '0 20px 40px rgba(0,0,0,0.7)',
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
                      borderRadius: '12px',
                      textDecoration: 'none',
                      color: '#f8fafc',
                      transition: 'background 0.15s',
                    }}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'rgba(255,255,255,0.05)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}
                  >
                    <img src={prod.featuredImage} alt={prod.title} style={{ width: '40px', height: '40px', objectFit: 'cover', borderRadius: '8px' }} />
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
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
            {/* Shop link */}
            <Magnetic>
              <Link
                href="/shop"
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  width: '42px',
                  height: '42px',
                  borderRadius: '50%',
                  color: pathname === '/shop' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
                  background: pathname === '/shop' ? 'rgba(14, 165, 233, 0.1)' : 'transparent',
                  textDecoration: 'none',
                  transition: 'color 0.2s, background 0.2s',
                }}
                title="Catalog"
              >
                <SlidersHorizontal size={20} />
              </Link>
            </Magnetic>

            {/* Admin Center */}
            <Magnetic>
              <Link
                href="/admin"
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  width: '42px',
                  height: '42px',
                  borderRadius: '50%',
                  color: pathname === '/admin' ? '#fbbf24' : 'var(--text-secondary)',
                  background: pathname === '/admin' ? 'rgba(245, 158, 11, 0.1)' : 'transparent',
                  textDecoration: 'none',
                  transition: 'color 0.2s, background 0.2s',
                }}
                title="Admin Center"
              >
                <Settings size={20} />
              </Link>
            </Magnetic>

            {/* Account */}
            <Magnetic>
              <Link
                href="/account"
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  width: '42px',
                  height: '42px',
                  borderRadius: '50%',
                  color: 'var(--text-secondary)',
                  textDecoration: 'none',
                  transition: 'color 0.2s',
                }}
                title="Account"
                onMouseEnter={(e) => (e.currentTarget.style.color = '#fff')}
                onMouseLeave={(e) => (e.currentTarget.style.color = 'var(--text-secondary)')}
              >
                <User size={20} />
              </Link>
            </Magnetic>

            {/* Wishlist */}
            <Magnetic>
              <Link
                href="/account?tab=wishlist"
                style={{
                  position: 'relative',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  width: '42px',
                  height: '42px',
                  borderRadius: '50%',
                  color: wishlist.length > 0 ? '#f43f5e' : 'var(--text-secondary)',
                  textDecoration: 'none',
                  transition: 'color 0.2s',
                }}
                title="Wishlist"
              >
                <Heart size={20} fill={wishlist.length > 0 ? '#f43f5e' : 'none'} />
                {wishlist.length > 0 && (
                  <span style={{
                    position: 'absolute',
                    top: '2px',
                    right: '2px',
                    background: '#f43f5e',
                    color: '#fff',
                    fontSize: '0.65rem',
                    fontWeight: 700,
                    width: '16px',
                    height: '16px',
                    borderRadius: '50%',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                  }}>
                    {wishlist.length}
                  </span>
                )}
              </Link>
            </Magnetic>

            {/* Cart Trigger */}
            <Magnetic pullFactor={0.3}>
              <button
                onClick={() => setIsCartOpen(true)}
                style={{
                  position: 'relative',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  width: '48px',
                  height: '48px',
                  background: 'linear-gradient(135deg, #0ea5e9, #0284c7)',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '50%',
                  cursor: 'pointer',
                  boxShadow: 'var(--shadow-glow)',
                  marginLeft: '0.5rem',
                  transition: 'transform 0.2s',
                }}
              >
                <ShoppingBag size={20} />
                {totalItemCount > 0 && (
                  <span style={{
                    position: 'absolute',
                    top: '-4px',
                    right: '-4px',
                    background: '#ffffff',
                    color: '#0284c7',
                    fontSize: '0.75rem',
                    fontWeight: 800,
                    minWidth: '20px',
                    height: '20px',
                    padding: '0 4px',
                    borderRadius: '10px',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
                  }}>
                    {totalItemCount}
                  </span>
                )}
              </button>
            </Magnetic>
          </div>
        </div>
      </div>
    </motion.header>
  );
};
