import { headers } from 'next/headers';
import { auth } from '@/lib/auth';

/**
 * Returns the current session or null. Any authenticated user is treated as the
 * store admin (single-tenant back office).
 */
export async function getSession() {
  return auth.api.getSession({ headers: await headers() });
}

/**
 * Throws if there is no authenticated admin. Use inside API route handlers that
 * mutate store data.
 */
export async function requireAdminApi() {
  const session = await getSession();
  if (!session?.user) {
    throw new Response(JSON.stringify({ error: 'Unauthorized' }), {
      status: 401,
      headers: { 'content-type': 'application/json' },
    });
  }
  return session.user;
}
