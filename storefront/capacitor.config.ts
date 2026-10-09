import type { CapacitorConfig } from '@capacitor/cli';

/**
 * The native iOS/Android apps run in "remote server" mode: instead of bundling
 * a static export, the WebView loads the deployed Next.js server. This keeps
 * all server-side features (API routes, database, admin auth) working inside
 * the native shell.
 *
 * Set CAP_SERVER_URL to your deployed Vercel URL before running the mobile
 * build scripts, e.g.:
 *   CAP_SERVER_URL="https://your-app.vercel.app" npm run mobile:sync
 */
const serverUrl = process.env.CAP_SERVER_URL;

const config: CapacitorConfig = {
  appId: 'com.almadina.storefront',
  appName: 'Al-Madina Hub',
  // A lightweight offline fallback shell shipped inside the app bundle. When
  // CAP_SERVER_URL is set, the WebView loads the live server instead.
  webDir: 'mobile-shell',
  server: {
    androidScheme: 'https',
    cleartext: true,
    ...(serverUrl ? { url: serverUrl } : {}),
  },
  plugins: {
    SplashScreen: {
      launchShowDuration: 2000,
      launchAutoHide: true,
      backgroundColor: '#0b0f19',
      androidSplashResourceName: 'splash',
      androidScaleType: 'CENTER_CROP',
      showSpinner: false,
      splashFullScreen: true,
      splashImmersive: true,
    },
    StatusBar: {
      style: 'DARK',
      backgroundColor: '#0b0f19',
      overlaysWebView: false,
    },
  },
};

export default config;
