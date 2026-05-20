(function () {
  'use strict';

  const CSS_ID   = 'pms-navbar-styles';
  const SB_URL   = 'https://iharcxdakmyxjpqpcbnb.supabase.co';
  const SB_KEY   = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImloYXJjeGRha215eGpwcXBjYm5iIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Nzg0ODc0NDEsImV4cCI6MjA5NDA2MzQ0MX0.5BE8ckW-3g5mJmXyrDWO_cytfI-_JrMaV4LQip7pbvs';

  const NAV_ITEMS = [
    {
      id:   'nav-home',
      href: 'index.html',
      label: 'Home',
      icon: `<svg viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/><polyline points="9 21 9 12 15 12 15 21"/></svg>`,
    },
    {
      id:   'nav-records',
      href: 'list_record.html',
      label: 'Manage Record',
      icon: `<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="9" x2="9" y2="21"/></svg>`,
    },
    {
      id:   'nav-item',
      href: 'item_table.html',
      label: 'Item',
      icon: `<svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>`,
    },
    {
      id:   'nav-event',
      href: 'event_table.html',
      label: 'Event',
      icon: `<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
    },
    {
      id:   'nav-category',
      href: 'category.html',
      label: 'Category',
      icon: `<svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="12" x2="12" y2="18"/><line x1="9" y1="15" x2="15" y2="15"/></svg>`,
    },
    {
      id:   'nav-reports',
      href: 'report.html',
      label: 'Reports',
      icon: `<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>`,
    },
  ];

  const PAGE_MAP = {
    'index.html':        'nav-home',
    '':                  'nav-home',
    'list_record.html':  'nav-records',
    'item_table.html':   'nav-item',
    'event_table.html':  'nav-event',
    'category.html':     'nav-category',
    'report.html':       'nav-reports',
  };

  function ensureStyles() {
    if (document.getElementById(CSS_ID)) return;
    const css = `
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
  --pms-topbar-h: 72px;
  --pms-bg: #0f172a;
  --pms-surface: rgba(255, 255, 255, 0.05);
  --pms-surface-hover: rgba(255, 255, 255, 0.1);
  --pms-text: #f1f5f9;
  --pms-text-secondary: #94a3b8;
  --pms-accent: #6366f1;
  --pms-accent-light: #818cf8;
  --pms-accent-glow: rgba(99, 102, 241, 0.3);
  --pms-border: rgba(255, 255, 255, 0.08);
  --pms-font: 'Inter', -apple-system, sans-serif;
  --pms-ease: cubic-bezier(0.16, 1, 0.3, 1);
  --pms-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
  --pms-glass: rgba(15, 23, 42, 0.8);
}

*, *::before, *::after { box-sizing: border-box; }

.pms-topbar {
  position: fixed;
  top: 16px;
  left: 50%;
  transform: translateX(-50%);
  width: calc(100% - 40px);
  max-width: 1400px;
  height: var(--pms-topbar-h);
  background: var(--pms-glass);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border: 1px solid var(--pms-border);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  z-index: 1000;
  font-family: var(--pms-font);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  animation: navbarSlideDown 0.6s var(--pms-ease) both;
}

@keyframes navbarSlideDown {
  from { 
    transform: translateX(-50%) translateY(-120%);
    opacity: 0;
  }
  to { 
    transform: translateX(-50%) translateY(0);
    opacity: 1;
  }
}

.pms-topbar-left { 
  display: flex; 
  align-items: center; 
  gap: 16px;
  flex-shrink: 0;
}

.pms-logo-container {
  position: relative;
  width: 42px;
  height: 42px;
  border-radius: 12px;
  overflow: hidden;
  background: linear-gradient(135deg, var(--pms-accent), #a855f7);
  padding: 2px;
  animation: logoFadeIn 0.6s 0.15s var(--pms-ease) both;
}

.pms-logo-inner {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  overflow: hidden;
  background: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(10px);
}

.pms-logo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  display: block;
}

.pms-logo-glow {
  position: absolute;
  inset: -2px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--pms-accent), #a855f7, #ec4899);
  opacity: 0;
  transition: opacity 0.3s var(--pms-ease);
  z-index: -1;
  filter: blur(8px);
}

.pms-logo-container:hover .pms-logo-glow {
  opacity: 0.6;
}

@keyframes logoFadeIn {
  from { 
    transform: scale(0.5) rotate(-15deg); 
    opacity: 0; 
    filter: blur(10px);
  }
  to { 
    transform: scale(1) rotate(0deg); 
    opacity: 1;
    filter: blur(0);
  }
}

.pms-brand { 
  display: flex; 
  flex-direction: column; 
  line-height: 1.2; 
  animation: fadeRight 0.5s 0.25s var(--pms-ease) both;
}

@keyframes fadeRight {
  from { 
    transform: translateX(-20px); 
    opacity: 0; 
  }
  to { 
    transform: translateX(0); 
    opacity: 1; 
  }
}

.pms-brand-title {
  font-size: 15px; 
  font-weight: 700;
  color: var(--pms-text); 
  letter-spacing: -0.3px;
  background: linear-gradient(135deg, #f1f5f9, #cbd5e1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.pms-brand-sub {
  font-size: 10px; 
  color: var(--pms-text-secondary);
  font-weight: 400;
  letter-spacing: 0.2px;
}

.pms-topbar-center {
  display: flex; 
  align-items: center; 
  gap: 4px;
  background: var(--pms-surface);
  padding: 4px;
  border-radius: 14px;
  border: 1px solid var(--pms-border);
  animation: fadeDown 0.5s 0.35s var(--pms-ease) both;
  overflow-x: auto;
}

.pms-topbar-center::-webkit-scrollbar {
  height: 0;
}

@keyframes fadeDown {
  from { 
    transform: translateY(-20px); 
    opacity: 0; 
  }
  to { 
    transform: translateY(0); 
    opacity: 1; 
  }
}

.pms-nav-link {
  color: var(--pms-text-secondary); 
  text-decoration: none;
  padding: 8px 16px; 
  border-radius: 11px;
  font-size: 13px; 
  font-weight: 500;
  transition: all 0.3s var(--pms-ease); 
  white-space: nowrap;
  position: relative;
  display: flex;
  align-items: center;
  gap: 7px;
  letter-spacing: -0.1px;
  flex-shrink: 0;
}

.pms-nav-link svg {
  width: 16px; 
  height: 16px;
  stroke: currentColor; 
  stroke-width: 1.8;
  fill: none; 
  stroke-linecap: round; 
  stroke-linejoin: round;
  transition: all 0.3s var(--pms-ease);
}

.pms-nav-link:hover { 
  background: var(--pms-surface-hover); 
  color: var(--pms-text);
  transform: translateY(-1px);
}

.pms-nav-link.active {
  background: rgba(99, 102, 241, 0.15);
  color: var(--pms-accent-light);
  box-shadow: 0 0 20px var(--pms-accent-glow);
}

.pms-nav-link.active svg {
  stroke: var(--pms-accent-light);
  filter: drop-shadow(0 0 4px var(--pms-accent-glow));
}

.pms-topbar-right { 
  display: flex; 
  align-items: center; 
  gap: 12px;
  animation: fadeLeft 0.5s 0.45s var(--pms-ease) both;
  flex-shrink: 0;
}

@keyframes fadeLeft {
  from { 
    transform: translateX(20px); 
    opacity: 0; 
  }
  to { 
    transform: translateX(0); 
    opacity: 1; 
  }
}

/* Settings Button */
.pms-settings-btn {
  width: 38px; 
  height: 38px;
  border-radius: 12px;
  background: var(--pms-surface);
  border: 1px solid var(--pms-border);
  display: flex; 
  align-items: center; 
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s var(--pms-ease);
  text-decoration: none;
  position: relative;
  overflow: hidden;
}

.pms-settings-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--pms-accent), #a855f7);
  opacity: 0;
  transition: opacity 0.3s var(--pms-ease);
}

.pms-settings-btn:hover::before {
  opacity: 0.15;
}

.pms-settings-btn:hover {
  border-color: var(--pms-accent-light);
  transform: rotate(45deg);
}

.pms-settings-btn svg {
  width: 18px; 
  height: 18px;
  stroke: var(--pms-text-secondary); 
  stroke-width: 1.8;
  fill: none; 
  stroke-linecap: round; 
  stroke-linejoin: round;
  position: relative;
  z-index: 1;
  transition: stroke 0.3s var(--pms-ease);
}

.pms-settings-btn:hover svg {
  stroke: var(--pms-accent-light);
}

/* Profile Trigger */
.pms-profile-trigger {
  display: flex; 
  align-items: center; 
  gap: 10px;
  padding: 4px 16px 4px 4px; 
  background: var(--pms-surface);
  border-radius: 14px; 
  cursor: pointer;
  transition: all 0.3s var(--pms-ease);
  border: 1px solid var(--pms-border);
  position: relative;
  overflow: hidden;
}

.pms-profile-trigger::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--pms-accent), #a855f7);
  opacity: 0;
  transition: opacity 0.3s var(--pms-ease);
}

