'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import confetti from 'canvas-confetti';
import {
  ShieldCheck,
  Truck,
  CreditCard,
  Building,
  CheckCircle2,
  Download,
  ExternalLink,
  Zap,
  ArrowRight,
  ArrowLeft,
  Copy,
  Check,
  Lock,
} from 'lucide-react';
import { triggerSuccessHaptic } from '../../lib/native/haptics';
import { useCart } from '../../context/CartContext';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';
import {
  CustomerAddress,
  Emirate,
  Order,
  OrderItem,
  PaymentMethodType,
  ShippingMethod,
} from '../../lib/types/commerce';
import { getShippingMethodsForEmirate, generateAmazonTrackingId } from '../../lib/integrations/amazonDelivery';
import { createTaxInvoice } from '../../lib/integrations/uaeTax';
import { generateBankTransferReference, getPaymentMethodLabel } from '../../lib/integrations/uaePayments';
import { syncOrderToOdoo } from '../../lib/integrations/odooConnector';

export const CheckoutWizard: React.FC = () => {
  const router = useRouter();
  const { cart, subtotalAed, discountAed, clearCart } = useCart();
  const { storeIdentity, paymentSettings, odooSettings, createOrder, addOdooLog } = useStore();
  const { currency, rates, formatPrice, convertPrice, formatBnpl } = useCurrency();

  const [currentStep, setCurrentStep] = useState<1 | 2 | 3 | 4>(1);
  const [isProcessing, setIsProcessing] = useState(false);
  const [completedOrder, setCompletedOrder] = useState<Order | null>(null);
  const [copiedIban, setCopiedIban] = useState<string | null>(null);

  // Form State
  const [customer, setCustomer] = useState<CustomerAddress>({
    fullName: '',
    email: '',
    phone: '+971 50 ',
    emirate: 'Dubai',
    area: 'Downtown Dubai',
    streetAddress: 'Sheikh Mohammed Bin Rashid Blvd',
    buildingVilla: 'Burj Residences Tower 2',
    apartmentSuite: 'Apt 1402',
    country: 'United Arab Emirates',
    trnNumber: '',
  });

  const [selectedShippingMethod, setSelectedShippingMethod] = useState<ShippingMethod>(
    getShippingMethodsForEmirate('Dubai', subtotalAed, storeIdentity.freeShippingThresholdAed)[0]
  );

  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState<PaymentMethodType>('tabby');
  const [selectedBankIndex, setSelectedBankIndex] = useState<number>(0);

  // Available shipping methods based on current selected emirate
  const shippingMethods = getShippingMethodsForEmirate(
    customer.emirate,
    subtotalAed,
    storeIdentity.freeShippingThresholdAed
  );

  // Calculations
  const shippingAed = selectedShippingMethod.priceAed;
  const codFeeAed = selectedPaymentMethod === 'cod' ? paymentSettings.cod.feeAed : 0;
  const grandTaxableAed = Math.max(0, subtotalAed - discountAed + shippingAed + codFeeAed);
  const vatAmountAed = Number((grandTaxableAed * 0.05).toFixed(2));
  const grandTotalAed = Number((grandTaxableAed + vatAmountAed).toFixed(2));
  const grandTotalConverted = convertPrice(grandTotalAed);

  const emiratesList: Emirate[] = [
    'Dubai',
    'Abu Dhabi',
    'Sharjah',
    'Ajman',
    'Ras Al Khaimah',
    'Fujairah',
    'Umm Al Quwain',
  ];

  const handleEmirateChange = (newEmirate: Emirate) => {
    setCustomer((prev) => ({ ...prev, emirate: newEmirate }));
    const available = getShippingMethodsForEmirate(newEmirate, subtotalAed, storeIdentity.freeShippingThresholdAed);
    setSelectedShippingMethod(available[0]);
  };

  const handleCopyIban = (iban: string) => {
    navigator.clipboard.writeText(iban);
    setCopiedIban(iban);
    setTimeout(() => setCopiedIban(null), 2000);
  };

  const handlePlaceOrder = async () => {
    setIsProcessing(true);
    const orderNumber = `ORD-${Math.floor(100000 + Math.random() * 900000)}`;
    const invoiceNumber = `INV-${new Date().getFullYear()}-${Math.floor(1000 + Math.random() * 9000)}`;

    const orderItems: OrderItem[] = cart.map((c) => ({
      productId: c.product.id,
      sku: c.selectedVariant?.sku || c.product.sku,
      title: c.product.title,
      variantName: c.selectedVariant?.name,
      quantity: c.quantity,
      unitPriceAed: c.unitPriceAed,
      totalPriceAed: c.unitPriceAed * c.quantity,
      image: c.product.featuredImage,
    }));

    const taxInvoice = createTaxInvoice(
      invoiceNumber,
      storeIdentity.companyLegalName,
      storeIdentity.uaeTrn,
      subtotalAed,
      discountAed,
      shippingAed + codFeeAed,
      currency,
      rates
    );

    const trackingNumber = selectedShippingMethod.isAmazonFulfillment
      ? generateAmazonTrackingId()
      : `DXB-${Math.floor(10000000 + Math.random() * 90000000)}`;

    const carrier = selectedShippingMethod.isAmazonFulfillment
      ? 'Amazon Logistics UAE'
      : 'Dubai Express Logistics';

    const newOrder: Order = {
      id: 'ord-' + Date.now(),
      orderNumber,
      createdAt: new Date().toISOString(),
      customer,
      items: orderItems,
      subtotalAed,
      vatAmountAed,
      shippingAmountAed: shippingAed + codFeeAed,
      discountAmountAed: discountAed,
      totalAmountAed: grandTotalAed,
      currency,
      currencyRate: rates.rates[currency] || 1,
      totalInSelectedCurrency: grandTotalConverted,
      shippingMethod: selectedShippingMethod,
      paymentMethod: selectedPaymentMethod,
      paymentStatus: selectedPaymentMethod === 'bank_transfer' ? 'pending_verification' : selectedPaymentMethod === 'cod' ? 'pending_cod' : 'paid',
      paymentReference: selectedPaymentMethod === 'bank_transfer' ? generateBankTransferReference(orderNumber) : `TXN-${Date.now()}`,
      orderStatus: 'confirmed',
      trackingNumber,
      carrier,
      taxInvoice,
      odooSynced: false,
    };

    // Save order
    createOrder(newOrder);

    // Odoo ERP Sync
    if (odooSettings.enabled) {
      try {
        const odooRes = await syncOrderToOdoo(newOrder, odooSettings);
        if (odooRes.success) {
          newOrder.odooSynced = true;
          newOrder.odooSaleOrderId = odooRes.odooSaleOrderId;
          addOdooLog(odooRes.log);
        }
      } catch (e) {}
    }

    setCompletedOrder(newOrder);
    setIsProcessing(false);
    setCurrentStep(4);
    clearCart();
    triggerSuccessHaptic();

    // Trigger celebratory confetti
    try {
      confetti({
        particleCount: 120,
        spread: 80,
        origin: { y: 0.6 },
        colors: ['#0ea5e9', '#f59e0b', '#10b981', '#ffffff'],
      });
    } catch (e) {}
  };

  if (cart.length === 0 && currentStep !== 4) {
    return (
      <div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center' }}>
        <h2 style={{ fontSize: '1.5rem', color: '#fff', marginBottom: '1rem' }}>Your shopping bag is empty</h2>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '2rem' }}>Please add items to your cart before proceeding to checkout.</p>
        <Link href="/shop" className="btn-primary">
          Browse Catalog
        </Link>
      </div>
    );
  }

  return (
    <div className="container" style={{ padding: '2rem 1.5rem 4rem' }}>
      {/* Steps Indicator */}
      <div style={{ maxWidth: '800px', margin: '0 auto 2.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', position: 'relative' }}>
        {[
          { num: 1, label: '1. Emirates Address' },
          { num: 2, label: '2. Delivery & Amazon' },
          { num: 3, label: '3. Payment & VAT' },
          { num: 4, label: '4. Order Receipt' },
        ].map((s) => (
          <div key={s.num} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '0.4rem', zIndex: 2 }}>
            <div
              style={{
                width: '36px',
                height: '36px',
                borderRadius: '50%',
                background: currentStep >= s.num ? 'linear-gradient(135deg, var(--accent-cyan), #0284c7)' : '#1f2937',
                color: '#fff',
                fontWeight: 700,
                fontSize: '0.85rem',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                border: '2px solid ' + (currentStep >= s.num ? 'var(--accent-cyan)' : 'var(--border-subtle)'),
                boxShadow: currentStep === s.num ? 'var(--shadow-glow)' : 'none',
              }}
            >
              {currentStep > s.num ? <Check size={16} /> : s.num}
            </div>
            <span style={{ fontSize: '0.75rem', fontWeight: 600, color: currentStep >= s.num ? '#fff' : 'var(--text-muted)' }}>
              {s.label}
            </span>
          </div>
        ))}
      </div>

      {/* Main Grid: Wizard Form on Left, Order Summary on Right */}
      <div style={{ display: 'grid', gridTemplateColumns: currentStep === 4 ? '1fr' : 'repeat(auto-fit, minmax(320px, 1fr))', gap: '2rem' }}>
        {/* Left: Step Content */}
        <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.75rem' }}>
          {/* STEP 1: Address & UAE Emirates */}
          {currentStep === 1 && (
            <div>
              <h2 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                <Building size={20} color="var(--accent-cyan)" />
                <span>UAE Shipping & Delivery Address</span>
              </h2>

              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '1rem', marginBottom: '1.5rem' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Full Name *</label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Mohammed Al Hashimi"
                    value={customer.fullName}
                    onChange={(e) => setCustomer({ ...customer, fullName: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Email Address *</label>
                  <input
                    type="email"
                    required
                    placeholder="m.hashimi@example.ae"
                    value={customer.email}
                    onChange={(e) => setCustomer({ ...customer, email: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>UAE Mobile Number (+971) *</label>
                  <input
                    type="tel"
                    required
                    placeholder="+971 50 123 4567"
                    value={customer.phone}
                    onChange={(e) => setCustomer({ ...customer, phone: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Emirate *</label>
                  <select
                    value={customer.emirate}
                    onChange={(e) => handleEmirateChange(e.target.value as Emirate)}
                    className="input-field"
                    style={{ cursor: 'pointer' }}
                  >
                    {emiratesList.map((em) => (
                      <option key={em} value={em}>
                        {em}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Area / District *</label>
                  <input
                    type="text"
                    required
                    placeholder="Downtown Dubai / Dubai Marina / Al Reem"
                    value={customer.area}
                    onChange={(e) => setCustomer({ ...customer, area: e.target.value })}
                    className="input-field"
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '0.78rem', color: 'var(--text-secondary)', marginBottom: '0.3rem' }}>Building / Villa / Street *</label>
                  <input
                    type="text"
                    required
                    placeholder="Tower 2, Apt 1402, Street 4"
                    value={customer.buildingVilla}
                    onChange={(e) => setCustomer({ ...customer, buildingVilla: e.target.value })}
                    className="input-field"
                  />
                </div>
              </div>

              {/* B2B TRN */}
              <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '0.85rem', marginBottom: '1.5rem' }}>
                <label style={{ display: 'block', fontSize: '0.75rem', color: '#94a3b8', marginBottom: '0.3rem' }}>
                  🏢 Corporate UAE TRN Number (Optional - for B2B Tax Invoice):
                </label>
                <input
                  type="text"
                  placeholder="100XXXXXXXXX003 (15 digits)"
                  value={customer.trnNumber || ''}
                  onChange={(e) => setCustomer({ ...customer, trnNumber: e.target.value })}
                  className="input-field"
                  style={{ fontSize: '0.82rem' }}
                />
              </div>

              <button
                onClick={() => {
                  if (!customer.fullName || !customer.email || !customer.phone) {
                    alert('Please enter your full name, email, and UAE phone number.');
                    return;
                  }
                  setCurrentStep(2);
                }}
                className="btn-primary"
                style={{ width: '100%', padding: '0.85rem' }}
              >
                <span>Continue to Delivery Options</span>
                <ArrowRight size={18} />
              </button>
            </div>
          )}

          {/* STEP 2: Delivery & Amazon MCF */}
          {currentStep === 2 && (
            <div>
              <h2 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                <Truck size={20} color="var(--accent-cyan)" />
                <span>Select Fulfillment & Delivery Method ({customer.emirate})</span>
              </h2>

              <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginBottom: '1.75rem' }}>
                {shippingMethods.map((method) => (
                  <div
                    key={method.id}
                    onClick={() => setSelectedShippingMethod(method)}
                    style={{
                      border: selectedShippingMethod.id === method.id ? '2px solid var(--accent-cyan)' : '1px solid var(--border-subtle)',
                      background: selectedShippingMethod.id === method.id ? 'rgba(14, 165, 233, 0.08)' : '#0b0f19',
                      borderRadius: '12px',
                      padding: '1.25rem',
                      cursor: 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'space-between',
                      gap: '1rem',
                      transition: 'var(--transition-fast)',
                    }}
                  >
                    <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.85rem' }}>
                      <div
                        style={{
                          width: '20px',
                          height: '20px',
                          borderRadius: '50%',
                          border: '2px solid ' + (selectedShippingMethod.id === method.id ? 'var(--accent-cyan)' : '#4b5563'),
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          marginTop: '0.2rem',
                        }}
                      >
                        {selectedShippingMethod.id === method.id && (
                          <div style={{ width: '10px', height: '10px', borderRadius: '50%', background: 'var(--accent-cyan)' }} />
                        )}
                      </div>

                      <div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', flexWrap: 'wrap' }}>
                          <span style={{ fontWeight: 700, color: '#fff', fontSize: '0.95rem' }}>{method.name}</span>
                          {method.isAmazonFulfillment && (
                            <span style={{ padding: '0.15rem 0.45rem', borderRadius: '4px', background: '#232f3e', color: '#ff9900', fontSize: '0.68rem', fontWeight: 800 }}>
                              Amazon Prime Speed
                            </span>
                          )}
                        </div>
                        <div style={{ fontSize: '0.8rem', color: 'var(--text-secondary)', marginTop: '0.25rem' }}>
                          {method.description}
                        </div>
                        <div style={{ fontSize: '0.75rem', color: '#10b981', fontWeight: 600, marginTop: '0.2rem' }}>
                          Estimated: {method.estimatedDelivery}
                        </div>
                      </div>
                    </div>

                    <div style={{ fontSize: '1rem', fontWeight: 800, color: method.priceAed === 0 ? '#10b981' : '#fff' }}>
                      {method.priceAed === 0 ? 'FREE' : formatPrice(method.priceAed)}
                    </div>
                  </div>
                ))}
              </div>

              <div style={{ display: 'flex', gap: '1rem' }}>
                <button onClick={() => setCurrentStep(1)} className="btn-secondary" style={{ flex: 1 }}>
                  <ArrowLeft size={16} />
                  <span>Back</span>
                </button>
                <button onClick={() => setCurrentStep(3)} className="btn-primary" style={{ flex: 2 }}>
                  <span>Continue to Payment</span>
                  <ArrowRight size={16} />
                </button>
              </div>
            </div>
          )}

          {/* STEP 3: UAE Payment Channels & Bank IBAN */}
          {currentStep === 3 && (
            <div>
              <h2 style={{ fontSize: '1.25rem', color: '#fff', marginBottom: '1.25rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                <CreditCard size={20} color="var(--accent-cyan)" />
                <span>Select UAE Payment Engine</span>
              </h2>

              <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginBottom: '1.75rem' }}>
                {/* Tabby (Split in 4) */}
                <div
                  onClick={() => setSelectedPaymentMethod('tabby')}
                  style={{
                    border: selectedPaymentMethod === 'tabby' ? '2px solid #3bfc87' : '1px solid var(--border-subtle)',
                    background: selectedPaymentMethod === 'tabby' ? 'rgba(59, 252, 135, 0.06)' : '#0b0f19',
                    borderRadius: '12px',
                    padding: '1.15rem',
                    cursor: 'pointer',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                      <span style={{ padding: '0.2rem 0.5rem', borderRadius: '6px', background: '#3bfc87', color: '#0b1a10', fontWeight: 800, fontSize: '0.75rem' }}>
                        tabby
                      </span>
                      <div>
                        <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>Tabby - Split in 4 Payments</div>
                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                          Pay 4 monthly installments of <strong>{formatBnpl(grandTotalAed, 4)}</strong>. 0% interest.
                        </div>
                      </div>
                    </div>
                    {selectedPaymentMethod === 'tabby' && <CheckCircle2 size={20} color="#3bfc87" />}
                  </div>
                </div>

                {/* Tamara (Split in 3) */}
                <div
                  onClick={() => setSelectedPaymentMethod('tamara')}
                  style={{
                    border: selectedPaymentMethod === 'tamara' ? '2px solid #f69371' : '1px solid var(--border-subtle)',
                    background: selectedPaymentMethod === 'tamara' ? 'rgba(246, 147, 113, 0.06)' : '#0b0f19',
                    borderRadius: '12px',
                    padding: '1.15rem',
                    cursor: 'pointer',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                      <span style={{ padding: '0.2rem 0.5rem', borderRadius: '6px', background: '#fce0c6', color: '#2c1006', fontWeight: 800, fontSize: '0.75rem' }}>
                        tamara
                      </span>
                      <div>
                        <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>Tamara - Pay in 3 Installments</div>
                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                          Pay 3 monthly payments of <strong>{formatBnpl(grandTotalAed, 3)}</strong>. Shariah certified.
                        </div>
                      </div>
                    </div>
                    {selectedPaymentMethod === 'tamara' && <CheckCircle2 size={20} color="#f69371" />}
                  </div>
                </div>

                {/* Stripe UAE / Apple Pay */}
                <div
                  onClick={() => setSelectedPaymentMethod('stripe')}
                  style={{
                    border: selectedPaymentMethod === 'stripe' ? '2px solid var(--accent-cyan)' : '1px solid var(--border-subtle)',
                    background: selectedPaymentMethod === 'stripe' ? 'rgba(14, 165, 233, 0.06)' : '#0b0f19',
                    borderRadius: '12px',
                    padding: '1.15rem',
                    cursor: 'pointer',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                      <CreditCard size={20} color="var(--accent-cyan)" />
                      <div>
                        <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>Credit / Debit Card & Apple Pay</div>
                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                          Visa, MasterCard, American Express via Stripe UAE gateway.
                        </div>
                      </div>
                    </div>
                    {selectedPaymentMethod === 'stripe' && <CheckCircle2 size={20} color="var(--accent-cyan)" />}
                  </div>
                </div>

                {/* Direct UAE Bank IBAN Transfer */}
                <div
                  onClick={() => setSelectedPaymentMethod('bank_transfer')}
                  style={{
                    border: selectedPaymentMethod === 'bank_transfer' ? '2px solid var(--accent-gold)' : '1px solid var(--border-subtle)',
                    background: selectedPaymentMethod === 'bank_transfer' ? 'rgba(245, 158, 11, 0.06)' : '#0b0f19',
                    borderRadius: '12px',
                    padding: '1.15rem',
                    cursor: 'pointer',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                      <Building size={20} color="var(--accent-gold)" />
                      <div>
                        <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>Direct UAE Bank Transfer (IBAN / FTS)</div>
                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                          Transfer directly to Emirates NBD, ADCB, or Wio Bank with zero gateway surcharge.
                        </div>
                      </div>
                    </div>
                    {selectedPaymentMethod === 'bank_transfer' && <CheckCircle2 size={20} color="var(--accent-gold)" />}
                  </div>

                  {/* Bank Details sub-panel */}
                  {selectedPaymentMethod === 'bank_transfer' && (
                    <div style={{ marginTop: '1rem', background: '#1e293b', border: '1px solid var(--border-subtle)', borderRadius: '10px', padding: '1rem' }}>
                      <div style={{ fontSize: '0.75rem', color: '#fbbf24', fontWeight: 700, marginBottom: '0.5rem' }}>
                        Select Corporate Receiving Account:
                      </div>

                      <div style={{ display: 'flex', gap: '0.5rem', marginBottom: '0.75rem', flexWrap: 'wrap' }}>
                        {paymentSettings.bankTransfer.accounts.map((acc, idx) => (
                          <button
                            key={acc.iban}
                            type="button"
                            onClick={(e) => {
                              e.stopPropagation();
                              setSelectedBankIndex(idx);
                            }}
                            style={{
                              padding: '0.35rem 0.65rem',
                              borderRadius: '6px',
                              background: selectedBankIndex === idx ? 'var(--accent-gold)' : '#0f172a',
                              color: selectedBankIndex === idx ? '#000' : '#fff',
                              fontWeight: 700,
                              fontSize: '0.72rem',
                              border: 'none',
                              cursor: 'pointer',
                            }}
                          >
                            {acc.bankName.split(' ')[0]}
                          </button>
                        ))}
                      </div>

                      {paymentSettings.bankTransfer.accounts[selectedBankIndex] && (
                        <div style={{ fontSize: '0.78rem', color: '#cbd5e1', display: 'flex', flexDirection: 'column', gap: '0.3rem' }}>
                          <div><strong>Bank:</strong> {paymentSettings.bankTransfer.accounts[selectedBankIndex].bankName}</div>
                          <div><strong>Account Title:</strong> {paymentSettings.bankTransfer.accounts[selectedBankIndex].accountTitle}</div>
                          <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                            <span><strong>IBAN:</strong> {paymentSettings.bankTransfer.accounts[selectedBankIndex].iban}</span>
                            <button
                              type="button"
                              onClick={(e) => {
                                e.stopPropagation();
                                handleCopyIban(paymentSettings.bankTransfer.accounts[selectedBankIndex].iban);
                              }}
                              style={{ background: 'none', border: 'none', color: 'var(--accent-cyan)', cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.2rem' }}
                            >
                              {copiedIban === paymentSettings.bankTransfer.accounts[selectedBankIndex].iban ? (
                                <span style={{ color: '#10b981' }}>Copied!</span>
                              ) : (
                                <Copy size={13} />
                              )}
                            </button>
                          </div>
                          <div><strong>SWIFT / BIC:</strong> {paymentSettings.bankTransfer.accounts[selectedBankIndex].swiftBic}</div>
                        </div>
                      )}
                    </div>
                  )}
                </div>

                {/* Cash on Delivery (COD) */}
                <div
                  onClick={() => setSelectedPaymentMethod('cod')}
                  style={{
                    border: selectedPaymentMethod === 'cod' ? '2px solid #10b981' : '1px solid var(--border-subtle)',
                    background: selectedPaymentMethod === 'cod' ? 'rgba(16, 185, 129, 0.06)' : '#0b0f19',
                    borderRadius: '12px',
                    padding: '1.15rem',
                    cursor: 'pointer',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                      <Truck size={20} color="#10b981" />
                      <div>
                        <div style={{ fontWeight: 700, color: '#fff', fontSize: '0.92rem' }}>Cash on Delivery (UAE Emirates)</div>
                        <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                          Pay in cash or card at your doorstep to the courier (+AED {paymentSettings.cod.feeAed} handling).
                        </div>
                      </div>
                    </div>
                    {selectedPaymentMethod === 'cod' && <CheckCircle2 size={20} color="#10b981" />}
                  </div>
                </div>
              </div>

              <div style={{ display: 'flex', gap: '1rem' }}>
                <button onClick={() => setCurrentStep(2)} className="btn-secondary" style={{ flex: 1 }}>
                  <ArrowLeft size={16} />
                  <span>Back</span>
                </button>
                <button
                  onClick={handlePlaceOrder}
                  disabled={isProcessing}
                  className="btn-gold"
                  style={{ flex: 2, padding: '0.85rem', fontSize: '1rem' }}
                >
                  <Lock size={16} />
                  <span>{isProcessing ? 'Processing UAE Order...' : `Pay & Complete Order (${formatPrice(grandTotalAed)})`}</span>
                </button>
              </div>
            </div>
          )}

          {/* STEP 4: Order Confirmation & FTA Tax Invoice */}
          {currentStep === 4 && completedOrder && (
            <div style={{ textAlign: 'center', padding: '1rem 0' }}>
              <div style={{ width: '64px', height: '64px', borderRadius: '50%', background: 'rgba(16, 185, 129, 0.2)', border: '2px solid #10b981', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 1.5rem' }}>
                <CheckCircle2 size={36} color="#10b981" />
              </div>

              <h2 style={{ fontSize: '1.75rem', color: '#fff', marginBottom: '0.5rem' }}>
                Order Confirmed! Mabrook! 🎉
              </h2>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem', marginBottom: '1.5rem' }}>
                Your order has been received and dispatched to our {storeIdentity.companyLegalName} fulfillment center.
              </p>

              {/* Order Info Card */}
              <div style={{ background: '#0b0f19', border: '1px solid var(--border-subtle)', borderRadius: '14px', padding: '1.5rem', textAlign: 'left', maxWidth: '600px', margin: '0 auto 2rem' }}>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1rem', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '1rem', marginBottom: '1rem' }}>
                  <div>
                    <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Order Reference:</span>
                    <div style={{ fontSize: '1rem', fontWeight: 800, color: 'var(--accent-cyan)' }}>{completedOrder.orderNumber}</div>
                  </div>
                  <div>
                    <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Fulfillment Tracking:</span>
                    <div style={{ fontSize: '0.95rem', fontWeight: 700, color: '#fbbf24' }}>
                      {completedOrder.carrier} ({completedOrder.trackingNumber})
                    </div>
                  </div>
                  <div>
                    <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Payment Channel:</span>
                    <div style={{ fontSize: '0.85rem', fontWeight: 600, color: '#fff' }}>
                      {getPaymentMethodLabel(completedOrder.paymentMethod)}
                    </div>
                  </div>
                  <div>
                    <span style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>UAE TRN (Tax):</span>
                    <div style={{ fontSize: '0.85rem', fontWeight: 600, color: '#fff' }}>
                      {completedOrder.taxInvoice.trnSeller}
                    </div>
                  </div>
                </div>

                {/* Items preview */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem', marginBottom: '1rem' }}>
                  {completedOrder.items.map((item) => (
                    <div key={item.sku} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: '0.82rem' }}>
                      <span>{item.quantity}x {item.title}</span>
                      <span style={{ fontWeight: 700, color: '#fff' }}>{formatPrice(item.totalPriceAed)}</span>
                    </div>
                  ))}
                </div>

                {/* VAT Total */}
                <div style={{ borderTop: '1px solid var(--border-subtle)', paddingTop: '0.75rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: '1.1rem', fontWeight: 800 }}>
                  <span>Total (incl. 5% UAE VAT):</span>
                  <span style={{ color: 'var(--accent-gold)' }}>{formatPrice(completedOrder.totalAmountAed)}</span>
                </div>

                {/* Bank Transfer Instructions if chosen */}
                {completedOrder.paymentMethod === 'bank_transfer' && (
                  <div style={{ marginTop: '1rem', background: 'rgba(245,158,11,0.1)', border: '1px solid rgba(245,158,11,0.3)', borderRadius: '8px', padding: '0.85rem', fontSize: '0.78rem' }}>
                    <div style={{ fontWeight: 700, color: '#fbbf24', marginBottom: '0.3rem' }}>
                      Bank Transfer Reference Code: <strong>{completedOrder.paymentReference}</strong>
                    </div>
                    <p style={{ color: '#cbd5e1' }}>
                      Please include this reference code in your bank transfer description to ensure instantaneous allocation.
                    </p>
                  </div>
                )}

                {/* Odoo Sync badge */}
                {completedOrder.odooSynced && (
                  <div style={{ marginTop: '0.75rem', display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.75rem', color: '#10b981' }}>
                    <Check size={14} /> Synchronized with Odoo ERP (#SO-{completedOrder.odooSaleOrderId})
                  </div>
                )}
              </div>

              {/* Actions */}
              <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center', flexWrap: 'wrap' }}>
                <Link href={`/account?tab=orders&highlight=${completedOrder.id}`} className="btn-secondary">
                  <span>View in Customer Account</span>
                  <ExternalLink size={16} />
                </Link>
                <Link href="/shop" className="btn-primary">
                  <span>Continue Shopping</span>
                  <ArrowRight size={16} />
                </Link>
              </div>
            </div>
          )}
        </div>

        {/* Right: Order Summary Sidebar (for Steps 1-3) */}
        {currentStep < 4 && (
          <div style={{ background: '#111827', border: '1px solid var(--border-subtle)', borderRadius: '16px', padding: '1.5rem', height: 'fit-content' }}>
            <h3 style={{ fontSize: '1.1rem', color: '#fff', marginBottom: '1rem', borderBottom: '1px solid var(--border-subtle)', paddingBottom: '0.75rem' }}>
              Order Summary ({cart.length} items)
            </h3>

            {/* Item list snippet */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem', marginBottom: '1.25rem', maxHeight: '240px', overflowY: 'auto' }}>
              {cart.map((item) => (
                <div key={item.id} style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
                  <img src={item.product.featuredImage} alt={item.product.title} style={{ width: '48px', height: '48px', objectFit: 'cover', borderRadius: '6px', background: '#0b0f19' }} />
                  <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ fontSize: '0.82rem', fontWeight: 600, color: '#fff', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                      {item.product.title}
                    </div>
                    <div style={{ fontSize: '0.72rem', color: 'var(--text-secondary)' }}>
                      Qty: {item.quantity} {item.selectedVariant ? `• ${item.selectedVariant.name}` : ''}
                    </div>
                  </div>
                  <div style={{ fontSize: '0.85rem', fontWeight: 700, color: '#fff' }}>
                    {formatPrice(item.unitPriceAed * item.quantity)}
                  </div>
                </div>
              ))}
            </div>

            {/* Calculations */}
            <div style={{ borderTop: '1px solid var(--border-subtle)', paddingTop: '1rem', display: 'flex', flexDirection: 'column', gap: '0.45rem', fontSize: '0.85rem' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--text-secondary)' }}>
                <span>Subtotal:</span>
                <span style={{ color: '#fff', fontWeight: 600 }}>{formatPrice(subtotalAed)}</span>
              </div>

              {discountAed > 0 && (
                <div style={{ display: 'flex', justifyContent: 'space-between', color: '#10b981' }}>
                  <span>Discount:</span>
                  <span>-{formatPrice(discountAed)}</span>
                </div>
              )}

              <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--text-secondary)' }}>
                <span>Shipping ({customer.emirate}):</span>
                <span style={{ color: shippingAed === 0 ? '#10b981' : '#fff', fontWeight: 600 }}>
                  {shippingAed === 0 ? 'FREE' : formatPrice(shippingAed)}
                </span>
              </div>

              {codFeeAed > 0 && (
                <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--text-secondary)' }}>
                  <span>COD Handling Fee:</span>
                  <span style={{ color: '#fff', fontWeight: 600 }}>{formatPrice(codFeeAed)}</span>
                </div>
              )}

              <div style={{ display: 'flex', justifyContent: 'space-between', color: 'var(--text-secondary)' }}>
                <span>UAE VAT (5% FTA Standard):</span>
                <span style={{ color: '#fff', fontWeight: 600 }}>{formatPrice(vatAmountAed)}</span>
              </div>

              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.2rem', fontWeight: 800, color: '#fff', borderTop: '1px solid var(--border-subtle)', paddingTop: '0.75rem', marginTop: '0.25rem' }}>
                <span>Grand Total:</span>
                <span style={{ color: 'var(--accent-gold)' }}>{formatPrice(grandTotalAed)}</span>
              </div>

              {/* Currency conversion notice if USD or INR is selected */}
              {currency !== 'AED' && (
                <div style={{ fontSize: '0.72rem', color: 'var(--text-muted)', textAlign: 'right' }}>
                  Base AED amount: AED {grandTotalAed.toLocaleString()}
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
