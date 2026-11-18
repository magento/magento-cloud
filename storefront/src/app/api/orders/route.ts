import { NextResponse } from 'next/server';
import { requireAdminApi } from '@/lib/auth-helpers';
import { getOrders, getProducts, upsertOrder, upsertProduct } from '@/lib/db/store-repo';
import type { Order } from '@/lib/types/commerce';

export const dynamic = 'force-dynamic';

// Admin reads the full order list.
export async function GET() {
  try {
    await requireAdminApi();
  } catch (res) {
    return res as Response;
  }
  const orders = await getOrders();
  return NextResponse.json({ orders });
}

// Public: place an order (checkout). Persists the order and decrements stock.
export async function POST(req: Request) {
  const body = await req.json();
  const order: Order | undefined = body?.order;
  if (!order?.id) return NextResponse.json({ error: 'Invalid order payload' }, { status: 400 });

  await upsertOrder(order);

  // Decrement stock for purchased items from the authoritative catalog.
  const products = await getProducts();
  for (const item of order.items) {
    const product = products.find((p) => p.id === item.productId);
    if (!product) continue;
    const nextStock = Math.max(0, (product.stock || 0) - item.quantity);
    await upsertProduct({ ...product, stock: nextStock, updatedAt: new Date().toISOString() });
  }

  return NextResponse.json({ ok: true, order });
}

// Admin: update order status / tracking.
export async function PATCH(req: Request) {
  try {
    await requireAdminApi();
  } catch (res) {
    return res as Response;
  }

  const body = await req.json();
  const order: Order | undefined = body?.order;
  if (!order?.id) return NextResponse.json({ error: 'Invalid order payload' }, { status: 400 });

  await upsertOrder(order);
  return NextResponse.json({ ok: true, order });
}
