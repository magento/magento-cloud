'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import { LogOut, ShieldCheck } from 'lucide-react';
import { authClient } from '@/lib/auth-client';

export const AdminTopBar: React.FC<{ email: string }> = ({ email }) => {
  const router = useRouter();
  const [signingOut, setSigningOut] = useState(false);

  const handleSignOut = async () => {
    setSigningOut(true);
    try {
      await authClient.signOut();
      router.push('/admin/login');
      router.refresh();
    } catch {
      setSigningOut(false);
    }
  };

  return (
    <div
      className="container"
      style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: '1rem',
        padding: '1.25rem 1.5rem 0',
        flexWrap: 'wrap',
      }}
    >
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', color: 'var(--text-secondary)', fontSize: '0.85rem' }}>
        <ShieldCheck size={18} color="var(--accent-cyan)" />
        <span>
          Signed in as <strong style={{ color: '#fff' }}>{email}</strong>
        </span>
      </div>
      <button
        type="button"
        onClick={handleSignOut}
        disabled={signingOut}
        style={{
          display: 'inline-flex',
          alignItems: 'center',
          gap: '0.45rem',
          background: 'rgba(255,255,255,0.06)',
          border: '1px solid var(--border-subtle)',
          color: '#fff',
          padding: '0.55rem 1rem',
          borderRadius: '10px',
          fontSize: '0.85rem',
          cursor: signingOut ? 'default' : 'pointer',
          opacity: signingOut ? 0.6 : 1,
        }}
      >
        <LogOut size={16} />
        {signingOut ? 'Signing out...' : 'Sign out'}
      </button>
    </div>
  );
};