.pms-profile-trigger:hover::before {
  opacity: 0.1;
}

.pms-profile-trigger:hover { 
  border-color: var(--pms-accent-light);
  transform: translateY(-1px);
}

.pms-avatar {
  width: 36px; 
  height: 36px; 
  border-radius: 10px;
  background: linear-gradient(135deg, var(--pms-accent), #a855f7);
  border: 2px solid var(--pms-border);
  object-fit: cover; 
  flex-shrink: 0;
  display: flex; 
  align-items: center; 
  justify-content: center; 
  overflow: hidden;
  transition: all 0.3s var(--pms-ease);
  position: relative;
  z-index: 1;
}

.pms-profile-trigger:hover .pms-avatar { 
  border-color: var(--pms-accent-light); 
  box-shadow: 0 0 20px var(--pms-accent-glow);
}

.pms-avatar img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
  border-radius: 8px; 
}

.pms-avatar svg { 
  width: 18px; 
  height: 18px; 
  stroke: var(--pms-text-secondary); 
  stroke-width: 1.8; 
  fill: none; 
}

.pms-profile-info { 
  display: flex; 
  flex-direction: column;
  position: relative;
  z-index: 1;
}

.pms-profile-name {
  font-size: 13px; 
  font-weight: 600; 
  color: var(--pms-text); 
  white-space: nowrap;
  letter-spacing: -0.2px;
}

