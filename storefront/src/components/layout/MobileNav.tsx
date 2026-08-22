'use client';

import React from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Home, ShoppingBag, SlidersHorizontal, User, Settings } from 'lucide-react';
import { useCart } from '../../context/CartContext';

export const MobileNav: React.FC = () => {
  const pathname = usePathname();
  const { totalItemCount, setIsCartOpen } = useCart();

  return (
    <div
      style={{
        position: 'fixed',
        bottom: 0,
        left: 0,
        right: 0,
        zIndex: 45,
        background: 'rgba(11, 15, 25, 0.95)',
        backdropFilter: 'blur(16px)',
        borderTop: '1px solid var(--border-subtle)',
        padding: '0.5rem 1rem 0.75rem',
        display: 'flex',
        justifyContent: 'space-around',
        alignItems: 'center',
      }}
      className="md:hidden"
    >
      <Link
        href="/"
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '0.2rem',
          color: pathname === '/' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
          textDecoration: 'none',
          fontSize: '0.7rem',
          fontWeight: 600,
        }}
      >
        <Home size={20} />
        <span>Home</span>
      </Link>

      <Link
        href="/shop"
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '0.2rem',
          color: pathname === '/shop' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
          textDecoration: 'none',
          fontSize: '0.7rem',
          fontWeight: 600,
        }}
      >
        <SlidersHorizontal size={20} />
        <span>Catalog</span>
      </Link>

      {/* Cart Trigger */}
      <button
        onClick={() => setIsCartOpen(true)}
        style={{
          position: 'relative',
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '0.2rem',
          background: 'none',
          border: 'none',
          color: 'var(--text-secondary)',
          fontSize: '0.7rem',
          fontWeight: 600,
          cursor: 'pointer',
        }}
      >
        <div style={{ position: 'relative' }}>
          <ShoppingBag size={20} />
          {totalItemCount > 0 && (
            <span
              style={{
                position: 'absolute',
                top: '-4px',
                right: '-6px',
                background: 'var(--accent-cyan)',
                color: '#fff',
                fontSize: '0.65rem',
                fontWeight: 800,
                width: '16px',
                height: '16px',
                borderRadius: '50%',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
              }}
            >
              {totalItemCount}
            </span>
          )}
        </div>
        <span>Cart</span>
      </button>

      <Link
        href="/account"
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '0.2rem',
          color: pathname === '/account' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
          textDecoration: 'none',
          fontSize: '0.7rem',
          fontWeight: 600,
        }}
      >
        <User size={20} />
        <span>Account</span>
      </Link>

      <Link
        href="/admin"
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          gap: '0.2rem',
          color: pathname === '/admin' ? 'var(--accent-gold)' : 'var(--text-secondary)',
          textDecoration: 'none',
          fontSize: '0.7rem',
          fontWeight: 700,
        }}
      >
        <Settings size={20} />
        <span>Admin</span>
      </Link>
    </div>
  );
};
