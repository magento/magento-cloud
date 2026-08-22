import React, { Suspense } from 'react';
import { AdminDashboard } from '../../components/admin/AdminDashboard';

export default function AdminPage() {
  return (
    <Suspense fallback={<div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center', color: '#fff' }}>Loading Admin Center...</div>}>
      <AdminDashboard />
    </Suspense>
  );
}
