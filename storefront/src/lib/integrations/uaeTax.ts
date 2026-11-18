import { CurrencyCode, ExchangeRates, TaxInvoice } from '../types/commerce';
import { convertFromAed } from './currencyRates';

export const UAE_VAT_RATE = 0.05; // 5% Standard UAE FTA VAT Rate

/**
 * Calculates UAE 5% VAT for a given gross or net subtotal.
 */
export function calculateUaeVat(subtotalAed: number, discountAed: number = 0, shippingAed: number = 0) {
  const taxableAmountAed = Math.max(0, subtotalAed - discountAed + shippingAed);
  const vatAmountAed = taxableAmountAed * UAE_VAT_RATE;
  const grandTotalAed = taxableAmountAed + vatAmountAed;

  return {
    taxableAmountAed: Number(taxableAmountAed.toFixed(2)),
    vatRatePercent: 5,
    vatAmountAed: Number(vatAmountAed.toFixed(2)),
    grandTotalAed: Number(grandTotalAed.toFixed(2)),
  };
}

/**
 * Encodes TLV (Tag-Length-Value) for UAE FTA E-Invoicing QR Code.
 * Tag 1: Seller Name
 * Tag 2: Seller TRN
 * Tag 3: Timestamp
 * Tag 4: Invoice Total (with VAT)
 * Tag 5: VAT Amount
 */
export function generateFtaQrPayload(
  sellerName: string,
  sellerTrn: string,
  timestamp: string,
  totalWithVat: number,
  vatTotal: number
): string {
  const tags: [number, string][] = [
    [1, sellerName],
    [2, sellerTrn],
    [3, timestamp],
    [4, totalWithVat.toFixed(2)],
    [5, vatTotal.toFixed(2)],
  ];

  const buffers: Uint8Array[] = [];
  for (const [tag, val] of tags) {
    const valBytes = new TextEncoder().encode(val);
    const tagBuffer = new Uint8Array(2 + valBytes.length);
    tagBuffer[0] = tag;
    tagBuffer[1] = valBytes.length;
    tagBuffer.set(valBytes, 2);
    buffers.push(tagBuffer);
  }

  // Combine and base64 encode
  const totalLength = buffers.reduce((acc, b) => acc + b.length, 0);
  const combined = new Uint8Array(totalLength);
  let offset = 0;
  for (const b of buffers) {
    combined.set(b, offset);
    offset += b.length;
  }

  // Convert binary to base64
  let binary = '';
  const len = combined.byteLength;
  for (let i = 0; i < len; i++) {
    binary += String.fromCharCode(combined[i]);
  }
  return typeof btoa !== 'undefined' ? btoa(binary) : Buffer.from(binary, 'binary').toString('base64');
}

/**
 * Builds a complete UAE Tax Invoice record.
 */
export function createTaxInvoice(
  invoiceNumber: string,
  sellerName: string,
  sellerTrn: string,
  subtotalAed: number,
  discountAed: number,
  shippingAed: number,
  selectedCurrency: CurrencyCode = 'AED',
  rates?: ExchangeRates
): TaxInvoice {
  const tax = calculateUaeVat(subtotalAed, discountAed, shippingAed);
  const now = new Date().toISOString();
  const rateUsed = rates?.rates[selectedCurrency] || 1;
  const grandTotalSelectedCurrency = convertFromAed(tax.grandTotalAed, selectedCurrency, rates);

  const qrPayload = generateFtaQrPayload(
    sellerName,
    sellerTrn,
    now,
    tax.grandTotalAed,
    tax.vatAmountAed
  );

  return {
    invoiceNumber,
    trnSeller: sellerTrn,
    date: now,
    subtotalAed,
    vatRatePercent: 5,
    vatAmountAed: tax.vatAmountAed,
    shippingAed,
    discountAed,
    grandTotalAed: tax.grandTotalAed,
    grandTotalSelectedCurrency: Number(grandTotalSelectedCurrency.toFixed(2)),
    currencyCode: selectedCurrency,
    exchangeRateUsed: rateUsed,
    qrPayload,
  };
}
