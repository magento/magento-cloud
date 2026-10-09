'use client';

import React, { useState } from 'react';
import {
  Plus,
  Search,
  Upload,
  Download,
  Trash2,
  Edit2,
  X,
  Check,
  Sparkles,
  Zap,
  Tag,
  Layers,
  FileSpreadsheet,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { Product, ProductVariant } from '../../lib/types/commerce';
import { CATEGORIES, BRANDS } from '../../lib/magento/mockData';

export const ProductManagerTab: React.FC = () => {
  const { products, addProduct, updateProduct, deleteProduct, bulkImportProducts, exportProductsCsv } = useStore();
  const { formatPrice } = useCurrency();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCat, setSelectedCat] = useState('All');
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isBulkModalOpen, setIsBulkModalOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState<Product | null>(null);

  // Bulk import state
  const [csvText, setCsvText] = useState('');
  const [bulkFeedback, setBulkFeedback] = useState<{ successCount: number; errors: string[] } | null>(null);

  // Single Product Form State
  const [formData, setFormData] = useState<Omit<Product, 'id' | 'createdAt' | 'updatedAt'>>({
    sku: `SKU-${Math.floor(100000 + Math.random() * 900000)}`,
    barcode: '',
    title: '',
    slug: '',
    description: '',
    shortDescription: '',
    category: 'Smartphones',
    brand: 'Apple',
    basePriceAed: 1999,
    compareAtPriceAed: 2299,
    costPriceAed: 1600,
    featuredImage: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800',
    galleryImages: ['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800'],
    stock: 25,
    lowStockThreshold: 5,
    hasVariants: false,
    variants: [],
    tags: ['New Arrival', 'UAE Stock'],
    rating: 5.0,
    reviewCount: 0,
    amazonAsin: '',
    isFeatured: false,
    isTrending: false,
    isFlashDeal: false,
  });

  // Variant helper
  const [tempVariantName, setTempVariantName] = useState('');
  const [tempVariantPriceAdj, setTempVariantPriceAdj] = useState(0);
  const [tempVariantStock, setTempVariantStock] = useState(10);

  const filteredProducts = products.filter((p) => {
    const matchesSearch = p.title.toLowerCase().includes(searchTerm.toLowerCase()) || p.sku.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCat = selectedCat === 'All' || p.category === selectedCat;
    return matchesSearch && matchesCat;
  });

  const handleTitleChange = (val: string) => {
    setFormData((prev) => ({
      ...prev,
      title: val,
      slug: val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''),
    }));
  };

  const handleAddVariant = () => {
    if (!tempVariantName) return;
    const newVariant: ProductVariant = {
      id: 'var-' + Date.now(),
      sku: `${formData.sku}-${tempVariantName.substring(0, 3).toUpperCase()}`,
      name: tempVariantName,
      options: { Variant: tempVariantName },
      priceAdjustmentAed: Number(tempVariantPriceAdj),
      stock: Number(tempVariantStock),
    };
    setFormData((prev) => ({
      ...prev,
      hasVariants: true,
      variants: [...prev.variants, newVariant],
    }));
    setTempVariantName('');
    setTempVariantPriceAdj(0);
    setTempVariantStock(10);
  };

  const handleRemoveVariant = (id: string) => {
    setFormData((prev) => {
      const remaining = prev.variants.filter((v) => v.id !== id);
      return {
        ...prev,
        variants: remaining,
        hasVariants: remaining.length > 0,
      };
    });
  };

  const handleSaveProduct = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingProduct) {
      updateProduct(editingProduct.id, formData);
      setEditingProduct(null);
    } else {
      addProduct(formData);
    }
    setIsAddModalOpen(false);
  };

  const handleDownloadSampleCsv = () => {
    const sample = `title,sku,category,brand,basePriceAed,compareAtPriceAed,stock,amazonAsin,barcode
"Sony PlayStation 5 Pro UAE Edition","SONY-PS5-PRO","Gaming","Sony",3499,3899,20,"B0DFG921XX","711719582104"
"Bose QuietComfort Ultra Headphones","BOSE-QCU-BLK","Audio","Bose",1699,1999,35,"B0CD27L93Z","017817845210"
"Apple Watch Ultra 2 (Titanium Orange)","APL-WCH-ULT2","Wearables","Apple",3199,3599,15,"B0CHX31XYZ","194253819023"`;
    const blob = new Blob([sample], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sample_uae_products.csv';
    a.click();
  };

  const handleExportCsv = () => {
    const csv = exportProductsCsv();
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `catalog_export_${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
  };

  const handleProcessBulkCsv = () => {
    if (!csvText.trim()) return;
    const lines = csvText.trim().split('\n');
    if (lines.length <= 1) return;

    const headers = lines[0].split(',').map((h) => h.replace(/"/g, '').trim());
    const items: Partial<Product>[] = [];

    for (let i = 1; i < lines.length; i++) {
      const row = lines[i].split(',').map((c) => c.replace(/^"|"$/g, '').trim());
      if (row.length >= 5) {
        items.push({
          title: row[0],
          sku: row[1],
          category: row[2] || 'General',
          brand: row[3] || 'Store Brand',
          basePriceAed: Number(row[4]) || 99,
          compareAtPriceAed: row[5] ? Number(row[5]) : undefined,
          stock: row[6] ? Number(row[6]) : 10,
          amazonAsin: row[7] || '',
          barcode: row[8] || '',
        });
      }
    }

    const res = bulkImportProducts(items);
    setBulkFeedback(res);
    if (res.successCount > 0) {
      setTimeout(() => {
        setIsBulkModalOpen(false);
        setBulkFeedback(null);
        setCsvText('');
      }, 1500);
    }
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
      {/* Header Actions */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem 1.5rem' }}>
        <div>
          <h3 style={{ fontSize: '1.15rem', color: '#fff' }}>Product & Catalog Management</h3>
          <p style={{ fontSize: '0.8rem', color: 'var(--text-secondary)' }}>
            Manage {products.length} products, dynamic variant combinations, and batch CSV imports.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '0.6rem', flexWrap: 'wrap' }}>
          <button onClick={handleExportCsv} className="btn-secondary" style={{ padding: '0.5rem 0.85rem', fontSize: '0.8rem' }}>
            <Download size={15} />
            <span>Export CSV</span>
          </button>
          <button onClick={() => setIsBulkModalOpen(true)} className="btn-secondary" style={{ padding: '0.5rem 0.85rem', fontSize: '0.8rem' }}>
            <Upload size={15} />
            <span>Bulk CSV Import</span>
          </button>
          <button
            onClick={() => {
              setEditingProduct(null);
              setIsAddModalOpen(true);
            }}
            className="btn-primary"
            style={{ padding: '0.5rem 1rem', fontSize: '0.82rem' }}
          >
            <Plus size={16} />
            <span>Add Single Product</span>
          </button>
        </div>
      </div>

      {/* Filter & Search Bar */}
      <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', alignItems: 'center' }}>
        <div style={{ flex: 1, minWidth: '240px', position: 'relative' }}>
          <Search size={16} color="var(--text-muted)" style={{ position: 'absolute', left: '1rem', top: '50%', transform: 'translateY(-50%)' }} />
          <input
            type="text"
            placeholder="Search by title, SKU, or brand..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="input-field"
            style={{ paddingLeft: '2.5rem' }}
          />
        </div>

        <select
          value={selectedCat}
          onChange={(e) => setSelectedCat(e.target.value)}
          className="input-field"
          style={{ width: 'auto', minWidth: '180px', cursor: 'pointer' }}
        >
          <option value="All">All Categories ({products.length})</option>
          {CATEGORIES.filter((c) => c !== 'All Products').map((c) => (
            <option key={c} value={c}>
              {c}
            </option>
          ))}
        </select>
      </div>

      {/* Products Table */}
      <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '14px', overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '0.85rem' }}>
          <thead>
            <tr style={{ background: '#0b0f19', borderBottom: '1px solid var(--border-subtle)', color: 'var(--text-muted)' }}>
              <th style={{ padding: '0.85rem 1rem' }}>Product</th>
              <th style={{ padding: '0.85rem 1rem' }}>SKU</th>
              <th style={{ padding: '0.85rem 1rem' }}>Category</th>
              <th style={{ padding: '0.85rem 1rem' }}>Base Price (AED)</th>
              <th style={{ padding: '0.85rem 1rem' }}>Stock (Warehouse)</th>
              <th style={{ padding: '0.85rem 1rem' }}>Amazon ASIN</th>
              <th style={{ padding: '0.85rem 1rem', textAlign: 'right' }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {filteredProducts.length === 0 ? (
              <tr>
                <td colSpan={7} style={{ padding: '2rem', textAlign: 'center', color: 'var(--text-secondary)' }}>
                  No matching products found.
                </td>
              </tr>
            ) : (
              filteredProducts.map((p) => (
                <tr key={p.id} style={{ borderBottom: '1px solid rgba(255,255,255,0.05)', transition: 'background 0.15s' }}>
                  <td style={{ padding: '0.85rem 1rem', display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                    <img src={p.featuredImage} alt={p.title} style={{ width: '40px', height: '40px', objectFit: 'cover', borderRadius: '6px', background: '#0b0f19' }} />
                    <div>
                      <div style={{ fontWeight: 600, color: '#fff' }}>{p.title}</div>
                      <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>{p.brand} • {p.hasVariants ? `${p.variants.length} Variants` : 'Single'}</div>
                    </div>
                  </td>
                  <td style={{ padding: '0.85rem 1rem', color: 'var(--accent-cyan)', fontWeight: 600 }}>{p.sku}</td>
                  <td style={{ padding: '0.85rem 1rem', color: 'var(--text-secondary)' }}>{p.category}</td>
                  <td style={{ padding: '0.85rem 1rem', fontWeight: 700, color: '#fbbf24' }}>
                    AED {p.basePriceAed.toLocaleString()}
                  </td>
                  <td style={{ padding: '0.85rem 1rem' }}>
                    <span
                      style={{
                        padding: '0.2rem 0.5rem',
                        borderRadius: '6px',
                        fontSize: '0.75rem',
                        fontWeight: 700,
                        background: p.stock > 5 ? 'rgba(16,185,129,0.15)' : p.stock > 0 ? 'rgba(245,158,11,0.15)' : 'rgba(244,63,94,0.15)',
                        color: p.stock > 5 ? '#10b981' : p.stock > 0 ? '#f59e0b' : '#f43f5e',
                      }}
                    >
                      {p.stock} in stock
                    </span>
                  </td>
                  <td style={{ padding: '0.85rem 1rem', color: p.amazonAsin ? '#ff9900' : 'var(--text-muted)' }}>
                    {p.amazonAsin || '—'}
                  </td>
                  <td style={{ padding: '0.85rem 1rem', textAlign: 'right' }}>
                    <div style={{ display: 'inline-flex', gap: '0.4rem' }}>
                      <button
                        onClick={() => {
                          setEditingProduct(p);
                          setFormData({ ...p });
                          setIsAddModalOpen(true);
                        }}
                        style={{ background: '#1e293b', border: '1px solid var(--border-subtle)', borderRadius: '6px', color: '#cbd5e1', padding: '0.35rem 0.5rem', cursor: 'pointer' }}
                        title="Edit Product"
                      >
                        <Edit2 size={14} />
                      </button>
                      <button
                        onClick={() => {
                          if (confirm(`Delete product ${p.title}?`)) {
                            deleteProduct(p.id);
                          }
                        }}
                        style={{ background: 'rgba(244,63,94,0.15)', border: '1px solid rgba(244,63,94,0.3)', borderRadius: '6px', color: '#f43f5e', padding: '0.35rem 0.5rem', cursor: 'pointer' }}
                        title="Delete"
                      >
                        <Trash2 size={14} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Add / Edit Product Modal */}
      {isAddModalOpen && (
        <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.8)', backdropFilter: 'blur(8px)', zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '1.5rem' }}>
          <div style={{ background: '#111827', border: '1px solid var(--border-hover)', borderRadius: '16px', width: '100%', maxWidth: '750px', maxHeight: '90vh', overflowY: 'auto', padding: '1.75rem', position: 'relative' }}>
            <button
              onClick={() => setIsAddModalOpen(false)}
              style={{ position: 'absolute', top: '1.25rem', right: '1.25rem', background: 'none', border: 'none', color: '#9ca3af', cursor: 'pointer' }}
            >
              <X size={20} />
            </button>

            <h3 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '1.25rem' }}>
              {editingProduct ? 'Edit Product' : 'Add New Product'}
            </h3>

            <form onSubmit={handleSaveProduct} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '1rem' }}>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Product Title *</label>
                  <input
                    type="text"
                    required
                    value={formData.title}
                    onChange={(e) => handleTitleChange(e.target.value)}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>SKU Code *</label>
                  <input
                    type="text"
                    required
                    value={formData.sku}
                    onChange={(e) => setFormData({ ...formData, sku: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Barcode / EAN</label>
                  <input
                    type="text"
                    value={formData.barcode || ''}
                    onChange={(e) => setFormData({ ...formData, barcode: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Category</label>
                  <select
                    value={formData.category}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    className="input-field"
                  >
                    {CATEGORIES.filter((c) => c !== 'All Products').map((c) => (
                      <option key={c} value={c}>
                        {c}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Brand</label>
                  <input
                    type="text"
                    value={formData.brand}
                    onChange={(e) => setFormData({ ...formData, brand: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Base Price (AED) *</label>
                  <input
                    type="number"
                    required
                    value={formData.basePriceAed}
                    onChange={(e) => setFormData({ ...formData, basePriceAed: Number(e.target.value) })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Compare At Price (AED)</label>
                  <input
                    type="number"
                    value={formData.compareAtPriceAed || ''}
                    onChange={(e) => setFormData({ ...formData, compareAtPriceAed: Number(e.target.value) })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Available Stock Units *</label>
                  <input
                    type="number"
                    required
                    value={formData.stock}
                    onChange={(e) => setFormData({ ...formData, stock: Number(e.target.value) })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Amazon.ae ASIN</label>
                  <input
                    type="text"
                    placeholder="e.g. B09G9FPHY6"
                    value={formData.amazonAsin || ''}
                    onChange={(e) => setFormData({ ...formData, amazonAsin: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Featured Image URL *</label>
                  <input
                    type="url"
                    required
                    value={formData.featuredImage}
                    onChange={(e) => setFormData({ ...formData, featuredImage: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Description</label>
                  <textarea
                    rows={3}
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    className="input-field"
                  />
                </div>
              </div>

              {/* Dynamic Variants Builder */}
              <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '12px', padding: '1rem' }}>
                <div style={{ fontSize: '0.88rem', fontWeight: 700, color: 'var(--accent-cyan)', marginBottom: '0.75rem', display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
                  <Layers size={16} /> Product Variants (Sizes, Colors, Storage)
                </div>

                {formData.variants.length > 0 && (
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem', marginBottom: '1rem' }}>
                    {formData.variants.map((v) => (
                      <div key={v.id} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#1e293b', padding: '0.45rem 0.75rem', borderRadius: '6px', fontSize: '0.8rem' }}>
                        <span><strong>{v.name}</strong> (SKU: {v.sku}) • Stock: {v.stock} • Price Adj: +AED {v.priceAdjustmentAed}</span>
                        <button type="button" onClick={() => handleRemoveVariant(v.id)} style={{ background: 'none', border: 'none', color: '#f43f5e', cursor: 'pointer' }}>
                          <X size={14} />
                        </button>
                      </div>
                    ))}
                  </div>
                )}

                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: '0.6rem', alignItems: 'flex-end' }}>
                  <div>
                    <label style={{ fontSize: '0.7rem', color: 'var(--text-muted)' }}>Variant Name</label>
                    <input
                      type="text"
                      placeholder="e.g. 512GB / Blue"
                      value={tempVariantName}
                      onChange={(e) => setTempVariantName(e.target.value)}
                      className="input-field"
                      style={{ padding: '0.4rem' }}
                    />
                  </div>
                  <div>
                    <label style={{ fontSize: '0.7rem', color: 'var(--text-muted)' }}>+AED Price Diff</label>
                    <input
                      type="number"
                      value={tempVariantPriceAdj}
                      onChange={(e) => setTempVariantPriceAdj(Number(e.target.value))}
                      className="input-field"
                      style={{ padding: '0.4rem' }}
                    />
                  </div>
                  <div>
                    <label style={{ fontSize: '0.7rem', color: 'var(--text-muted)' }}>Variant Stock</label>
                    <input
                      type="number"
                      value={tempVariantStock}
                      onChange={(e) => setTempVariantStock(Number(e.target.value))}
                      className="input-field"
                      style={{ padding: '0.4rem' }}
                    />
                  </div>
                  <button type="button" onClick={handleAddVariant} className="btn-secondary" style={{ padding: '0.4rem 0.75rem', fontSize: '0.78rem' }}>
                    + Add Variant
                  </button>
                </div>
              </div>

              <div style={{ display: 'flex', gap: '1rem', justifyContent: 'flex-end', marginTop: '1rem' }}>
                <button type="button" onClick={() => setIsAddModalOpen(false)} className="btn-secondary">
                  Cancel
                </button>
                <button type="submit" className="btn-primary">
                  {editingProduct ? 'Update Product' : 'Create Product'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Bulk CSV Import Modal */}
      {isBulkModalOpen && (
        <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.8)', backdropFilter: 'blur(8px)', zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '1.5rem' }}>
          <div style={{ background: '#111827', border: '1px solid var(--border-hover)', borderRadius: '16px', width: '100%', maxWidth: '650px', padding: '1.75rem', position: 'relative' }}>
            <button
              onClick={() => setIsBulkModalOpen(false)}
              style={{ position: 'absolute', top: '1.25rem', right: '1.25rem', background: 'none', border: 'none', color: '#9ca3af', cursor: 'pointer' }}
            >
              <X size={20} />
            </button>

            <h3 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '0.5rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <FileSpreadsheet size={20} color="var(--accent-cyan)" />
              <span>Bulk CSV / Catalog Import Tool</span>
            </h3>
            <p style={{ fontSize: '0.82rem', color: 'var(--text-secondary)', marginBottom: '1.25rem' }}>
              Paste your CSV content or download our formatted sample CSV template.
            </p>

            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.75rem' }}>
              <span style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>CSV Format: title, sku, category, brand, basePriceAed, compareAtPriceAed, stock, amazonAsin</span>
              <button
                type="button"
                onClick={handleDownloadSampleCsv}
                style={{ background: 'none', border: 'none', color: 'var(--accent-gold)', fontSize: '0.78rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.3rem' }}
              >
                <Download size={13} /> Sample CSV
              </button>
            </div>

            <textarea
              rows={8}
              placeholder={`title,sku,category,brand,basePriceAed,compareAtPriceAed,stock,amazonAsin,barcode\n"Sony PS5 Pro","SONY-PS5","Gaming","Sony",3499,3899,20,"B0DFG921XX","711719582104"`}
              value={csvText}
              onChange={(e) => setCsvText(e.target.value)}
              className="input-field"
              style={{ fontFamily: 'monospace', fontSize: '0.78rem', marginBottom: '1rem' }}
            />

            {bulkFeedback && (
              <div style={{ background: bulkFeedback.successCount > 0 ? 'rgba(16,185,129,0.15)' : 'rgba(244,63,94,0.15)', padding: '0.75rem', borderRadius: '8px', marginBottom: '1rem', fontSize: '0.8rem', color: bulkFeedback.successCount > 0 ? '#10b981' : '#f43f5e' }}>
                {bulkFeedback.successCount > 0 && <div>✅ Successfully imported {bulkFeedback.successCount} products!</div>}
                {bulkFeedback.errors.length > 0 && <div>⚠️ {bulkFeedback.errors.join(', ')}</div>}
              </div>
            )}

            <div style={{ display: 'flex', gap: '1rem', justifyContent: 'flex-end' }}>
              <button type="button" onClick={() => setIsBulkModalOpen(false)} className="btn-secondary">
                Cancel
              </button>
              <button type="button" onClick={handleProcessBulkCsv} className="btn-primary">
                Import & Sync Inventory
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
