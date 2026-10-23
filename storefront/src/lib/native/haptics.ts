import { Haptics, ImpactStyle, NotificationType } from '@capacitor/haptics';

/**
 * Triggers light impact haptic feedback for button taps (Add to Cart, Tab switch, etc.)
 */
export async function triggerLightHaptic(): Promise<void> {
  try {
    await Haptics.impact({ style: ImpactStyle.Light });
  } catch {
    // Graceful fallback for non-native web browsers
  }
}

/**
 * Triggers medium impact haptic feedback
 */
export async function triggerMediumHaptic(): Promise<void> {
  try {
    await Haptics.impact({ style: ImpactStyle.Medium });
  } catch {
    // Fallback
  }
}

/**
 * Triggers success haptic feedback (Order Placed, Saved settings, etc.)
 */
export async function triggerSuccessHaptic(): Promise<void> {
  try {
    await Haptics.notification({ type: NotificationType.Success });
  } catch {
    // Fallback
  }
}

/**
 * Triggers error / warning haptic feedback
 */
export async function triggerWarningHaptic(): Promise<void> {
  try {
    await Haptics.notification({ type: NotificationType.Warning });
  } catch {
    // Fallback
  }
}