.pms-profile-fullname {
  font-size: 10px; 
  color: var(--pms-text-secondary); 
  white-space: nowrap;
  letter-spacing: 0.2px;
}

/* Dropdown */
.pms-dropdown {
  position: fixed;
  top: calc(16px + var(--pms-topbar-h) + 8px);
  right: calc((100vw - min(1400px, calc(100vw - 40px))) / 2 + 28px);
  background: var(--pms-glass);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  min-width: 220px;
  border-radius: 16px; 
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  overflow: hidden; 
  display: none; 
  z-index: 1001;
  border: 1px solid var(--pms-border);
  transform-origin: top right;
  opacity: 0; 
  transform: scale(0.95) translateY(-10px);
  transition: all 0.3s var(--pms-ease);
  pointer-events: none;
}

.pms-dropdown.show {
  display: block; 
  opacity: 1; 
  transform: scale(1) translateY(0); 
  pointer-events: all;
}

.pms-dropdown-item {
  display: flex; 
  align-items: center; 
  gap: 12px;
  padding: 12px 20px; 
  text-decoration: none; 
  color: var(--pms-text);
  transition: all 0.2s var(--pms-ease); 
  font-size: 13px;
  font-weight: 500;
  letter-spacing: -0.1px;
}

.pms-dropdown-item:hover { 
  background: var(--pms-surface-hover); 
  padding-left: 24px;
}

.pms-dropdown-item svg { 
  width: 18px; 
  height: 18px; 
  stroke: var(--pms-text-secondary); 
  stroke-width: 1.8; 
  fill: none; 
  transition: stroke 0.2s;
}

.pms-dropdown-item:hover svg { 
  stroke: var(--pms-accent-light); 
}

.pms-dropdown-item.danger { 
  color: #fca5a5; 
}

.pms-dropdown-item.danger svg { 
  stroke: #fca5a5; 
}

.pms-dropdown-item.danger:hover { 
  background: rgba(239, 68, 68, 0.1); 
}

.pms-dropdown-item.danger:hover svg { 
  stroke: #ef4444; 
}

.pms-dropdown-divider { 
  height: 1px; 
  background: var(--pms-border); 
  margin: 4px 0; 
}

.pms-content-shift {
  margin-top: calc(var(--pms-topbar-h) + 32px);
  min-height: calc(100vh - var(--pms-topbar-h) - 32px);
  animation: contentShiftIn 0.5s 0.55s var(--pms-ease) both;
}

