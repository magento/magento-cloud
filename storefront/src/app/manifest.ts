import { MetadataRoute } from 'next';

export default function manifest(): MetadataRoute.Manifest {
  return {
    name: 'Al-Madina Luxury & Tech Hub',
    short_name: 'Al-Madina Hub',
    description: 'UAE Premier Headless Magento Commerce & PWA App',
    start_url: '/',
    display: 'standalone',
    background_color: '#0b0f19',
    theme_color: '#0284c7',
    icons: [
      {
        src: '/icon-192.png',
        sizes: '192x192',
        type: 'image/png',
      },
      {
        src: '/icon-512.png',
        sizes: '512x512',
        type: 'image/png',
      },
    ],
  };
}
