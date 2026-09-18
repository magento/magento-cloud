'use client';

import React from 'react';
import Link from 'next/link';
import {
  ShieldCheck,
  Truck,
  RotateCcw,
  Headphones,
  MapPin,
  Mail,
  Phone,
  MessageCircle,
  ExternalLink,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';

export const Footer: React.FC = () => {
  const { storeIdentity } = useStore();

  return (
    <footer style={{ background: '#070a12', borderTop: '1px solid var(--border-subtle)', color: 'var(--text-secondary)', marginTop: '4rem' }}>
      {/* Trust & Guarantee Badges */}
      <div style={{ borderBottom: '1px solid rgba(255,255,255,0.06)', padding: '2.5rem 0' }}>
        <div className="container" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '2rem' }}>
          <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
            <div style={{ width: '44px', height: '44px', borderRadius: '10px', background: 'rgba(14,165,233,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <Truck size={22} color="var(--accent-cyan)" />
            </div>
            <div>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem' }}>Amazon Logistics & UAE Express</div>
              <div style={{ fontSize: '0.82rem', marginTop: '0.2rem' }}>Same-Day Dubai delivery and 1-2 days across all 7 UAE Emirates.</div>
            </div>
          </div>

          <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
            <div style={{ width: '44px', height: '44px', borderRadius: '10px', background: 'rgba(245,158,11,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <ShieldCheck size={22} color="var(--accent-gold)" />
            </div>
            <div>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem' }}>100% Genuine & UAE TRA Approved</div>
              <div style={{ fontSize: '0.82rem', marginTop: '0.2rem' }}>Official manufacturer warranties and FTA-compliant Tax Invoices.</div>
            </div>
          </div>

          <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
            <div style={{ width: '44px', height: '44px', borderRadius: '10px', background: 'rgba(16,185,129,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <RotateCcw size={22} color="#10b981" />
            </div>
            <div>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem' }}>Tabby & Tamara Split in 4</div>
              <div style={{ fontSize: '0.82rem', marginTop: '0.2rem' }}>Zero interest, zero hidden fees. Shariah compliant installment plans.</div>
            </div>
          </div>

          <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
            <div style={{ width: '44px', height: '44px', borderRadius: '10px', background: 'rgba(244,63,94,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <Headphones size={22} color="#f43f5e" />
            </div>
            <div>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem' }}>Dubai VIP Support & WhatsApp</div>
              <div style={{ fontSize: '0.82rem', marginTop: '0.2rem' }}>Direct concierge assistance for order questions and corporate bulk orders.</div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Footer Links */}
      <div className="container" style={{ padding: '3.5rem 1.5rem 2.5rem', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '2.5rem' }}>
        {/* Company Identity */}
        <div>
          <div style={{ fontSize: '1.2rem', fontWeight: 800, color: '#fff', marginBottom: '0.75rem' }}>
            {storeIdentity.storeName}
          </div>
          <p style={{ fontSize: '0.85rem', lineHeight: '1.6', marginBottom: '1.25rem' }}>
            {storeIdentity.tagline}
          </p>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem', fontSize: '0.82rem' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <MapPin size={15} color="var(--accent-cyan)" />
              <span>{storeIdentity.address}</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <Phone size={15} color="var(--accent-cyan)" />
              <span>{storeIdentity.supportPhone}</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <Mail size={15} color="var(--accent-cyan)" />
              <span>{storeIdentity.supportEmail}</span>
            </div>
            <a
              href={`https://wa.me/${storeIdentity.whatsappNumber.replace(/[^0-9]/g, '')}`}
              target="_blank"
              rel="noreferrer"
              style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', color: '#10b981', fontWeight: 600, textDecoration: 'none', marginTop: '0.25rem' }}
            >
              <MessageCircle size={16} />
              <span>WhatsApp UAE Concierge</span>
              <ExternalLink size={12} />
            </a>
          </div>
        </div>

        {/* Quick Links */}
        <div>
          <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem', marginBottom: '1rem' }}>Shopping & Categories</div>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.6rem', fontSize: '0.85rem' }}>
            <li><Link href="/shop?cat=Smartphones" style={{ color: 'inherit', textDecoration: 'none' }}>Smartphones & 5G Devices</Link></li>
            <li><Link href="/shop?cat=Laptops" style={{ color: 'inherit', textDecoration: 'none' }}>MacBook Pro & Creator Laptops</Link></li>
            <li><Link href="/shop?cat=Audio" style={{ color: 'inherit', textDecoration: 'none' }}>Noise-Canceling Audio</Link></li>
            <li><Link href="/shop?cat=Wearables" style={{ color: 'inherit', textDecoration: 'none' }}>Smartwatches & Wearables</Link></li>
            <li><Link href="/shop?cat=Beauty%20%26%20Lifestyle" style={{ color: 'inherit', textDecoration: 'none' }}>Luxury Beauty & Dyson</Link></li>
          </ul>
        </div>

        {/* Store & Admin Hub */}
        <div>
          <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem', marginBottom: '1rem' }}>Platform & Integrations</div>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.6rem', fontSize: '0.85rem' }}>
            <li><Link href="/admin?tab=store" style={{ color: 'inherit', textDecoration: 'none' }}>Store Customizer & Branding</Link></li>
            <li><Link href="/admin?tab=products" style={{ color: 'inherit', textDecoration: 'none' }}>Product & Variant Manager</Link></li>
            <li><Link href="/admin?tab=inventory" style={{ color: 'inherit', textDecoration: 'none' }}>Online Warehouse Inventory</Link></li>
            <li><Link href="/admin?tab=odoo" style={{ color: 'inherit', textDecoration: 'none' }}>Odoo 18 ERP Connector</Link></li>
            <li><Link href="/admin?tab=amazon" style={{ color: 'inherit', textDecoration: 'none' }}>Amazon.ae & MCF Logistics</Link></li>
            <li><Link href="/admin?tab=payments" style={{ color: 'inherit', textDecoration: 'none' }}>UAE Payments & IBAN Routing</Link></li>
          </ul>
        </div>

        {/* UAE Compliance & Legal */}
        <div>
          <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.95rem', marginBottom: '1rem' }}>UAE License & Compliance</div>
          <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem', fontSize: '0.8rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.4rem' }}>
              <span style={{ color: 'var(--text-muted)' }}>Entity:</span>
              <span style={{ color: '#fff', fontWeight: 600 }}>{storeIdentity.companyLegalName}</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.4rem' }}>
              <span style={{ color: 'var(--text-muted)' }}>Trade License:</span>
              <span style={{ color: '#fbbf24', fontWeight: 600 }}>{storeIdentity.tradeLicenseNumber}</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
              <span style={{ color: 'var(--text-muted)' }}>UAE TRN (Tax):</span>
              <span style={{ color: 'var(--accent-cyan)', fontWeight: 600 }}>{storeIdentity.uaeTrn}</span>
            </div>
          </div>

          <div style={{ marginTop: '1rem', fontSize: '0.75rem', color: 'var(--text-muted)' }}>
            All transactions are protected by 256-bit TLS encryption. Invoices include standard 5% UAE FTA VAT.
          </div>
        </div>
      </div>

      {/* Payment Badges & Copyright */}
      <div style={{ background: '#04060a', padding: '1.5rem 0', borderTop: '1px solid rgba(255,255,255,0.05)' }}>
        <div className="container" style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'space-between', alignItems: 'center', gap: '1.25rem' }}>
          <div style={{ fontSize: '0.8rem' }}>
            © 2026 {storeIdentity.storeName}. All Rights Reserved. Built with Headless Magento 2.4 & Next.js on Vercel.
          </div>

          {/* Payment Method Badges */}
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', flexWrap: 'wrap' }}>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#1e293b', color: '#fff', fontSize: '0.72rem', fontWeight: 700 }}>
              VISA
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#1e293b', color: '#ff5f00', fontSize: '0.72rem', fontWeight: 700 }}>
              Mastercard
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#1e293b', color: '#0070ba', fontSize: '0.72rem', fontWeight: 700 }}>
              Apple Pay
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontSize: '0.72rem', fontWeight: 800 }}>
              tabby
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#fce0c6', color: '#2c1006', fontSize: '0.72rem', fontWeight: 800 }}>
              tamara
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#232f3e', color: '#ff9900', fontSize: '0.72rem', fontWeight: 700 }}>
              Amazon MCF
            </span>
            <span style={{ padding: '0.2rem 0.5rem', borderRadius: '4px', background: '#1e293b', color: '#10b981', fontSize: '0.72rem', fontWeight: 700 }}>
              UAE IBAN FTS
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
};
