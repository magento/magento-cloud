'use client';

import React, { useState } from 'react';
import { CreditCard, Building, Plus, Trash2, Save, Check, ShieldCheck } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { BankAccountDetails, PaymentGatewaySettings } from '../../lib/types/commerce';

export const PaymentsTab: React.FC = () => {
  const { paymentSettings, updatePaymentSettings } = useStore();
  const [formData, setFormData] = useState<PaymentGatewaySettings>(paymentSettings);
  const [isSaved, setIsSaved] = useState(false);

  const [newBank, setNewBank] = useState<BankAccountDetails>({
    bankName: '',
    accountTitle: 'Magento Commerce UAE LLC',
    accountNumber: '',
    iban: 'AE',
    swiftBic: '',
    branchName: '',
  });
  const [showAddBank, setShowAddBank] = useState(false);

  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    updatePaymentSettings(formData);
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2000);
  };

  const handleAddBankAccount = () => {
    if (!newBank.bankName || !newBank.iban) return;
    setFormData((prev) => ({
      ...prev,
      bankTransfer: {
        ...prev.bankTransfer,
        accounts: [...prev.bankTransfer.accounts, newBank],
      },
    }));
    setNewBank({
      bankName: '',
      accountTitle: 'Magento Commerce UAE LLC',
      accountNumber: '',
      iban: 'AE',
      swiftBic: '',
      branchName: '',
    });
    setShowAddBank(false);
  };

  const handleRemoveBank = (index: number) => {
    setFormData((prev) => ({
      ...prev,
      bankTransfer: {
        ...prev.bankTransfer,
        accounts: prev.bankTransfer.accounts.filter((_, idx) => idx !== index),
      },
    }));
  };

  return (
    <form onSubmit={handleSave} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <CreditCard size={20} color="var(--accent-cyan)" />
            <span>UAE Payment Channels & Bank Account Routing</span>
          </h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Configure Stripe UAE, Tabby, Tamara BNPL, Direct UAE Bank IBAN accounts, and Cash on Delivery.
          </p>
        </div>

        <button type="submit" className="btn-primary" style={{ padding: '0.65rem 1.25rem' }}>
          {isSaved ? (
            <>
              <Check size={18} />
              <span>Saved!</span>
            </>
          ) : (
            <>
              <Save size={18} />
              <span>Save Payment Settings</span>
            </>
          )}
        </button>
      </div>

      {/* Stripe UAE */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
          <h4 style={{ fontSize: '1rem', color: 'var(--accent-cyan)', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <CreditCard size={18} /> Stripe UAE & Apple Pay Gateway
          </h4>
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.82rem', color: '#fff', cursor: 'pointer' }}>
            <input
              type="checkbox"
              checked={formData.stripe.enabled}
              onChange={(e) => setFormData({ ...formData, stripe: { ...formData.stripe, enabled: e.target.checked } })}
              style={{ width: '16px', height: '16px', accentColor: 'var(--accent-cyan)' }}
            />
            <span>Enabled</span>
          </label>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Stripe Publishable Key</label>
            <input
              type="text"
              value={formData.stripe.publishableKey}
              onChange={(e) => setFormData({ ...formData, stripe: { ...formData.stripe, publishableKey: e.target.value } })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Stripe Secret Key</label>
            <input
              type="password"
              value={formData.stripe.secretKeyMasked}
              onChange={(e) => setFormData({ ...formData, stripe: { ...formData.stripe, secretKeyMasked: e.target.value } })}
              className="input-field"
            />
          </div>
        </div>
      </div>

      {/* BNPL: Tabby & Tamara */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '1.5rem' }}>
        {/* Tabby */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <span style={{ padding: '0.15rem 0.45rem', borderRadius: '4px', background: '#3bfc87', color: '#0b1a10', fontSize: '0.75rem', fontWeight: 800 }}>
                tabby
              </span>
              <span style={{ fontWeight: 700, color: '#fff', fontSize: '0.95rem' }}>Tabby (Pay in 4)</span>
            </div>
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.82rem', color: '#fff', cursor: 'pointer' }}>
              <input
                type="checkbox"
                checked={formData.tabby.enabled}
                onChange={(e) => setFormData({ ...formData, tabby: { ...formData.tabby, enabled: e.target.checked } })}
                style={{ width: '16px', height: '16px', accentColor: '#3bfc87' }}
              />
              <span>Enabled</span>
            </label>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.85rem' }}>
            <div>
              <label style={{ display: 'block', fontSize: '0.75rem', color: 'var(--text-secondary)', marginBottom: '0.25rem' }}>Tabby Public Key</label>
              <input
                type="text"
                value={formData.tabby.publicKey}
                onChange={(e) => setFormData({ ...formData, tabby: { ...formData.tabby, publicKey: e.target.value } })}
                className="input-field"
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '0.75rem', color: 'var(--text-secondary)', marginBottom: '0.25rem' }}>Tabby Merchant Code</label>
              <input
                type="text"
                value={formData.tabby.merchantCode}
                onChange={(e) => setFormData({ ...formData, tabby: { ...formData.tabby, merchantCode: e.target.value } })}
                className="input-field"
              />
            </div>
          </div>
        </div>

        {/* Tamara */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <span style={{ padding: '0.15rem 0.45rem', borderRadius: '4px', background: '#fce0c6', color: '#2c1006', fontSize: '0.75rem', fontWeight: 800 }}>
                tamara
              </span>
              <span style={{ fontWeight: 700, color: '#fff', fontSize: '0.95rem' }}>Tamara (Pay in 3)</span>
            </div>
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.82rem', color: '#fff', cursor: 'pointer' }}>
              <input
                type="checkbox"
                checked={formData.tamara.enabled}
                onChange={(e) => setFormData({ ...formData, tamara: { ...formData.tamara, enabled: e.target.checked } })}
                style={{ width: '16px', height: '16px', accentColor: '#f69371' }}
              />
              <span>Enabled</span>
            </label>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.85rem' }}>
            <div>
              <label style={{ display: 'block', fontSize: '0.75rem', color: 'var(--text-secondary)', marginBottom: '0.25rem' }}>Tamara API Token</label>
              <input
                type="password"
                value={formData.tamara.apiTokenMasked}
                onChange={(e) => setFormData({ ...formData, tamara: { ...formData.tamara, apiTokenMasked: e.target.value } })}
                className="input-field"
              />
            </div>
          </div>
        </div>
      </div>

      {/* Direct UAE Corporate Bank Accounts */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem', flexWrap: 'wrap', gap: '0.75rem' }}>
          <div>
            <h4 style={{ fontSize: '1rem', color: 'var(--accent-gold)', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <Building size={18} /> UAE Corporate Bank Accounts (IBAN / Central Bank FTS)
            </h4>
            <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
              Configure Emirates NBD, ADCB, Wio, or FAB accounts displayed to customers on checkout.
            </div>
          </div>

          <button
            type="button"
            onClick={() => setShowAddBank(true)}
            className="btn-secondary"
            style={{ padding: '0.45rem 0.85rem', fontSize: '0.78rem' }}
          >
            <Plus size={14} />
            <span>Add Bank Account</span>
          </button>
        </div>

        {/* Bank List */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.85rem' }}>
          {formData.bankTransfer.accounts.map((acc, idx) => (
            <div key={acc.iban} style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem', display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
              <div>
                <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>{acc.bankName}</div>
                <div style={{ fontSize: '0.8rem', color: '#cbd5e1', marginTop: '0.2rem' }}>Account Title: <strong>{acc.accountTitle}</strong></div>
                <div style={{ fontSize: '0.8rem', color: 'var(--accent-cyan)', fontFamily: 'monospace', marginTop: '0.2rem' }}>IBAN: {acc.iban}</div>
                <div style={{ fontSize: '0.72rem', color: 'var(--text-muted)', marginTop: '0.2rem' }}>SWIFT: {acc.swiftBic} • Branch: {acc.branchName || 'Dubai'}</div>
              </div>

              <button
                type="button"
                onClick={() => handleRemoveBank(idx)}
                style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer', padding: '0.3rem' }}
                title="Remove account"
              >
                <Trash2 size={16} />
              </button>
            </div>
          ))}
        </div>

        {/* Add Bank Modal / Form */}
        {showAddBank && (
          <div style={{ marginTop: '1rem', background: '#1e293b', border: '1px solid var(--border-hover)', borderRadius: '12px', padding: '1.25rem' }}>
            <h5 style={{ fontSize: '0.88rem', color: '#fff', marginBottom: '0.75rem' }}>Add UAE Corporate Bank Account</h5>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '0.85rem', marginBottom: '1rem' }}>
              <div>
                <label style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>Bank Name *</label>
                <input
                  type="text"
                  placeholder="e.g. First Abu Dhabi Bank (FAB)"
                  value={newBank.bankName}
                  onChange={(e) => setNewBank({ ...newBank, bankName: e.target.value })}
                  className="input-field"
                  style={{ fontSize: '0.8rem' }}
                />
              </div>
              <div>
                <label style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>Account Title *</label>
                <input
                  type="text"
                  value={newBank.accountTitle}
                  onChange={(e) => setNewBank({ ...newBank, accountTitle: e.target.value })}
                  className="input-field"
                  style={{ fontSize: '0.8rem' }}
                />
              </div>
              <div>
                <label style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>UAE IBAN Number *</label>
                <input
                  type="text"
                  placeholder="AE000000000000000000000"
                  value={newBank.iban}
                  onChange={(e) => setNewBank({ ...newBank, iban: e.target.value })}
                  className="input-field"
                  style={{ fontSize: '0.8rem' }}
                />
              </div>
              <div>
                <label style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>SWIFT / BIC Code</label>
                <input
                  type="text"
                  placeholder="FABBAEADXXX"
                  value={newBank.swiftBic}
                  onChange={(e) => setNewBank({ ...newBank, swiftBic: e.target.value })}
                  className="input-field"
                  style={{ fontSize: '0.8rem' }}
                />
              </div>
            </div>

            <div style={{ display: 'flex', gap: '0.6rem', justifyContent: 'flex-end' }}>
              <button type="button" onClick={() => setShowAddBank(false)} className="btn-secondary" style={{ padding: '0.4rem 0.85rem', fontSize: '0.78rem' }}>
                Cancel
              </button>
              <button type="button" onClick={handleAddBankAccount} className="btn-primary" style={{ padding: '0.4rem 0.85rem', fontSize: '0.78rem' }}>
                Save Bank Account
              </button>
            </div>
          </div>
        )}
      </div>
    </form>
  );
};
