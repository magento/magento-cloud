import { createSerwistRoute } from '@serwist/turbopack';
import type { NextRequest } from 'next/server';

const route = createSerwistRoute({
  swSrc: 'src/app/sw.ts',
});

// We only serve sw.js here. 
export const GET = (req: NextRequest) => route.GET(req, { params: Promise.resolve({ path: 'sw.js' }) } as any);

export const dynamic = 'force-static';
export const revalidate = false;