@keyframes contentShiftIn {
  from { 
    opacity: 0;
    transform: translateY(20px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

/* Media Queries */
@media (max-width: 900px) {
  .pms-topbar { 
    padding: 0 16px;
    width: calc(100% - 24px);
    height: auto;
    flex-wrap: wrap;
    padding: 12px;
    gap: 12px;
  }
  
  .pms-topbar-left, 
  .pms-topbar-center, 
  .pms-topbar-right { 
    margin: 0;
  }
  
  .pms-topbar-center { 
    order: 3; 
    width: 100%; 
    justify-content: flex-start; 
    overflow-x: auto; 
    padding: 4px;
    -webkit-overflow-scrolling: touch;
  }
  
  .pms-topbar-center::-webkit-scrollbar {
    height: 0;
  }
  
  .pms-nav-link { 
    padding: 6px 12px; 
    font-size: 12px;
    flex-shrink: 0;
  }
  
  .pms-brand-sub { 
    display: none; 
  }
  
  .pms-profile-fullname { 
    display: none; 
  }
  
  .pms-profile-trigger { 
    padding: 3px 12px 3px 3px; 
  }
  
  .pms-avatar { 
    width: 32px; 
    height: 32px; 
    border-radius: 8px;
  }
  
  .pms-content-shift { 
    margin-top: 120px;
  }
}

@media (max-width: 600px) {
  .pms-topbar {
    width: calc(100% - 16px);
    border-radius: 16px;
    padding: 10px;
  }
  
  .pms-brand-title { 
    font-size: 13px; 
  }
  
  .pms-logo-container { 
    width: 36px; 
    height: 36px;
    border-radius: 10px;
  }
  
  .pms-logo-inner {
    border-radius: 8px;
  }
  
  .pms-nav-link { 
    padding: 5px 10px; 
    font-size: 11px; 
    border-radius: 8px;
  }
  
  .pms-nav-link svg { 
    width: 14px; 
    height: 14px; 
  }
  
  .pms-settings-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
  }
  
  .pms-profile-trigger {
    border-radius: 10px;
    padding: 2px 10px 2px 2px;
  }
}
`;
    const style = document.createElement('style');
    style.id = CSS_ID;
    style.textContent = css;
    document.head.appendChild(style);
  }

  const ICON = {
    user:   `<svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>`,
    logout: `<svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>`,
    profile: `<svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
    settings: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>`,
    chevron: `<svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>`,
  };

  function buildNavHTML(userFullname, profileImage) {
    const avatarHtml = profileImage && profileImage !== 'null'
      ? `<img src="${profileImage}" alt="Profile" onerror="this.parentElement.innerHTML='${ICON.user.replace(/'/g,'&#39;')}'" />`
      : ICON.user;

    const navItemsHTML = NAV_ITEMS.map(item => `
      <a href="${item.href}" class="pms-nav-link" id="${item.id}">
        ${item.icon}
        ${item.label}
      </a>
    `).join('');

    return `
<header class="pms-topbar" role="banner">
  <div class="pms-topbar-left">
    <div class="pms-logo-container">
      <div class="pms-logo-glow"></div>
      <div class="pms-logo-inner">
        <img src="image/bepo.png" alt="Logo" class="pms-logo" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%236366f1%22><path d=%22M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5%22/></svg>'" />
      </div>
    </div>
    <div class="pms-brand">
      <div class="pms-brand-title">Procurement Data System</div>
      <div class="pms-brand-sub">Bohol Employment Placement Office &amp; Public Employment Service Office</div>
    </div>
  </div>
  
  <div class="pms-topbar-center">
    ${navItemsHTML}
  </div>
  
  <div class="pms-topbar-right">
    <a href="setting.html" class="pms-settings-btn" title="Settings">
      ${ICON.settings}
    </a>
    <div class="pms-profile-trigger" id="pms-profile-trigger">
      <div class="pms-avatar" id="pms-avatar">${avatarHtml}</div>
      <div class="pms-profile-info">
        <div class="pms-profile-name" id="pms-profile-name">${userFullname || 'User'}</div>
        <div class="pms-profile-fullname" id="pms-profile-fullname">Employee</div>
      </div>
    </div>
    
    <div class="pms-dropdown" id="pms-dropdown">
      <a href="view.html" class="pms-dropdown-item" id="dropdown-profile">
        ${ICON.profile}
        <span>View Profile</span>
      </a>
      <div class="pms-dropdown-divider"></div>
      <a href="logout.html" class="pms-dropdown-item danger" id="dropdown-logout">
        ${ICON.logout}
        <span>Logout</span>
      </a>
    </div>
  </div>
</header>`;
  }

  function markActive() {
    const page = window.location.pathname.split('/').pop().toLowerCase() || 'index.html';
    const id = PAGE_MAP[page];
    if (id) {
      const el = document.getElementById(id);
      if (el) el.classList.add('active');
    }
  }

  function attachDropdownBehavior() {
    const trigger = document.getElementById('pms-profile-trigger');
    const dropdown = document.getElementById('pms-dropdown');
    if (!trigger || !dropdown) return;
    
    function toggleDropdown(e) { 
      e.stopPropagation(); 
      dropdown.classList.toggle('show'); 
    }
    
    trigger.addEventListener('click', toggleDropdown);
    
    document.addEventListener('click', function(e) {
      if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }
    });
  }

  function shiftContent() {
    const selectors = ['.app-shell', '#app-shell', '.main-content', '#main-content', '.main', '#main', 'main', '.content', '#content', '.dashboard', '.page-content', '#page-content'];
    for (const sel of selectors) {
      const el = document.querySelector(sel);
      if (el) { 
        el.classList.add('pms-content-shift'); 
        return; 
      }
    }
    document.body.style.paddingTop = 'calc(var(--pms-topbar-h, 72px) + 32px)';
  }

  async function fetchProfile() {
    let userId = sessionStorage.getItem('bepo_user_id') || localStorage.getItem('user_id');
    if (!userId) {
      const supabaseSession = localStorage.getItem('supabase_session');
      if (supabaseSession) {
        try { const session = JSON.parse(supabaseSession); userId = session.user?.id; } catch(e) {}
      }
    }
    if (!userId) return null;
    
    try {
      let response = await fetch(`${SB_URL}/rest/v1/members?user_id=eq.${userId}&select=fullname,nickname,profile_image,id_number`, {
        headers: { 'apikey': SB_KEY, 'Authorization': `Bearer ${SB_KEY}`, 'Content-Type': 'application/json' }
      });
      
      if (!response.ok) {
        response = await fetch(`${SB_URL}/rest/v1/members?id=eq.${userId}&select=fullname,nickname,profile_image,id_number`, {
          headers: { 'apikey': SB_KEY, 'Authorization': `Bearer ${SB_KEY}`, 'Content-Type': 'application/json' }
        });
        if (!response.ok) return null;
      }
      
      const data = await response.json();
      return data && data.length > 0 ? data[0] : null;
    } catch (e) { return null; }
  }

  function updateProfileUI(profile) {
    if (!profile) return;
    const displayName = profile.nickname || profile.fullname || sessionStorage.getItem('bepo_user_name') || 'User';
    const profileImage = profile.profile_image || null;
    
    const nameEl = document.getElementById('pms-profile-name');
    if (nameEl) nameEl.textContent = displayName;
    
    const fullnameEl = document.getElementById('pms-profile-fullname');
    if (fullnameEl && profile.id_number) fullnameEl.textContent = profile.id_number;
    else if (fullnameEl && profile.fullname) fullnameEl.textContent = profile.fullname.split(' ')[0];
    
    const avatarContainer = document.getElementById('pms-avatar');
    if (avatarContainer && profileImage && profileImage !== 'null') {
      avatarContainer.innerHTML = `<img src="${profileImage}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" />`;
    }
    
    sessionStorage.setItem('bepo_user_name', displayName);
    if (profile.fullname) sessionStorage.setItem('bepo_user_fullname', profile.fullname);
    if (profile.id_number) sessionStorage.setItem('bepo_id_number', profile.id_number);
  }

  function getDefaultName() {
    return sessionStorage.getItem('bepo_user_name') || sessionStorage.getItem('bepo_user_fullname') || 'User';
  }

  window.renderPMSNavbar = async function (options) {
    ensureStyles();
    if (document.querySelector('.pms-topbar')) return;
    
    const defaultName = getDefaultName();
    const container = (options && options.containerSelector) ? document.querySelector(options.containerSelector) : document.body;
    if (!container) return;
    
    const tmp = document.createElement('div');
    tmp.innerHTML = buildNavHTML(defaultName, null);
    
    Array.from(tmp.children).forEach(el => {
      if (container === document.body) { document.body.insertBefore(el, document.body.firstChild); }
      else { container.appendChild(el); }
    });
    
    shiftContent();
    markActive();
    attachDropdownBehavior();
    
    fetchProfile().then(profile => { if (profile) updateProfileUI(profile); });
  };

  window.updateNavbarProfile = function(fullname, profileImage, idNumber) {
    const nameEl = document.getElementById('pms-profile-name');
    if (nameEl && fullname) nameEl.textContent = fullname;
    const fullnameEl = document.getElementById('pms-profile-fullname');
    if (fullnameEl && idNumber) fullnameEl.textContent = idNumber;
    const avatarContainer = document.getElementById('pms-avatar');
    if (avatarContainer && profileImage && profileImage !== 'null') {
      avatarContainer.innerHTML = `<img src="${profileImage}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" />`;
    }
    sessionStorage.setItem('bepo_user_name', fullname);
    if (idNumber) sessionStorage.setItem('bepo_id_number', idNumber);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      if (!document.querySelector('.pms-topbar')) window.renderPMSNavbar();
    });
  } else {
    if (!document.querySelector('.pms-topbar')) window.renderPMSNavbar();
  }

})();