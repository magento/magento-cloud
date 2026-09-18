'use client';

import React, { useState } from 'react';
import { Database, RefreshCw, Check, Save, Layers, ArrowRight, ShieldCheck, Activity } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { testOdooConnection, syncCatalogWithOdoo } from '../../lib/integrations/odooConnector';
import { OdooSettings } from '../../lib/types/commerce';

export const OdooTab: React.FC = () => {
  const { odooSettings, updateOdooSettings, products, odooLogs, addOdooLog } = useStore();
  const [formData, setFormData] = useState<OdooSettings>(odooSettings);
  const [isSaved, setIsSaved] = useState(false);
  const [isTesting, setIsTesting] = useState(false);
  const [isSyncing, setIsSyncing] = useState(false);
  const [testResult, setTestResult] = useState<{ success: boolean; message: string; version?: string } | null>(null);

  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    updateOdooSettings(formData);
    setIsSaved(true);
    setTimeout(() => setIsSaved(false), 2000);
  };

  const handleTestConnection = async () => {
    setIsTesting(true);
    const res = await testOdooConnection(formData);
    setTestResult(res);
    setIsTesting(false);
  };

  const handleManualCatalogSync = async () => {
    setIsSyncing(true);
    const res = await syncCatalogWithOdoo(products, formData);
    setIsSyncing(false);
    res.logs.forEach((log) => addOdooLog(log));
  };

  return (
    <form onSubmit={handleSave} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <Database size={20} color="var(--accent-cyan)" />
            <span>Odoo 18 ERP Connector & Bidirectional Sync</span>
          </h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Synchronize products, live warehouse stock, automated Sales Orders (`sale.order`), and FTA Tax Invoices (`account.move`).
          </p>
        </div>

        <div style={{ display: 'flex', gap: '0.6rem', flexWrap: 'wrap' }}>
          <button
            type="button"
            onClick={handleTestConnection}
            disabled={isTesting}
            className="btn-secondary"
            style={{ padding: '0.65rem 1rem', fontSize: '0.82rem' }}
          >
            <RefreshCw size={15} className={isTesting ? 'animate-spin' : ''} />
            <span>{isTesting ? 'Testing...' : 'Test Connection'}</span>
          </button>

          <button
            type="button"
            onClick={handleManualCatalogSync}
            disabled={isSyncing}
            className="btn-secondary"
            style={{ padding: '0.65rem 1rem', fontSize: '0.82rem' }}
          >
            <Layers size={15} className={isSyncing ? 'animate-spin' : ''} />
            <span>{isSyncing ? 'Syncing Catalog...' : 'Sync Catalog to Odoo'}</span>
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
                <span>Save Odoo Config</span>
              </>
            )}
          </button>
        </div>
      </div>

      {testResult && (
        <div style={{ background: testResult.success ? 'rgba(16,185,129,0.15)' : 'rgba(244,63,94,0.15)', border: '1px solid ' + (testResult.success ? 'rgba(16,185,129,0.3)' : 'rgba(244,63,94,0.3)'), borderRadius: '10px', padding: '0.85rem 1.25rem', color: testResult.success ? '#10b981' : '#f43f5e', fontSize: '0.82rem' }}>
          {testResult.success ? `✅ ${testResult.message} (${testResult.version})` : `❌ ${testResult.message}`}
        </div>
      )}

      {/* Connection Parameters */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '1.25rem' }}>
          Odoo Instance Connection Settings
        </h4>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Odoo Host Server URL *</label>
            <input
              type="url"
              required
              value={formData.url}
              onChange={(e) => setFormData({ ...formData, url: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>Database Name *</label>
            <input
              type="text"
              required
              value={formData.dbName}
              onChange={(e) => setFormData({ ...formData, dbName: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>User Email / Login *</label>
            <input
              type="email"
              required
              value={formData.username}
              onChange={(e) => setFormData({ ...formData, username: e.target.value })}
              className="input-field"
            />
          </div>

          <div>
            <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.35rem' }}>API Key / Password *</label>
            <input
              type="password"
              value={formData.apiKeyMasked}
              onChange={(e) => setFormData({ ...formData, apiKeyMasked: e.target.value })}
              className="input-field"
            />
          </div>
        </div>
      </div>

      {/* Sync Logs */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.25rem' }}>
        <h4 style={{ fontSize: '0.95rem', color: '#fff', marginBottom: '0.85rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <Activity size={16} color="var(--accent-cyan)" />
          <span>Real-time Odoo Sync Activity Logs</span>
        </h4>

        <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '0.85rem', maxHeight: '200px', overflowY: 'auto', display: 'flex', flexDirection: 'column', gap: '0.4rem', fontFamily: 'monospace', fontSize: '0.75rem' }}>
          {odooLogs.length === 0 ? (
            <div style={{ color: 'var(--text-muted)', textAlign: 'center', padding: '1rem' }}>No recent sync events. Orders and catalog sync in real-time.</div>
          ) : (
            odooLogs.map((log) => (
              <div key={log.id} style={{ display: 'flex', gap: '0.5rem', alignItems: 'baseline' }}>
                <span style={{ color: '#64748b' }}>[{new Date(log.timestamp).toLocaleTimeString()}]</span>
                <span style={{ color: log.status === 'success' ? '#10b981' : '#f43f5e', fontWeight: 700 }}>
                  [{log.entity.toUpperCase()}]
                </span>
                <span style={{ color: '#cbd5e1' }}>{log.message}</span>
              </div>
            ))
          )}
        </div>
      </div>
    </form>
  );
};
