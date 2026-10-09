const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const publicDir = path.join(__dirname, '..', 'public');
if (!fs.existsSync(publicDir)) {
  fs.mkdirSync(publicDir, { recursive: true });
}

// Crisp Luxury Emblem SVG
const svgIcon = `
<svg width="512" height="512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0b0f19"/>
      <stop offset="50%" stop-color="#111827"/>
      <stop offset="100%" stop-color="#070a12"/>
    </linearGradient>
    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#fbbf24"/>
      <stop offset="50%" stop-color="#f59e0b"/>
      <stop offset="100%" stop-color="#d97706"/>
    </linearGradient>
    <linearGradient id="cyanGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#38bdf8"/>
      <stop offset="100%" stop-color="#0284c7"/>
    </linearGradient>
    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="12" result="blur"/>
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
  </defs>

  <!-- Background with subtle border -->
  <rect width="512" height="512" rx="110" fill="url(#bgGrad)"/>
  <rect x="6" y="6" width="500" height="500" rx="104" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="6"/>
  <rect x="20" y="20" width="472" height="472" rx="90" fill="none" stroke="url(#cyanGrad)" stroke-width="2" stroke-opacity="0.3"/>

  <!-- Glowing background aura -->
  <circle cx="256" cy="240" r="140" fill="#0284c7" opacity="0.18" filter="url(#glow)"/>
  <circle cx="256" cy="280" r="120" fill="#f59e0b" opacity="0.12" filter="url(#glow)"/>

  <!-- Modern Geometric Falcon / Crystal Emblem (Al-Madina) -->
  <g transform="translate(0, -10)">
    <!-- Outer Shield / Diamond -->
    <path d="M 256 100 L 370 200 L 256 390 L 142 200 Z" fill="none" stroke="url(#cyanGrad)" stroke-width="10" stroke-linejoin="round"/>
    
    <!-- Central Tech Crown / Crystal Wings -->
    <path d="M 256 140 L 330 210 L 256 340 L 182 210 Z" fill="url(#goldGrad)" opacity="0.9"/>
    
    <!-- Inner Accent Cuts -->
    <polygon points="256,155 275,215 256,310 237,215" fill="#ffffff" opacity="0.9"/>
    <polygon points="256,220 310,215 256,320" fill="url(#cyanGrad)" opacity="0.8"/>
    <polygon points="256,220 202,215 256,320" fill="url(#goldGrad)" opacity="0.7"/>

    <!-- Dynamic Tech Dots -->
    <circle cx="256" cy="100" r="10" fill="#38bdf8"/>
    <circle cx="370" cy="200" r="8" fill="#fbbf24"/>
    <circle cx="142" cy="200" r="8" fill="#fbbf24"/>
    <circle cx="256" cy="390" r="10" fill="#38bdf8"/>
  </g>

  <!-- Typography: AL-MADINA -->
  <text x="256" y="440" font-family="-apple-system, system-ui, 'Outfit', 'Plus Jakarta Sans', sans-serif" font-size="34" font-weight="900" letter-spacing="6" fill="#f9fafb" text-anchor="middle">
    AL-MADINA
  </text>
  <text x="256" y="470" font-family="-apple-system, system-ui, 'Outfit', 'Plus Jakarta Sans', sans-serif" font-size="16" font-weight="700" letter-spacing="8" fill="#38bdf8" text-anchor="middle">
    DUBAI HUB
  </text>
</svg>
`;

async function generate() {
  const svgBuffer = Buffer.from(svgIcon);

  // 512x512
  await sharp(svgBuffer)
    .resize(512, 512)
    .png()
    .toFile(path.join(publicDir, 'icon-512.png'));
  console.log('Created icon-512.png');

  // 192x192
  await sharp(svgBuffer)
    .resize(192, 192)
    .png()
    .toFile(path.join(publicDir, 'icon-192.png'));
  console.log('Created icon-192.png');

  // 180x180 Apple Touch Icon
  await sharp(svgBuffer)
    .resize(180, 180)
    .png()
    .toFile(path.join(publicDir, 'apple-touch-icon.png'));
  console.log('Created apple-touch-icon.png');

  // 64x64 Favicon
  await sharp(svgBuffer)
    .resize(64, 64)
    .png()
    .toFile(path.join(publicDir, 'favicon.png'));
  console.log('Created favicon.png');
}

generate().catch(console.error);
