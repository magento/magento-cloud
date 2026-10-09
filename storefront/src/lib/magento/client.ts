import { INITIAL_PRODUCTS } from './mockData';
import { Product } from '../types/commerce';

const GRAPHQL_ENDPOINT = process.env.NEXT_PUBLIC_MAGENTO_GRAPHQL_URL || '';

export async function fetchMagentoGraphQL<T = any>(
  query: string,
  variables: Record<string, any> = {},
  customHeaders: Record<string, string> = {}
): Promise<{ data: T | null; error: Error | null; isMock: boolean }> {
  if (!GRAPHQL_ENDPOINT) {
    return { data: null, error: null, isMock: true };
  }

  try {
    const res = await fetch(GRAPHQL_ENDPOINT, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        ...customHeaders,
      },
      body: JSON.stringify({ query, variables }),
      next: { revalidate: 60 },
    });

    if (!res.ok) {
      throw new Error(`Magento GraphQL responded with status ${res.status}`);
    }

    const json = await res.json();
    if (json.errors && json.errors.length > 0) {
      throw new Error(json.errors[0].message || 'GraphQL execution failed');
    }

    return { data: json.data as T, error: null, isMock: false };
  } catch (err: any) {
    console.warn('Magento GraphQL request failed, falling back to local catalog:', err.message);
    return { data: null, error: err, isMock: true };
  }
}

export function getFallbackProducts(): Product[] {
  return INITIAL_PRODUCTS;
}
