'use client';

import React, { useState } from 'react';
import { Globe, RefreshCw, Check, Save, ArrowRightLeft } from 'lucide-react';
import { useCurrency } from '../../context/CurrencyContext';

export const CurrencyTab: React.FC = () => {
  const { rates, updateRates, refreshLiveRates, isLoadingRates, currency, setCurrency } = useCurrency();
  const [manualRates, setManualRates] = useState({
    USD: rates.rates.USD,
    INR: rates.rates.INR,
  });
  const [isSaved, setIsSaved] = useState(false);

  const handleSaveManual = (e: React.FormEvent) => {
    e.preventDefault();
    updateRates({
      USD: Number(manualRates.USD),
      INR: Number(manualRates.INR),
    });
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2000);
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff' }}>Multi-Currency & Live FX Exchange Engine</h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Base store currency is **AED (UAE Dirham)** with real-time conversion into **USD ($)** and **INR (₹)**.
          </p>
        </div>

        <button
          onClick={() => refreshLiveRates()}
          disabled={isLoadingRates}
          className="btn-primary"
          style={{ padding: '0.65rem 1.25rem' }}
        >
          <RefreshCw size={16} className={isLoadingRates ? 'animate-spin' : ''} />
          <span>{isLoadingRates ? 'Fetching Live Rates...' : 'Sync Live Market FX'}</span>
        </button>
      </div>

      {/* Exchange Rate Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
        {/* AED Base */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.75rem' }}>
            <span style={{ fontWeight: 700, color: '#fff', fontSize: '1rem' }}>AED (د.إ) - Base UAE Dirham</span>
            <span style={{ padding: '0.15rem 0.5rem', borderRadius: '4px', background: 'rgba(14,165,233,0.15)', color: 'var(--accent-cyan)', fontSize: '0.7rem', fontWeight: 800 }}>
              PRIMARY BASE
            </span>
          </div>
          <div style={{ fontSize: '1.8rem', fontWeight: 800, color: '#fff' }}>1.0000 AED</div>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.35rem' }}>
            All product catalog prices, UAE VAT calculations, and merchant accounting are based in AED.
          </div>
        </div>

        {/* USD Card */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.75rem' }}>
            <span style={{ fontWeight: 700, color: '#fff', fontSize: '1rem' }}>USD ($) - US Dollar</span>
            <span style={{ padding: '0.15rem 0.5rem', borderRadius: '4px', background: 'rgba(16,185,129,0.15)', color: '#10b981', fontSize: '0.7rem', fontWeight: 800 }}>
              FIXED PEG (3.6725)
            </span>
          </div>
          <div style={{ fontSize: '1.8rem', fontWeight: 800, color: '#10b981' }}>{rates.rates.USD.toFixed(6)} USD</div>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.35rem' }}>
            1 USD = 3.6725 AED (UAE Central Bank Official Peg)
          </div>
        </div>

        {/* INR Card */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.75rem' }}>
            <span style={{ fontWeight: 700, color: '#fff', fontSize: '1rem' }}>INR (₹) - Indian Rupee</span>
            <span style={{ padding: '0.15rem 0.5rem', borderRadius: '4px', background: 'rgba(245,158,11,0.15)', color: '#fbbf24', fontSize: '0.7rem', fontWeight: 800 }}>
              LIVE MARKET RATE
            </span>
          </div>
          <div style={{ fontSize: '1.8rem', fontWeight: 800, color: '#fbbf24' }}>{rates.rates.INR.toFixed(4)} INR</div>
          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.35rem' }}>
            1 AED = ~{rates.rates.INR.toFixed(2)} INR (Updated: {new Date(rates.lastUpdated).toLocaleTimeString()})
          </div>
        </div>
      </div>

      {/* Manual Rate Adjuster Form */}
      <form onSubmit={handleSaveManual} style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '1rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <ArrowRightLeft size={16} color="var(--accent-cyan)" />
          <span>Manual Exchange Rate & Spread Override</span>
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '1.25rem', marginBottom: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>USD Conversion Multiplier (per 1 AED)</label>
            <input
              type="number"
              step="0.000001"
              value={manualRates.USD}
              onChange={(e) => setManualRates({ ...manualRates, USD: Number(e.target.value) })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>INR Conversion Multiplier (per 1 AED)</label>
            <input
              type="number"
              step="0.0001"
              value={manualRates.INR}
              onChange={(e) => setManualRates({ ...manualRates, INR: Number(e.target.value) })}
              className="input-field"
            />
          </div>
        </div>

        <button type="submit" className="btn-primary" style={{ padding: '0.6rem 1.25rem' }}>
          {isSaved ? (
            <>
              <Check size={16} />
              <span>Rates Updated!</span>
            </>
          ) : (
            <>
              <Save size={16} />
              <span>Save Custom FX Multipliers</span>
            </>
          )}
        </button>
      </form>
    </div>
  );
};
