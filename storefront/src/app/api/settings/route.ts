import { NextResponse } from 'next/server';
import { requireAdminApi } from '@/lib/auth-helpers';
import { getSettings, saveSettings, type StoreSettingsData } from '@/lib/db/store-repo';

export const dynamic = 'force-dynamic';

export async function GET() {
  const settings = await getSettings();
  return NextResponse.json({ settings });
}

// Merge a partial settings update into the stored settings row. Admin only.
export async function PATCH(req: Request) {
  try {
    await requireAdminApi();
  } catch (res) {
    return res as Response;
  }

  const patch = (await req.json()) as Partial<StoreSettingsData>;
  const current = await getSettings();
  const next: StoreSettingsData = {
    storeIdentity: { ...current.storeIdentity, ...patch.storeIdentity },
    warehouseSettings: { ...current.warehouseSettings, ...patch.warehouseSettings },
    paymentSettings: { ...current.paymentSettings, ...patch.paymentSettings },
    odooSettings: { ...current.odooSettings, ...patch.odooSettings },
    amazonSettings: { ...current.amazonSettings, ...patch.amazonSettings },
  };
  await saveSettings(next);
  return NextResponse.json({ ok: true, settings: next });
}
