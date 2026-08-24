import type { Metadata } from 'next';
import './globals.css';
import { StoreProvider } from '../context/StoreContext';
import { CurrencyProvider } from '../context/CurrencyContext';
import { CartProvider } from '../context/CartContext';
import { Header } from '../components/layout/Header';
import { Footer } from '../components/layout/Footer';
import { MobileNav } from '../components/layout/MobileNav';
import { CartDrawer } from '../components/cart/CartDrawer';
import { StoreAssistant } from '../components/assistant/StoreAssistant';
import { RegisterSW } from '../components/pwa/RegisterSW';

export const metadata: Metadata = {
  title: 'Al-Madina Luxury & Tech Hub • UAE Magento Commerce',
  description:
    'Premier Headless Magento eCommerce Store in Dubai & UAE. Fast Same-Day Dubai Delivery, Amazon Logistics MCF, 5% UAE VAT Invoices, Tabby & Tamara Installments, and Multi-Currency Pricing (AED, USD, INR).',
  keywords: 'Magento UAE, Dubai online shopping, Tabby, Tamara, Amazon.ae MCF, Odoo ERP, AED, Electronics Dubai',
  manifest: '/manifest.json',
  icons: {
    icon: '/favicon.png',
    apple: '/apple-touch-icon.png',
  },
  appleWebApp: {
    capable: true,
    statusBarStyle: 'default',
    title: 'Al-Madina',
  },
  formatDetection: {
    telephone: false,
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body>
        <StoreProvider>
          <CurrencyProvider>
            <CartProvider>
              <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
                <Header />
                <main style={{ flex: 1 }}>{children}</main>
                <Footer />
                <CartDrawer />
                <StoreAssistant />
                <MobileNav />
                <RegisterSW />
              </div>
            </CartProvider>
          </CurrencyProvider>
        </StoreProvider>
      </body>
    </html>
  );
}
