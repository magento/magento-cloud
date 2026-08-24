import { INITIAL_PRODUCTS } from '../../../lib/magento/mockData';
import { ProductDetailView } from './ProductDetailView';

export function generateStaticParams() {
  return INITIAL_PRODUCTS.map((product) => ({
    slug: product.slug,
  }));
}

export default async function ProductDetailPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  return <ProductDetailView slug={slug} />;
}
