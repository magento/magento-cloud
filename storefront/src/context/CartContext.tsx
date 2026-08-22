'use client';

import React, { createContext, useContext, useEffect, useState } from 'react';
import { CartItem, DiscountCoupon, Product, ProductVariant } from '../lib/types/commerce';
import { calculateUaeVat } from '../lib/integrations/uaeTax';
import { useStore } from './StoreContext';

interface CartContextType {
  cart: CartItem[];
  wishlist: string[]; // Product IDs
  isCartOpen: boolean;
  setIsCartOpen: (open: boolean) => void;
  addToCart: (product: Product, quantity?: number, variant?: ProductVariant) => void;
  updateQuantity: (itemId: string, quantity: number) => void;
  removeFromCart: (itemId: string) => void;
  clearCart: () => void;
  toggleWishlist: (productId: string) => void;
  isInWishlist: (productId: string) => boolean;
  appliedCoupon: DiscountCoupon | null;
  applyCoupon: (code: string) => { success: boolean; message: string };
  removeCoupon: () => void;
  subtotalAed: number;
  discountAed: number;
  vatAed: number;
  totalAed: number;
  totalItemCount: number;
  freeShippingThresholdAed: number;
  freeShippingProgress: number; // 0 to 100%
  amountNeededForFreeShippingAed: number;
}

const AVAILABLE_COUPONS: DiscountCoupon[] = [
  {
    code: 'DUBAI10',
    type: 'percentage',
    value: 10,
    minSpendAed: 100,
    description: '10% Welcome Discount for Dubai & UAE shoppers',
  },
  {
    code: 'RAMADAN50',
    type: 'fixed_aed',
    value: 50,
    minSpendAed: 500,
    description: 'AED 50 OFF on orders above AED 500',
  },
  {
    code: 'VIPUAE',
    type: 'percentage',
    value: 15,
    minSpendAed: 1000,
    description: '15% VIP UAE Shopper Discount',
  },
];

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { storeIdentity } = useStore();
  const [cart, setCart] = useState<CartItem[]>([]);
  const [wishlist, setWishlist] = useState<string[]>([]);
  const [isCartOpen, setIsCartOpen] = useState<boolean>(false);
  const [appliedCoupon, setAppliedCoupon] = useState<DiscountCoupon | null>(null);

  // Load from localStorage
  useEffect(() => {
    try {
      const savedCart = localStorage.getItem('magento_cart');
      if (savedCart) setCart(JSON.parse(savedCart));

      const savedWishlist = localStorage.getItem('magento_wishlist');
      if (savedWishlist) setWishlist(JSON.parse(savedWishlist));
    } catch (e) {}
  }, []);

  const saveCartToStorage = (newCart: CartItem[]) => {
    try {
      localStorage.setItem('magento_cart', JSON.stringify(newCart));
    } catch (e) {}
  };

  const saveWishlistToStorage = (newWishlist: string[]) => {
    try {
      localStorage.setItem('magento_wishlist', JSON.stringify(newWishlist));
    } catch (e) {}
  };

  const addToCart = (product: Product, quantity = 1, variant?: ProductVariant) => {
    const unitPriceAed = product.basePriceAed + (variant ? variant.priceAdjustmentAed : 0);
    const cartItemId = variant ? `${product.id}-${variant.id}` : product.id;

    setCart((prev) => {
      const existingIdx = prev.findIndex((item) => item.id === cartItemId);
      let updated: CartItem[];

      if (existingIdx > -1) {
        updated = [...prev];
        updated[existingIdx].quantity += quantity;
      } else {
        updated = [
          ...prev,
          {
            id: cartItemId,
            product,
            selectedVariant: variant,
            quantity,
            unitPriceAed,
          },
        ];
      }
      saveCartToStorage(updated);
      return updated;
    });

    setIsCartOpen(true);
  };

  const updateQuantity = (itemId: string, quantity: number) => {
    if (quantity <= 0) {
      removeFromCart(itemId);
      return;
    }
    setCart((prev) => {
      const updated = prev.map((item) => (item.id === itemId ? { ...item, quantity } : item));
      saveCartToStorage(updated);
      return updated;
    });
  };

  const removeFromCart = (itemId: string) => {
    setCart((prev) => {
      const updated = prev.filter((item) => item.id !== itemId);
      saveCartToStorage(updated);
      return updated;
    });
  };

  const clearCart = () => {
    setCart([]);
    setAppliedCoupon(null);
    try {
      localStorage.removeItem('magento_cart');
    } catch (e) {}
  };

  const toggleWishlist = (productId: string) => {
    setWishlist((prev) => {
      const exists = prev.includes(productId);
      const updated = exists ? prev.filter((id) => id !== productId) : [...prev, productId];
      saveWishlistToStorage(updated);
      return updated;
    });
  };

  const isInWishlist = (productId: string) => wishlist.includes(productId);

  const applyCoupon = (code: string): { success: boolean; message: string } => {
    const clean = code.trim().toUpperCase();
    const found = AVAILABLE_COUPONS.find((c) => c.code === clean);

    if (!found) {
      return { success: false, message: 'Invalid promo code. Try DUBAI10 or RAMADAN50.' };
    }

    if (found.minSpendAed && subtotalAed < found.minSpendAed) {
      return {
        success: false,
        message: `Minimum spend of AED ${found.minSpendAed} required for coupon ${clean}.`,
      };
    }

    setAppliedCoupon(found);
    return { success: true, message: `Coupon ${clean} applied: ${found.description}` };
  };

  const removeCoupon = () => {
    setAppliedCoupon(null);
  };

  // Calculations
  const subtotalAed = cart.reduce((sum, item) => sum + item.unitPriceAed * item.quantity, 0);

  let discountAed = 0;
  if (appliedCoupon) {
    if (appliedCoupon.type === 'percentage') {
      discountAed = (subtotalAed * appliedCoupon.value) / 100;
    } else {
      discountAed = Math.min(subtotalAed, appliedCoupon.value);
    }
  }

  const tax = calculateUaeVat(subtotalAed, discountAed, 0);
  const totalAed = tax.grandTotalAed;
  const vatAed = tax.vatAmountAed;
  const totalItemCount = cart.reduce((sum, item) => sum + item.quantity, 0);

  const freeShippingThresholdAed = storeIdentity.freeShippingThresholdAed || 250;
  const freeShippingProgress = Math.min(100, Math.round((subtotalAed / freeShippingThresholdAed) * 100));
  const amountNeededForFreeShippingAed = Math.max(0, freeShippingThresholdAed - subtotalAed);

  return (
    <CartContext.Provider
      value={{
        cart,
        wishlist,
        isCartOpen,
        setIsCartOpen,
        addToCart,
        updateQuantity,
        removeFromCart,
        clearCart,
        toggleWishlist,
        isInWishlist,
        appliedCoupon,
        applyCoupon,
        removeCoupon,
        subtotalAed,
        discountAed,
        vatAed,
        totalAed,
        totalItemCount,
        freeShippingThresholdAed,
        freeShippingProgress,
        amountNeededForFreeShippingAed,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};
