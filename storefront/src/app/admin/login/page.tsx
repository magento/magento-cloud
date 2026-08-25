import React from 'react';
import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth-helpers';
import { db } from '@/lib/db';
import { user } from '@/lib/db/schema';
import { AdminAuthForm } from '../../../components/admin/AdminAuthForm';

export const dynamic = 'force-dynamic';

export default async function AdminLoginPage() {
  const session = await getSession();
  if (session?.user) {
    redirect('/admin');
  }

  // If no admin account exists yet, this is first-run: show the create form.
  const existing = await db.select({ id: user.id }).from(user).limit(1);
  const isFirstRun = existing.length === 0;

  return <AdminAuthForm isFirstRun={isFirstRun} />;
}
