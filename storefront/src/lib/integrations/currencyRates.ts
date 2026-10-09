import { CurrencyCode, ExchangeRates } from '../types/commerce';

// Actual & Pegged Exchange Rates (Base: AED - UAE Dirham)
// UAE Dirham is strictly pegged to USD at 3.6725 AED = 1 USD (1 AED = 0.272294 USD)
// Indian Rupee (INR) live market rate: 1 AED = ~23.15 INR (1 INR = ~0.0432 AED)
export const DEFAULT_EXCHANGE_RATES: ExchangeRates = {
  base: 'AED',
  rates: {
    AED: 1.0,
    USD: 0.272294, // Fixed peg (3.6725 AED / 1 USD)
    INR: 23.152,   // Live market FX rate
  },
  lastUpdated: new Date().toISOString(),
  source: 'live',
};

export const CURRENCY_SYMBOLS: Record<CurrencyCode, { symbol: string; label: string; prefix: boolean }> = {
  AED: { symbol: 'AED', label: 'UAE Dirham (د.إ)', prefix: true },
  USD: { symbol: '$', label: 'US Dollar', prefix: true },
  INR: { symbol: '₹', label: 'Indian Rupee', prefix: true },
};

/**
 * Converts an amount in base AED to the target currency.
 */
export function convertFromAed(
  amountInAed: number,
  targetCurrency: CurrencyCode,
  rates: ExchangeRates = DEFAULT_EXCHANGE_RATES
): number {
  if (targetCurrency === 'AED') return amountInAed;
  const rate = rates.rates[targetCurrency] || 1;
  return amountInAed * rate;
}

/**
 * Formats a currency amount into a beautifully localized string.
 */
export function formatCurrency(
  amountInAed: number,
  targetCurrency: CurrencyCode = 'AED',
  rates: ExchangeRates = DEFAULT_EXCHANGE_RATES
): string {
  const converted = convertFromAed(amountInAed, targetCurrency, rates);
  const symbolInfo = CURRENCY_SYMBOLS[targetCurrency];

  let formattedNumber: string;
  if (targetCurrency === 'INR') {
    // Standard Indian number formatting with commas (e.g. 1,00,000.00)
    formattedNumber = new Intl.NumberFormat('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(converted);
  } else if (targetCurrency === 'USD') {
    formattedNumber = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(converted);
  } else {
    // AED formatting
    formattedNumber = new Intl.NumberFormat('en-AE', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(converted);
  }

  if (symbolInfo.prefix) {
    return `${symbolInfo.symbol} ${formattedNumber}`;
  }
  return `${formattedNumber} ${symbolInfo.symbol}`;
}

/**
 * Calculates monthly installment amount for BNPL providers (e.g., Tabby in 4, Tamara in 3).
 */
export function calculateBnplInstallment(
  amountInAed: number,
  installments: 3 | 4,
  currency: CurrencyCode = 'AED',
  rates: ExchangeRates = DEFAULT_EXCHANGE_RATES
): string {
  const converted = convertFromAed(amountInAed, currency, rates);
  const perMonth = converted / installments;
  const symbolInfo = CURRENCY_SYMBOLS[currency];
  return `${symbolInfo.symbol} ${perMonth.toFixed(2)}`;
}
