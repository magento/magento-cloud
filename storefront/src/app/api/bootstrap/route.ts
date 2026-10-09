import { NextResponse } from 'next/server';
import { ensureSeeded, getOrders, getProducts, getSettings } from '@/lib/db/store-repo';

export const dynamic = 'force-dynamic';

// Public endpoint. Returns everything the storefront needs to hydrate, and
// seeds the demo catalog + default settings on the very first request.
export async function GET() {
  try {
    await ensureSeeded();
    const [settings, products, orders] = await Promise.all([getSettings(), getProducts(), getOrders()]);
    return NextResponse.json({ settings, products, orders });
  } catch (err) {
    console.error('[bootstrap] failed', err);
    return NextResponse.json({ error: 'Failed to load store data' }, { status: 500 });
  }
}
