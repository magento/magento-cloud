import React, { Suspense } from 'react';
import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth-helpers';
import { AdminDashboard } from '../../components/admin/AdminDashboard';
import { AdminTopBar } from '../../components/admin/AdminTopBar';

export const dynamic = 'force-dynamic';

export default async function AdminPage() {
  const session = await getSession();
  if (!session?.user) {
    redirect('/admin/login');
  }

  return (
    <>
      <AdminTopBar email={session.user.email} />
      <Suspense
        fallback={
          <div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center', color: '#fff' }}>
            Loading Admin Center...
          </div>
        }
      >
        <AdminDashboard />
      </Suspense>
    </>
  );
}
