'use client';

import React, { useState, useMemo, Suspense } from 'react';
import { useSearchParams } from 'next/navigation';
import { SlidersHorizontal, Search, RotateCcw, Star, Check } from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import { ProductCard } from '../../components/products/ProductCard';
import { CATEGORIES, BRANDS } from '../../lib/magento/mockData';

function ShopContent() {
  const searchParams = useSearchParams();
  const urlCategory = searchParams.get('cat') || 'All';
  const urlQuery = searchParams.get('q') || '';

  const { products } = useStore();
  const { formatPrice } = useCurrency();

  const [search, setSearch] = useState(urlQuery);
  const [selectedCategory, setSelectedCategory] = useState(urlCategory);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [maxPrice, setMaxPrice] = useState<number>(12000);
  const [onlyInStock, setOnlyInStock] = useState(false);
  const [minRating, setMinRating] = useState<number>(0);
  const [sortBy, setSortBy] = useState<'featured' | 'price-asc' | 'price-desc' | 'rating'>('featured');

  const toggleBrand = (brand: string) => {
    setSelectedBrands((prev) => (prev.includes(brand) ? prev.filter((b) => b !== brand) : [...prev, brand]));
  };

  const handleResetFilters = () => {
    setSearch('');
    setSelectedCategory('All');
    setSelectedBrands([]);
    setMaxPrice(12000);
    setOnlyInStock(false);
    setMinRating(0);
    setSortBy('featured');
  };

  const filteredProducts = useMemo(() => {
    return products
      .filter((p) => {
        const matchesSearch = !search || p.title.toLowerCase().includes(search.toLowerCase()) || p.brand.toLowerCase().includes(search.toLowerCase()) || p.sku.toLowerCase().includes(search.toLowerCase());
        const matchesCat = selectedCategory === 'All' || selectedCategory === 'All Products' || p.category === selectedCategory;
        const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(p.brand);
        const matchesPrice = p.basePriceAed <= maxPrice;
        const matchesStock = !onlyInStock || p.stock > 0;
        const matchesRating = minRating === 0 || p.rating >= minRating;

        return matchesSearch && matchesCat && matchesBrand && matchesPrice && matchesStock && matchesRating;
      })
      .sort((a, b) => {
        if (sortBy === 'price-asc') return a.basePriceAed - b.basePriceAed;
        if (sortBy === 'price-desc') return b.basePriceAed - a.basePriceAed;
        if (sortBy === 'rating') return b.rating - a.rating;
        return 0;
      });
  }, [products, search, selectedCategory, selectedBrands, maxPrice, onlyInStock, minRating, sortBy]);

  return (
    <div className="container" style={{ padding: '2rem 1.5rem 5rem' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem', marginBottom: '2rem' }}>
        <div>
          <h1 style={{ fontSize: '1.75rem', color: '#fff' }}>UAE Product Catalog</h1>
          <p style={{ fontSize: '0.85rem', color: 'var(--text-secondary)' }}>
            Showing {filteredProducts.length} items with UAE VAT included and fast Emirates delivery
          </p>
        </div>

        {/* Sort Select */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
          <span style={{ fontSize: '0.82rem', color: 'var(--text-secondary)' }}>Sort By:</span>
          <select
            value={sortBy}
            onChange={(e) => setSortBy(e.target.value as any)}
            className="input-field"
            style={{ width: 'auto', minWidth: '170px', padding: '0.45rem 0.75rem', fontSize: '0.82rem' }}
          >
            <option value="featured">Featured / Trending</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="rating">Highest Rated</option>
          </select>
        </div>
      </div>

      {/* Main Grid: Sidebar + Product Grid (stacks on mobile/tablet, sidebar on desktop) */}
      <div className="shop-layout">
        {/* Sidebar Filters */}
        <aside style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.5rem', display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '0.75rem' }}>
            <h3 style={{ fontSize: '1rem', color: '#fff', display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <SlidersHorizontal size={16} color="var(--accent-cyan)" />
              <span>Filters</span>
            </h3>
            <button
              onClick={handleResetFilters}
              style={{ background: 'none', border: 'none', color: '#f43f5e', fontSize: '0.75rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.2rem' }}
            >
              <RotateCcw size={12} /> Reset
            </button>
          </div>

          {/* Categories */}
          <div>
            <div style={{ fontSize: '0.82rem', fontWeight: 700, color: '#fff', marginBottom: '0.65rem' }}>Categories</div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.35rem' }}>
              <button
                onClick={() => setSelectedCategory('All')}
                style={{
                  background: 'none',
                  border: 'none',
                  textAlign: 'left',
                  color: selectedCategory === 'All' ? 'var(--accent-cyan)' : 'var(--text-secondary)',
                  fontWeight: selectedCategory === 'All' ? 700 : 400,
                  fontSize: '0.82rem',
                  cursor: 'pointer',
                  padding: '0.2rem 0',
                }}
              >
                All Categories ({products.length})
              </button>
              {CATEGORIES.filter((c) => c !== 'All Products').map((cat) => (
                <button
                  key={cat}
                  onClick={() => setSelectedCategory(cat)}
                  style={{
                    background: 'none',
                    border: 'none',
                    textAlign: 'left',
                    color: selectedCategory === cat ? 'var(--accent-cyan)' : 'var(--text-secondary)',
                    fontWeight: selectedCategory === cat ? 700 : 400,
                    fontSize: '0.82rem',
                    cursor: 'pointer',
                    padding: '0.2rem 0',
                  }}
                >
                  {cat} ({products.filter((p) => p.category === cat).length})
                </button>
              ))}
            </div>
          </div>

          {/* Price Range Slider */}
          <div>
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.82rem', fontWeight: 700, color: '#fff', marginBottom: '0.5rem' }}>
              <span>Max Price:</span>
              <span style={{ color: 'var(--accent-gold)' }}>{formatPrice(maxPrice)}</span>
            </div>
            <input
              type="range"
              min={500}
              max={12000}
              step={200}
              value={maxPrice}
              onChange={(e) => setMaxPrice(Number(e.target.value))}
              style={{ width: '100%', accentColor: 'var(--accent-cyan)', cursor: 'pointer' }}
            />
          </div>

          {/* Brands Filter */}
          <div>
            <div style={{ fontSize: '0.82rem', fontWeight: 700, color: '#fff', marginBottom: '0.65rem' }}>Brands</div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
              {BRANDS.map((brand) => (
                <label key={brand} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.82rem', color: '#cbd5e1', cursor: 'pointer' }}>
                  <input
                    type="checkbox"
                    checked={selectedBrands.includes(brand)}
                    onChange={() => toggleBrand(brand)}
                    style={{ accentColor: 'var(--accent-cyan)' }}
                  />
                  <span>{brand}</span>
                </label>
              ))}
            </div>
          </div>

          {/* In Stock Only */}
          <div style={{ borderTop: '1px solid var(--border-subtle)', paddingTop: '1rem' }}>
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontSize: '0.82rem', color: '#fff', cursor: 'pointer' }}>
              <input
                type="checkbox"
                checked={onlyInStock}
                onChange={(e) => setOnlyInStock(e.target.checked)}
                style={{ accentColor: '#10b981' }}
              />
              <span>In-Stock Only (Dubai Hub)</span>
            </label>
          </div>
        </aside>

        {/* Product Cards Grid */}
        <main>
          {filteredProducts.length === 0 ? (
            <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '4rem 2rem', textAlign: 'center' }}>
              <Search size={40} color="#4b5563" style={{ margin: '0 auto 1rem' }} />
              <h3 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '0.5rem' }}>No products match your filters</h3>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.85rem', marginBottom: '1.5rem' }}>
                Try adjusting your price range, clearing brand filters, or resetting search keywords.
              </p>
              <button onClick={handleResetFilters} className="btn-primary">
                Reset All Filters
              </button>
            </div>
          ) : (
            <div className="grid-products">
              {filteredProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}
        </main>
      </div>
    </div>
  );
}

export default function ShopPage() {
  return (
    <Suspense fallback={<div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center', color: '#fff' }}>Loading Catalog...</div>}>
      <ShopContent />
    </Suspense>
  );
}
