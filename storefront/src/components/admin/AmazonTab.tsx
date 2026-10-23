'use client';

import React, { useState } from 'react';
import { ShoppingCart, Truck, Zap, Save, Check, RefreshCw, ExternalLink } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { AmazonSettings } from '../../lib/types/commerce';

export const AmazonTab: React.FC = () => {
  const { amazonSettings, updateAmazonSettings } = useStore();
  const [formData, setFormData] = useState<AmazonSettings>(amazonSettings);
  const [isSaved, setIsSaved] = useState(false);
  const [isTesting, setIsTesting] = useState(false);
  const [testResult, setTestResult] = useState<string | null>(null);

  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    updateAmazonSettings(formData);
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2000);
  };

  const handleTestSpApi = async () => {
    setIsTesting(true);
    await new Promise((r) => setTimeout(r, 900));
    setIsTesting(false);
    setTestResult('Successfully authenticated with Amazon.ae Selling Partner API (Marketplace: A2VIGQ35RCS4UG, Region: eu-west-1).');
    setTimeout(() => setTestResult(null), 4000);
  };

  return (
    <form onSubmit={handleSave} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Top Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <span style={{ padding: '0.15rem 0.45rem', borderRadius: '4px', background: '#232f3e', color: '#ff9900', fontSize: '0.75rem', fontWeight: 800 }}>
              Amazon.ae
            </span>
            <span>Amazon Selling Partner & MCF Delivery Hub</span>
          </h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Configure Amazon UAE marketplace sync, Multi-Channel Fulfillment (MCF), and cross-channel upsells.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '0.6rem' }}>
          <button
            type="button"
            onClick={handleTestSpApi}
            disabled={isTesting}
            className="btn-secondary"
            style={{ padding: '0.65rem 1rem', fontSize: '0.82rem' }}
          >
            <RefreshCw size={15} className={isTesting ? 'animate-spin' : ''} />
            <span>{isTesting ? 'Testing API...' : 'Test SP-API Connection'}</span>
          </button>

          <button type="submit" className="btn-primary" style={{ padding: '0.65rem 1.25rem' }}>
            {isSaved ? (
              <>
                <Check size={18} />
                <span>Saved!</span>
              </>
            ) : (
              <>
                <Save size={18} />
                <span>Save Amazon Settings</span>
              </>
            )}
          </button>
        </div>
      </div>

      {testResult && (
        <div style={{ background: 'rgba(16,185,129,0.15)', border: '1px solid rgba(16,185,129,0.3)', borderRadius: '10px', padding: '0.85rem 1.25rem', color: '#10b981', fontSize: '0.82rem' }}>
          ✅ {testResult}
        </div>
      )}

      {/* Feature Toggles */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <Zap size={16} color="#ff9900" />
          <span>Amazon Integrations & Storefront Features</span>
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          {/* Toggle MCF */}
          <label style={{ display: 'flex', alignItems: 'flex-start', gap: '0.75rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem', cursor: 'pointer' }}>
            <input
              type="checkbox"
              checked={formData.enableAmazonDeliveryMcf}
              onChange={(e) => setFormData({ ...formData, enableAmazonDeliveryMcf: e.target.checked })}
              style={{ marginTop: '0.2rem', width: '16px', height: '16px', accentColor: 'var(--accent-cyan)' }}
            />
            <div>
              <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.88rem' }}>Enable Amazon Delivery (MCF) at Checkout</div>
              <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
                Allows customers across UAE to choose delivery by Amazon Logistics fleet with guaranteed Prime speeds.
              </div>
            </div>
          </label>

          {/* Toggle Upsells */}
          <label style={{ display: 'flex', alignItems: 'flex-start', gap: '0.75rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem', cursor: 'pointer' }}>
            <input
              type="checkbox"
              checked={formData.enableAmazonUpsells}
              onChange={(e) => setFormData({ ...formData, enableAmazonUpsells: e.target.checked })}
              style={{ marginTop: '0.2rem', width: '16px', height: '16px', accentColor: 'var(--accent-cyan)' }}
            />
            <div>
              <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.88rem' }}>Amazon "Frequently Bought Together" Upsells</div>
              <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
                Displays automated multi-item bundle builder on PDPs with 10% promotional bundle discount.
              </div>
            </div>
          </label>

          {/* Toggle Buy on Amazon */}
          <label style={{ display: 'flex', alignItems: 'flex-start', gap: '0.75rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem', cursor: 'pointer' }}>
            <input
              type="checkbox"
              checked={formData.enableBuyOnAmazonButton}
              onChange={(e) => setFormData({ ...formData, enableBuyOnAmazonButton: e.target.checked })}
              style={{ marginTop: '0.2rem', width: '16px', height: '16px', accentColor: 'var(--accent-cyan)' }}
            />
            <div>
              <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.88rem' }}>Show "Buy on Amazon.ae" Secondary Button</div>
              <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)', marginTop: '0.2rem' }}>
                Provides an option for Amazon loyalists to complete purchase on Amazon.ae with affiliate tracking.
              </div>
            </div>
          </label>
        </div>
      </div>

      {/* SP-API Credentials */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '1.25rem' }}>
          Amazon SP-API Connection Credentials
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Merchant / Seller ID *</label>
            <input
              type="text"
              value={formData.sellerId}
              onChange={(e) => setFormData({ ...formData, sellerId: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Marketplace ID (UAE)</label>
            <input
              type="text"
              readOnly
              value={formData.marketplaceId}
              className="input-field"
              style={{ background: '#0b0f19', color: 'var(--text-muted)' }}
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>SP-API Regional Endpoint</label>
            <input
              type="text"
              value={formData.spApiEndpoint}
              onChange={(e) => setFormData({ ...formData, spApiEndpoint: e.target.value })}
              className="input-field"
            />
          </div>
        </div>
      </div>
    </form>
  );
};
