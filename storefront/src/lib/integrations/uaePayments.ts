import { BankAccountDetails, PaymentGatewaySettings, PaymentMethodType } from '../types/commerce';

export const DEFAULT_UAE_BANK_ACCOUNTS: BankAccountDetails[] = [
  {
    bankName: 'Emirates NBD (Main Corporate Branch, Dubai)',
    accountTitle: 'Magento Commerce UAE LLC',
    accountNumber: '102938475601',
    iban: 'AE07033102938475601001',
    swiftBic: 'EBILAEADXXX',
    branchName: 'Dubai Internet City Branch',
    routingCode: '033',
  },
  {
    bankName: 'ADCB (Abu Dhabi Commercial Bank)',
    accountTitle: 'Magento Commerce UAE LLC',
    accountNumber: '492019283741',
    iban: 'AE52003492019283741001',
    swiftBic: 'ADCBAEAAXXX',
    branchName: 'Al Salam Street, Abu Dhabi',
    routingCode: '003',
  },
  {
    bankName: 'Wio Bank UAE (Digital Business Banking)',
    accountTitle: 'Magento Commerce UAE LLC',
    accountNumber: '990012345678',
    iban: 'AE18088990012345678001',
    swiftBic: 'WIOBAEAAXXX',
    branchName: 'Dubai Digital Hub',
    routingCode: '088',
  },
];

export const DEFAULT_PAYMENT_SETTINGS: PaymentGatewaySettings = {
  stripe: {
    enabled: true,
    publishableKey: 'pk_live_51MgtUAE_sample_key',
    secretKeyMasked: 'sk_live_••••••••••••••••••••••••9482',
    testMode: true,
  },
  tabby: {
    enabled: true,
    publicKey: 'pk_test_tabby_ae_demo',
    merchantCode: 'MAGENTO_CLOUD_AE',
    installments: 4,
  },
  tamara: {
    enabled: true,
    apiTokenMasked: 'tamara_sec_••••••••••••••••••••3819',
    installments: 3,
  },
  bankTransfer: {
    enabled: true,
    accounts: DEFAULT_UAE_BANK_ACCOUNTS,
    instructions:
      'Please transfer the exact order amount to any of our UAE corporate bank accounts listed below. Always mention your Unique Order Reference Code in the transfer remarks/description. Upload your transfer receipt to expedite warehouse dispatch.',
  },
  cod: {
    enabled: true,
    feeAed: 15,
    allowEmirates: ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'],
  },
};

/**
 * Generates a unique UAE Central Bank FTS (Funds Transfer System) compatible Reference Code.
 */
export function generateBankTransferReference(orderNumber: string): string {
  const cleanNum = orderNumber.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
  const randomSuffix = Math.floor(1000 + Math.random() * 9000);
  return `FTS-${cleanNum}-${randomSuffix}`;
}

export function getPaymentMethodLabel(method: PaymentMethodType): string {
  switch (method) {
    case 'stripe':
      return 'Credit / Debit Card (Stripe UAE & Apple Pay)';
    case 'tabby':
      return 'Tabby - Split in 4 Interest-Free Payments';
    case 'tamara':
      return 'Tamara - Pay in 3 Interest-Free Installments';
    case 'bank_transfer':
      return 'Direct UAE Bank Transfer (IBAN / Central Bank FTS)';
    case 'cod':
      return 'Cash on Delivery (UAE Emirates)';
    default:
      return 'Online Payment';
  }
}
