'use client';

import React, { useState } from 'react';
import { Save, Check, Image as ImageIcon, Palette, Building, Phone, Mail, Globe, Shield } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { StoreIdentity } from '../../lib/types/commerce';

export const StoreSettingsTab: React.FC = () => {
  const { storeIdentity, updateStoreIdentity } = useStore();
  const [formData, setFormData] = useState<StoreIdentity>(storeIdentity);
  const [isSaved, setIsSaved] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateStoreIdentity(formData);
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2500);
  };

  return (
    <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '2rem' }}>
      {/* Save bar */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff' }}>Store Branding & Identity</h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Update store name, logo, announcement banner, contact details, and UAE TRN registration.
          </p>
        </div>

        <button type="submit" className="btn-primary" style={{ padding: '0.65rem 1.5rem' }}>
          {isSaved ? (
            <>
              <Check size={18} />
              <span>Saved Successfully!</span>
            </>
          ) : (
            <>
              <Save size={18} />
              <span>Save Changes</span>
            </>
          )}
        </button>
      </div>

      {/* Basic Identity */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '1rem', color: 'var(--accent-cyan)', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <Building size={18} /> General Store Information
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Store Name *</label>
            <input
              type="text"
              required
              value={formData.storeName}
              onChange={(e) => setFormData({ ...formData, storeName: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Store Tagline / Slogan</label>
            <input
              type="text"
              value={formData.tagline}
              onChange={(e) => setFormData({ ...formData, tagline: e.target.value })}
              className="input-field"
            />
          </div>

          <div style={{ gridColumn: '1 / -1' }}>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Top Announcement Bar Text</label>
            <input
              type="text"
              value={formData.announcementText}
              onChange={(e) => setFormData({ ...formData, announcementText: e.target.value })}
              className="input-field"
            />
          </div>
        </div>
      </div>

      {/* Homepage Hero Section Write-Up & CMS */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
          <h4 style={{ fontSize: '1rem', color: '#38bdf8', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <Palette size={18} /> Homepage Hero Section Write-Up & CMS
          </h4>
          <span style={{ fontSize: '0.72rem', color: '#10b981', background: 'rgba(16,185,129,0.15)', padding: '0.2rem 0.6rem', borderRadius: '6px', fontWeight: 600 }}>
            Live on Storefront
          </span>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Top Announcement Pill / Badge
            </label>
            <input
              type="text"
              placeholder="e.g. Dubai Central Warehouse • Same-Day Dispatch"
              value={formData.heroBadgeText || ''}
              onChange={(e) => setFormData({ ...formData, heroBadgeText: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Main Heading Title (Line 1)
            </label>
            <input
              type="text"
              placeholder="e.g. Next-Gen Tech & Luxury Essentials"
              value={formData.heroTitle || ''}
              onChange={(e) => setFormData({ ...formData, heroTitle: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Gradient Highlight Text (Line 2)
            </label>
            <input
              type="text"
              placeholder="e.g. Delivered Across UAE"
              value={formData.heroHighlight || ''}
              onChange={(e) => setFormData({ ...formData, heroHighlight: e.target.value })}
              className="input-field"
            />
          </div>

          <div style={{ gridColumn: '1 / -1' }}>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Hero Subtitle / Write-Up Description
            </label>
            <textarea
              rows={3}
              placeholder="Detailed promotional description or value proposition..."
              value={formData.heroDescription || ''}
              onChange={(e) => setFormData({ ...formData, heroDescription: e.target.value })}
              className="input-field"
              style={{ resize: 'vertical' }}
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Primary Button Label
            </label>
            <input
              type="text"
              placeholder="e.g. Explore Catalog"
              value={formData.heroCtaPrimaryText || ''}
              onChange={(e) => setFormData({ ...formData, heroCtaPrimaryText: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Primary Button Link URL
            </label>
            <input
              type="text"
              placeholder="e.g. /shop"
              value={formData.heroCtaPrimaryLink || ''}
              onChange={(e) => setFormData({ ...formData, heroCtaPrimaryLink: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Secondary Button Label
            </label>
            <input
              type="text"
              placeholder="e.g. Admin Control Center"
              value={formData.heroCtaSecondaryText || ''}
              onChange={(e) => setFormData({ ...formData, heroCtaSecondaryText: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>
              Secondary Button Link URL
            </label>
            <input
              type="text"
              placeholder="e.g. /admin"
              value={formData.heroCtaSecondaryLink || ''}
              onChange={(e) => setFormData({ ...formData, heroCtaSecondaryLink: e.target.value })}
              className="input-field"
            />
          </div>
        </div>
      </div>

      {/* Logos & Branding */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '1rem', color: 'var(--accent-gold)', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <ImageIcon size={18} /> Visual Assets & Theme Colors
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Logo Image URL</label>
            <input
              type="url"
              value={formData.logoUrl}
              onChange={(e) => setFormData({ ...formData, logoUrl: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Favicon URL</label>
            <input
              type="text"
              value={formData.faviconUrl}
              onChange={(e) => setFormData({ ...formData, faviconUrl: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Primary Accent Color</label>
            <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
              <input
                type="color"
                value={formData.primaryColor}
                onChange={(e) => setFormData({ ...formData, primaryColor: e.target.value })}
                style={{ width: '40px', height: '38px', borderRadius: '8px', border: 'none', cursor: 'pointer', background: 'none' }}
              />
              <input
                type="text"
                value={formData.primaryColor}
                onChange={(e) => setFormData({ ...formData, primaryColor: e.target.value })}
                className="input-field"
                style={{ flex: 1 }}
              />
            </div>
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Free UAE Shipping Threshold (AED)</label>
            <input
              type="number"
              value={formData.freeShippingThresholdAed}
              onChange={(e) => setFormData({ ...formData, freeShippingThresholdAed: Number(e.target.value) })}
              className="input-field"
            />
          </div>
        </div>
      </div>

      {/* UAE License & Legal */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '1rem', color: '#10b981', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <Shield size={18} /> UAE Trade License & Federal Tax Authority (FTA) Settings
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Registered Legal Entity Name</label>
            <input
              type="text"
              value={formData.companyLegalName}
              onChange={(e) => setFormData({ ...formData, companyLegalName: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>UAE Trade License Number</label>
            <input
              type="text"
              value={formData.tradeLicenseNumber}
              onChange={(e) => setFormData({ ...formData, tradeLicenseNumber: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>UAE 15-Digit TRN (Tax Registration Number) *</label>
            <input
              type="text"
              required
              value={formData.uaeTrn}
              onChange={(e) => setFormData({ ...formData, uaeTrn: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>WhatsApp VIP Concierge Number (+971)</label>
            <input
              type="text"
              value={formData.whatsappNumber}
              onChange={(e) => setFormData({ ...formData, whatsappNumber: e.target.value })}
              className="input-field"
            />
          </div>

          <div style={{ gridColumn: '1 / -1' }}>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Registered Dubai Physical Address</label>
            <input
              type="text"
              value={formData.address}
              onChange={(e) => setFormData({ ...formData, address: e.target.value })}
              className="input-field"
            />
          </div>
        </div>
      </div>
    </form>
  );
};
