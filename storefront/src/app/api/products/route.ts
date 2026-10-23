import { NextResponse } from 'next/server';
import { requireAdminApi } from '@/lib/auth-helpers';
import { deleteProduct, getProducts, upsertProduct } from '@/lib/db/store-repo';
import type { Product } from '@/lib/types/commerce';

export const dynamic = 'force-dynamic';

export async function GET() {
  const products = await getProducts();
  return NextResponse.json({ products });
}

// Create or update one product, or a batch. Admin only.
export async function POST(req: Request) {
  try {
    await requireAdminApi();
  } catch (res) {
    return res as Response;
  }

  const body = await req.json();
  const items: Product[] = Array.isArray(body?.products) ? body.products : body?.product ? [body.product] : [];

  if (items.length === 0) {
    return NextResponse.json({ error: 'No product payload provided' }, { status: 400 });
  }

  for (const p of items) {
    if (!p?.id) return NextResponse.json({ error: 'Product missing id' }, { status: 400 });
    await upsertProduct(p);
  }

  return NextResponse.json({ ok: true, count: items.length });
}

export async function DELETE(req: Request) {
  try {
    await requireAdminApi();
  } catch (res) {
    return res as Response;
  }

  const { searchParams } = new URL(req.url);
  const id = searchParams.get('id');
  if (!id) return NextResponse.json({ error: 'Missing product id' }, { status: 400 });

  await deleteProduct(id);
  return NextResponse.json({ ok: true });
}
