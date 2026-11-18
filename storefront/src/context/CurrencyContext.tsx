'use client';

import React, { createContext, useContext, useEffect, useState } from 'react';
import { CurrencyCode, ExchangeRates } from '../lib/types/commerce';
import {
  DEFAULT_EXCHANGE_RATES,
  convertFromAed,
  formatCurrency,
  calculateBnplInstallment,
} from '../lib/integrations/currencyRates';

interface CurrencyContextType {
  currency: CurrencyCode;
  setCurrency: (code: CurrencyCode) => void;
  rates: ExchangeRates;
  updateRates: (newRates: Partial<ExchangeRates['rates']>) => void;
  formatPrice: (amountInAed: number) => string;
  convertPrice: (amountInAed: number) => number;
  formatBnpl: (amountInAed: number, installments?: 3 | 4) => string;
  refreshLiveRates: () => Promise<void>;
  isLoadingRates: boolean;
}

const CurrencyContext = createContext<CurrencyContextType | undefined>(undefined);

export const CurrencyProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [currency, setCurrencyState] = useState<CurrencyCode>('AED');
  const [rates, setRates] = useState<ExchangeRates>(DEFAULT_EXCHANGE_RATES);
  const [isLoadingRates, setIsLoadingRates] = useState<boolean>(false);

  useEffect(() => {
    // Load saved preferences
    try {
      const savedCurr = localStorage.getItem('magento_selected_currency') as CurrencyCode;
      if (savedCurr && (savedCurr === 'AED' || savedCurr === 'USD' || savedCurr === 'INR')) {
        setCurrencyState(savedCurr);
      }
      const savedRates = localStorage.getItem('magento_custom_rates');
      if (savedRates) {
        setRates(JSON.parse(savedRates));
      }
    } catch (e) {
      // Ignore localStorage errors in SSR
    }
  }, []);

  const setCurrency = (code: CurrencyCode) => {
    setCurrencyState(code);
    try {
      localStorage.setItem('magento_selected_currency', code);
    } catch (e) {}
  };

  const updateRates = (newRates: Partial<ExchangeRates['rates']>) => {
    setRates((prev) => {
      const updated: ExchangeRates = {
        ...prev,
        rates: { ...prev.rates, ...newRates },
        lastUpdated: new Date().toISOString(),
        source: 'manual',
      };
      try {
        localStorage.setItem('magento_custom_rates', JSON.stringify(updated));
      } catch (e) {}
      return updated;
    });
  };

  const refreshLiveRates = async () => {
    setIsLoadingRates(true);
    try {
      // Fetch actual market rates via open currency exchange API with fallback
      const res = await fetch('https://open.er-api.com/v6/latest/AED');
      if (res.ok) {
        const data = await res.json();
        if (data && data.rates) {
          const liveUsd = Number(data.rates.USD) || 0.272294;
          const liveInr = Number(data.rates.INR) || 23.152;
          const newRatesObj: ExchangeRates = {
            base: 'AED',
            rates: {
              AED: 1.0,
              USD: liveUsd,
              INR: liveInr,
            },
            lastUpdated: new Date().toISOString(),
            source: 'live',
          };
          setRates(newRatesObj);
          localStorage.setItem('magento_custom_rates', JSON.stringify(newRatesObj));
        }
      }
    } catch (err) {
      console.warn('Failed to fetch live FX rates, using standard pegged rates:', err);
    } finally {
      setIsLoadingRates(false);
    }
  };

  const formatPrice = (amountInAed: number) => {
    return formatCurrency(amountInAed, currency, rates);
  };

  const convertPrice = (amountInAed: number) => {
    return convertFromAed(amountInAed, currency, rates);
  };

  const formatBnpl = (amountInAed: number, installments: 3 | 4 = 4) => {
    return calculateBnplInstallment(amountInAed, installments, currency, rates);
  };

  return (
    <CurrencyContext.Provider
      value={{
        currency,
        setCurrency,
        rates,
        updateRates,
        formatPrice,
        convertPrice,
        formatBnpl,
        refreshLiveRates,
        isLoadingRates,
      }}
    >
      {children}
    </CurrencyContext.Provider>
  );
};

export const useCurrency = () => {
  const context = useContext(CurrencyContext);
  if (!context) {
    throw new Error('useCurrency must be used within a CurrencyProvider');
  }
  return context;
};
