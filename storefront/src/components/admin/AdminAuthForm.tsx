'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Lock, ShieldCheck } from 'lucide-react';
import { authClient } from '@/lib/auth-client';

export const AdminAuthForm: React.FC<{ isFirstRun: boolean }> = ({ isFirstRun }) => {
  const router = useRouter();
  const [mode, setMode] = useState<'sign-in' | 'sign-up'>(isFirstRun ? 'sign-up' : 'sign-in');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      if (mode === 'sign-up') {
        const { error: err } = await authClient.signUp.email({ email, password, name: name || 'Store Admin' });
        if (err) throw new Error(err.message || 'Could not create the admin account.');
      } else {
        const { error: err } = await authClient.signIn.email({ email, password });
        if (err) throw new Error(err.message || 'Invalid email or password.');
      }
      router.push('/admin');
      router.refresh();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Something went wrong. Please try again.');
      setLoading(false);
    }
  };

  const inputStyle: React.CSSProperties = {
    width: '100%',
    padding: '0.75rem 0.9rem',
    borderRadius: '10px',
    border: '1px solid var(--border-subtle, rgba(255,255,255,0.12))',
    background: 'rgba(255,255,255,0.04)',
    color: '#fff',
    fontSize: '0.95rem',
    marginTop: '0.35rem',
  };

  return (
    <div
      style={{
        minHeight: '70vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '3rem 1.5rem',
      }}
    >
      <div
        style={{
          width: '100%',
          maxWidth: 420,
          background: 'linear-gradient(135deg, #111827, #1e293b)',
          border: '1px solid var(--border-subtle, rgba(255,255,255,0.12))',
          borderRadius: '18px',
          padding: '2rem',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', marginBottom: '0.35rem' }}>
          <ShieldCheck size={22} color="var(--accent-cyan, #22d3ee)" />
          <h1 style={{ fontSize: '1.3rem', fontWeight: 800, color: '#fff', margin: 0 }}>Admin Control Center</h1>
        </div>
        <p style={{ color: 'var(--text-secondary, #94a3b8)', fontSize: '0.88rem', marginBottom: '1.5rem' }}>
          {mode === 'sign-up'
            ? isFirstRun
              ? 'Create the store administrator account to manage your catalog and orders.'
              : 'Create an administrator account.'
            : 'Sign in to manage your products, orders, and store settings.'}
        </p>

        <form onSubmit={handleSubmit}>
          {mode === 'sign-up' && (
            <label style={{ display: 'block', marginBottom: '1rem', color: 'var(--text-secondary, #94a3b8)', fontSize: '0.82rem' }}>
              Full name
              <input style={inputStyle} value={name} onChange={(e) => setName(e.target.value)} placeholder="Store Admin" autoComplete="name" />
            </label>
          )}
          <label style={{ display: 'block', marginBottom: '1rem', color: 'var(--text-secondary, #94a3b8)', fontSize: '0.82rem' }}>
            Email
            <input
              style={inputStyle}
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="admin@store.ae"
              autoComplete="email"
              required
            />
          </label>
          <label style={{ display: 'block', marginBottom: '1rem', color: 'var(--text-secondary, #94a3b8)', fontSize: '0.82rem' }}>
            Password
            <input
              style={inputStyle}
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="At least 8 characters"
              autoComplete={mode === 'sign-up' ? 'new-password' : 'current-password'}
              minLength={8}
              required
            />
          </label>

          {error && (
            <div style={{ color: '#fca5a5', fontSize: '0.82rem', marginBottom: '1rem' }} role="alert">
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            style={{
              width: '100%',
              display: 'inline-flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              background: 'var(--accent-cyan, #0284c7)',
              color: '#000',
              fontWeight: 700,
              border: 'none',
              padding: '0.8rem',
              borderRadius: '10px',
              fontSize: '0.95rem',
              cursor: loading ? 'default' : 'pointer',
              opacity: loading ? 0.7 : 1,
            }}
          >
            <Lock size={16} />
            {loading ? 'Please wait...' : mode === 'sign-up' ? 'Create admin account' : 'Sign in'}
          </button>
        </form>

        {!isFirstRun && (
          <p style={{ textAlign: 'center', marginTop: '1.25rem', color: 'var(--text-secondary, #94a3b8)', fontSize: '0.82rem' }}>
            {mode === 'sign-in' ? (
              <button type="button" onClick={() => setMode('sign-up')} style={linkBtn}>
                Need to create an admin account?
              </button>
            ) : (
              <button type="button" onClick={() => setMode('sign-in')} style={linkBtn}>
                Already have an account? Sign in
              </button>
            )}
          </p>
        )}

        <p style={{ textAlign: 'center', marginTop: '1rem' }}>
          <Link href="/" style={{ color: 'var(--text-secondary, #94a3b8)', fontSize: '0.8rem' }}>
            Back to storefront
          </Link>
        </p>
      </div>
    </div>
  );
};

const linkBtn: React.CSSProperties = {
  background: 'none',
  border: 'none',
  color: 'var(--accent-cyan, #22d3ee)',
  cursor: 'pointer',
  fontSize: '0.82rem',
  textDecoration: 'underline',
};
