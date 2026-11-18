'use client';

import React, { useState } from 'react';
import {
  MessageSquare,
  X,
  Sparkles,
  Send,
  MessageCircle,
  Truck,
  CreditCard,
  Receipt,
  ExternalLink,
  Bot,
} from 'lucide-react';
import { useStore } from '../../context/StoreContext';
import { useCurrency } from '../../context/CurrencyContext';

interface ChatMessage {
  id: string;
  sender: 'assistant' | 'user';
  text: string;
  timestamp: string;
}

export const StoreAssistant: React.FC = () => {
  const { storeIdentity, products } = useStore();
  const { formatPrice } = useCurrency();
  const [isOpen, setIsOpen] = useState(false);
  const [activeTab, setActiveTab] = useState<'chat' | 'faqs' | 'delivery'>('chat');
  const [inputQuery, setInputQuery] = useState('');
  const [chatMessages, setChatMessages] = useState<ChatMessage[]>([
    {
      id: 'm-1',
      sender: 'assistant',
      text: `Marhaba! 👋 Welcome to ${storeIdentity.storeName}. I'm your UAE Store Concierge. How can I assist you with products, Tabby/Tamara installments, or Amazon delivery today?`,
      timestamp: 'Just now',
    },
  ]);

  const handleSendMessage = (textToSend?: string) => {
    const query = (textToSend || inputQuery).trim();
    if (!query) return;

    const userMsg: ChatMessage = {
      id: 'u-' + Date.now(),
      sender: 'user',
      text: query,
      timestamp: 'Just now',
    };

    setChatMessages((prev) => [...prev, userMsg]);
    if (!textToSend) setInputQuery('');

    // Generate intelligent assistant response
    setTimeout(() => {
      let reply = '';
      const lower = query.toLowerCase();

      if (lower.includes('tabby') || lower.includes('tamara') || lower.includes('installment') || lower.includes('bnpl')) {
        reply = `We support both **Tabby (Split in 4)** and **Tamara (Split in 3)** with zero interest and zero fees for all UAE residents with Emirates ID. You can select either method on the checkout page!`;
      } else if (lower.includes('amazon') || lower.includes('mcf') || lower.includes('delivery') || lower.includes('shipping')) {
        reply = `🚚 We provide **Amazon Logistics (FBA/MCF)** fulfillment across all 7 Emirates (Dubai, Abu Dhabi, Sharjah, etc.) as well as **Same-Day Dubai Express**. Orders above AED ${storeIdentity.freeShippingThresholdAed} receive FREE standard delivery!`;
      } else if (lower.includes('tax') || lower.includes('vat') || lower.includes('trn') || lower.includes('invoice')) {
        reply = `🧾 All orders include standard UAE 5% VAT compliant with Federal Tax Authority (FTA). Our TRN is **${storeIdentity.uaeTrn}**. You receive a downloadable electronic Tax Invoice with FTA QR verification code immediately upon checkout.`;
      } else if (lower.includes('bank') || lower.includes('iban') || lower.includes('transfer')) {
        reply = `💳 We accept direct UAE Central Bank transfers to our corporate accounts at **Emirates NBD**, **ADCB**, and **Wio Bank**. You will receive an automated FTS Reference Number at checkout.`;
      } else if (lower.includes('iphone') || lower.includes('apple') || lower.includes('phone')) {
        const iphone = products.find((p) => p.title.toLowerCase().includes('iphone'));
        reply = iphone
          ? `📱 We have the **${iphone.title}** in stock in our Dubai Central Warehouse for ${formatPrice(iphone.basePriceAed)}. Available with same-day dispatch and Tabby 4x installments!`
          : `We carry a full range of flagship smartphones with official UAE warranty. Check our Smartphones catalog!`;
      } else {
        reply = `Thank you for your question! I can help you check inventory in our online warehouse, explain payment methods (Stripe, Tabby, Tamara, IBAN), or connect you with our Dubai VIP WhatsApp concierge.`;
      }

      setChatMessages((prev) => [
        ...prev,
        {
          id: 'a-' + Date.now(),
          sender: 'assistant',
          text: reply,
          timestamp: 'Just now',
        },
      ]);
    }, 600);
  };

  return (
    <>
      {/* Floating Toggle Button */}
      <div style={{ position: 'fixed', bottom: '5.5rem', right: '1.5rem', zIndex: 40 }} className="md:bottom-6">
        <button
          onClick={() => setIsOpen(!isOpen)}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.6rem',
            background: 'linear-gradient(135deg, #0ea5e9, #0284c7)',
            color: '#fff',
            border: 'none',
            borderRadius: 'var(--radius-full)',
            padding: '0.75rem 1.25rem',
            fontWeight: 700,
            fontSize: '0.9rem',
            cursor: 'pointer',
            boxShadow: '0 8px 25px rgba(14,165,233,0.4)',
            transition: 'var(--transition-normal)',
          }}
          className="glow-hover"
        >
          {isOpen ? <X size={20} /> : <Bot size={20} />}
          <span style={{ display: 'none' }} className="sm:inline">
            {isOpen ? 'Close Assistant' : 'Store Concierge'}
          </span>
        </button>
      </div>

      {/* Assistant Modal Window */}
      {isOpen && (
        <div
          style={{
            position: 'fixed',
            bottom: '9.5rem',
            right: '1.5rem',
            width: '90vw',
            maxWidth: '380px',
            height: '520px',
            background: '#111827',
            border: '1px solid var(--border-hover)',
            borderRadius: '16px',
            boxShadow: '0 20px 40px rgba(0,0,0,0.6)',
            zIndex: 50,
            display: 'flex',
            flexDirection: 'column',
            overflow: 'hidden',
          }}
          className="animate-fade-in md:bottom-20"
        >
          {/* Header */}
          <div style={{ background: 'linear-gradient(135deg, #0f172a, #1e293b)', padding: '1rem', borderBottom: '1px solid var(--border-subtle)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
              <div style={{ width: '34px', height: '34px', borderRadius: '8px', background: 'linear-gradient(135deg, #0ea5e9, #0284c7)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <Sparkles size={18} color="#fff" />
              </div>
              <div>
                <div style={{ fontSize: '0.92rem', fontWeight: 700, color: '#fff' }}>UAE Store Concierge</div>
                <div style={{ fontSize: '0.72rem', color: '#10b981', display: 'flex', alignItems: 'center', gap: '0.3rem' }}>
                  <span style={{ width: '6px', height: '6px', borderRadius: '50%', background: '#10b981' }}></span>
                  Online • Dubai Hub
                </div>
              </div>
            </div>

            <button onClick={() => setIsOpen(false)} style={{ background: 'none', border: 'none', color: '#9ca3af', cursor: 'pointer' }}>
              <X size={18} />
            </button>
          </div>

          {/* Tab Navigation */}
          <div style={{ display: 'flex', background: '#0b0f19', borderBottom: '1px solid var(--border-subtle)', padding: '0.25rem' }}>
            <button
              onClick={() => setActiveTab('chat')}
              style={{
                flex: 1,
                padding: '0.45rem',
                border: 'none',
                background: activeTab === 'chat' ? '#1f2937' : 'transparent',
                color: activeTab === 'chat' ? 'var(--accent-cyan)' : '#9ca3af',
                fontSize: '0.75rem',
                fontWeight: 600,
                borderRadius: '6px',
                cursor: 'pointer',
              }}
            >
              AI Assistant
            </button>
            <button
              onClick={() => setActiveTab('faqs')}
              style={{
                flex: 1,
                padding: '0.45rem',
                border: 'none',
                background: activeTab === 'faqs' ? '#1f2937' : 'transparent',
                color: activeTab === 'faqs' ? 'var(--accent-cyan)' : '#9ca3af',
                fontSize: '0.75rem',
                fontWeight: 600,
                borderRadius: '6px',
                cursor: 'pointer',
              }}
            >
              Payment & VAT
            </button>
            <button
              onClick={() => setActiveTab('delivery')}
              style={{
                flex: 1,
                padding: '0.45rem',
                border: 'none',
                background: activeTab === 'delivery' ? '#1f2937' : 'transparent',
                color: activeTab === 'delivery' ? 'var(--accent-cyan)' : '#9ca3af',
                fontSize: '0.75rem',
                fontWeight: 600,
                borderRadius: '6px',
                cursor: 'pointer',
              }}
            >
              Amazon Logistics
            </button>
          </div>

          {/* Tab Content */}
          {activeTab === 'chat' && (
            <>
              {/* Message List */}
              <div style={{ flex: 1, overflowY: 'auto', padding: '0.85rem', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
                {chatMessages.map((m) => (
                  <div
                    key={m.id}
                    style={{
                      alignSelf: m.sender === 'user' ? 'flex-end' : 'flex-start',
                      maxWidth: '85%',
                      background: m.sender === 'user' ? '#0284c7' : '#1f2937',
                      color: '#fff',
                      padding: '0.65rem 0.85rem',
                      borderRadius: '12px',
                      fontSize: '0.82rem',
                      lineHeight: '1.45',
                    }}
                  >
                    {m.text}
                  </div>
                ))}
              </div>

              {/* Quick Prompt Chips */}
              <div style={{ padding: '0.4rem 0.85rem', display: 'flex', gap: '0.4rem', overflowX: 'auto', background: '#0f172a' }}>
                <button
                  onClick={() => handleSendMessage('How does Tabby 4x split work?')}
                  style={{ whiteSpace: 'nowrap', fontSize: '0.7rem', padding: '0.25rem 0.55rem', borderRadius: '999px', background: '#1e293b', color: '#cbd5e1', border: '1px solid var(--border-subtle)', cursor: 'pointer' }}
                >
                  💳 Tabby / Tamara
                </button>
                <button
                  onClick={() => handleSendMessage('How does Amazon MCF delivery work?')}
                  style={{ whiteSpace: 'nowrap', fontSize: '0.7rem', padding: '0.25rem 0.55rem', borderRadius: '999px', background: '#1e293b', color: '#cbd5e1', border: '1px solid var(--border-subtle)', cursor: 'pointer' }}
                >
                  🚚 Amazon Delivery
                </button>
                <button
                  onClick={() => handleSendMessage('UAE 5% VAT and Tax Invoices?')}
                  style={{ whiteSpace: 'nowrap', fontSize: '0.7rem', padding: '0.25rem 0.55rem', borderRadius: '999px', background: '#1e293b', color: '#cbd5e1', border: '1px solid var(--border-subtle)', cursor: 'pointer' }}
                >
                  🧾 5% VAT Invoice
                </button>
              </div>

              {/* Input Box */}
              <div style={{ padding: '0.65rem 0.85rem', borderTop: '1px solid var(--border-subtle)', background: '#111827', display: 'flex', gap: '0.5rem' }}>
                <input
                  type="text"
                  placeholder="Ask a question..."
                  value={inputQuery}
                  onChange={(e) => setInputQuery(e.target.value)}
                  onKeyDown={(e) => e.key === 'Enter' && handleSendMessage()}
                  style={{ flex: 1, background: '#1f2937', border: '1px solid var(--border-subtle)', borderRadius: '8px', color: '#fff', padding: '0.45rem 0.75rem', fontSize: '0.82rem', outline: 'none' }}
                />
                <button
                  onClick={() => handleSendMessage()}
                  style={{ background: 'var(--accent-cyan)', color: '#fff', border: 'none', borderRadius: '8px', width: '34px', height: '34px', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer' }}
                >
                  <Send size={15} />
                </button>
              </div>
            </>
          )}

          {activeTab === 'faqs' && (
            <div style={{ flex: 1, overflowY: 'auto', padding: '1rem', display: 'flex', flexDirection: 'column', gap: '0.85rem', fontSize: '0.82rem' }}>
              <div style={{ background: '#1f2937', padding: '0.75rem', borderRadius: '10px' }}>
                <div style={{ fontWeight: 700, color: '#fbbf24', display: 'flex', alignItems: 'center', gap: '0.4rem', marginBottom: '0.3rem' }}>
                  <CreditCard size={15} /> Tabby & Tamara Buy Now Pay Later
                </div>
                <p style={{ color: '#cbd5e1', fontSize: '0.78rem' }}>
                  Split your purchase in 4 (Tabby) or 3 (Tamara) equal payments. 0% interest, no paperwork, instant approval with UAE phone & Emirates ID.
                </p>
              </div>

              <div style={{ background: '#1f2937', padding: '0.75rem', borderRadius: '10px' }}>
                <div style={{ fontWeight: 700, color: 'var(--accent-cyan)', display: 'flex', alignItems: 'center', gap: '0.4rem', marginBottom: '0.3rem' }}>
                  <Receipt size={15} /> UAE Federal Tax Authority (FTA) 5% VAT
                </div>
                <p style={{ color: '#cbd5e1', fontSize: '0.78rem' }}>
                  All orders comply with UAE VAT Law. Tax Registration Number (TRN): <strong>{storeIdentity.uaeTrn}</strong>. Itemized invoices with QR code are instantly available.
                </p>
              </div>

              {/* WhatsApp Button */}
              <a
                href={`https://wa.me/${storeIdentity.whatsappNumber.replace(/[^0-9]/g, '')}?text=Hello,%20I%20have%20an%20inquiry%20on%20${encodeURIComponent(storeIdentity.storeName)}`}
                target="_blank"
                rel="noreferrer"
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '0.5rem',
                  background: '#10b981',
                  color: '#fff',
                  padding: '0.65rem',
                  borderRadius: '10px',
                  fontWeight: 700,
                  textDecoration: 'none',
                  marginTop: 'auto',
                }}
              >
                <MessageCircle size={18} />
                <span>Chat on WhatsApp VIP Concierge</span>
                <ExternalLink size={14} />
              </a>
            </div>
          )}

          {activeTab === 'delivery' && (
            <div style={{ flex: 1, overflowY: 'auto', padding: '1rem', display: 'flex', flexDirection: 'column', gap: '0.85rem', fontSize: '0.82rem' }}>
              <div style={{ background: '#1f2937', padding: '0.75rem', borderRadius: '10px' }}>
                <div style={{ fontWeight: 700, color: '#ff9900', display: 'flex', alignItems: 'center', gap: '0.4rem', marginBottom: '0.3rem' }}>
                  <Truck size={15} /> Amazon Logistics (MCF / FBA)
                </div>
                <p style={{ color: '#cbd5e1', fontSize: '0.78rem', lineHeight: '1.5' }}>
                  Orders are dispatched directly via Amazon UAE fulfilment centers. You receive real-time SMS delivery notifications and live Amazon driver tracking.
                </p>
              </div>

              <div style={{ background: '#1f2937', padding: '0.75rem', borderRadius: '10px' }}>
                <div style={{ fontWeight: 700, color: '#fff', marginBottom: '0.4rem' }}>Emirates Delivery Speeds:</div>
                <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.3rem', fontSize: '0.75rem', color: '#94a3b8' }}>
                  <li>📍 <strong>Dubai:</strong> Same-Day Express (within 4-6 hours)</li>
                  <li>📍 <strong>Abu Dhabi:</strong> Next-Day by 2:00 PM</li>
                  <li>📍 <strong>Sharjah & Northern Emirates:</strong> 1-2 Business Days</li>
                </ul>
              </div>
            </div>
          )}
        </div>
      )}
    </>
  );
};
