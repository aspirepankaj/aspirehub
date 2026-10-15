const platforms = [
  { name: 'Google Analytics', description: 'Traffic & engagement', type: 'google', url: 'https://analytics.google.com/' },
  { name: 'Search Console', description: 'Search visibility', type: 'console', url: 'https://search.google.com/search-console' },
  { name: 'YouTube', description: 'Video performance', type: 'youtube', url: 'https://studio.youtube.com/' },
  { name: 'Keywords.com', description: 'Keyword rankings', type: 'keywords', url: 'https://www.keywords.com/' },
  { name: 'Meta', description: 'Social insights', type: 'meta', url: 'https://business.facebook.com/' },
];

const logos = {
  google: '<svg class="google-logo" viewBox="0 0 54 44"><rect x="27" y="2" width="10" height="40" rx="5" fill="currentColor"/><rect x="14" y="17" width="10" height="25" rx="5" fill="currentColor"/><circle cx="6" cy="37" r="5" fill="currentColor"/></svg>',
  youtube: '<svg class="youtube-logo" viewBox="0 0 56 44"><path d="M4 7Q28 2 51 7Q56 21 51 37Q28 42 4 37Q-1 23 4 7" fill="currentColor"/><path d="m23 13 16 9-16 9Z" fill="var(--primary-foreground)"/></svg>',
  meta: '<svg class="meta-logo" viewBox="0 0 58 44" fill="none"><path d="M5 31C1 24 8 8 15 8c9 0 17 29 25 29 16 0 11-29 1-29C31 8 22 37 13 37c-4 0-6-2-8-6Z" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg>',
  keywords: '<svg class="keywords-logo" viewBox="0 0 53 44"><rect x="2" y="8" width="10" height="30" rx="5" fill="var(--violet)"/><path d="m29 2-16 20 16 20h13L26 22 42 2Z" fill="currentColor"/></svg>',
  console: '<svg class="console-logo" viewBox="0 0 55 44"><path d="M19 11V7q0-4 5-4h8q5 0 5 4v4" fill="none" stroke="currentColor" stroke-width="3"/><rect x="4" y="12" width="47" height="30" rx="4" fill="var(--border)"/><path d="M9 11h39v10H9Z" fill="var(--blue)" opacity=".75"/><path d="M9 24h9v17H9m34-17h5v17h-5" fill="var(--card)"/><circle cx="14" cy="16" r="1.5" fill="var(--card)"/><circle cx="20" cy="16" r="1.5" fill="var(--card)"/><path d="m29 24-3 5 2 6 5 2v5h5v-8c4-3 4-7 1-10v6l-5 2-4-3v-5Z" fill="currentColor"/></svg>',
};
const wave = '<svg class="card-wave" viewBox="0 0 210 36" preserveAspectRatio="none"><path d="M0 8C61 6 92 52 210 1V36H0Z"/><path d="M0 35C89 43 138 1 210 3V36H0Z"/></svg>';

const $ = (s) => document.querySelector(s);
const sidebar = $('#sidebar'), overlay = $('#overlay'), title = $('#modalTitle'), body = $('#modalBody');

// Platform cards
$('#platformsGrid').innerHTML = platforms.map(p => `
  <article class="platform-card platform-${p.type}">
    <div class="platform-logo">${logos[p.type]}</div>
    <h3 class="platform-name">${p.name}</h3>
    <p class="platform-description">${p.description}</p>
    <button class="btn btn-ghost btn-icon platform-arrow" data-open="${p.name}"><i data-lucide="arrow-right"></i></button>
    ${wave}
  </article>`).join('');

function icons() { if (window.lucide) lucide.createIcons(); }

function openModal(name) {
  sidebar.classList.remove('is-open');
  title.textContent = name;
  const p = platforms.find(x => x.name === name);
  let html;
  if (name === 'Open Support Ticket' || name === 'Support Center') {
    html = `<form id="ticketForm"><label for="subject">Subject</label><input id="subject" required placeholder="How can we help?"/>
      <label for="message">Message</label><textarea id="message" required placeholder="Tell us what you need help with..."></textarea>
      <button type="submit" class="btn btn-primary modal-submit"><i data-lucide="message-square-more"></i>Prepare Ticket</button></form>`;
  } else if (p) {
    html = `<div class="platform-logo">${logos[p.type]}</div><p>${p.description}</p><p>Live account data is not connected.</p>
      <a class="btn btn-primary modal-submit" href="${p.url}" target="_blank" rel="noreferrer">Open ${p.name}<i data-lucide="arrow-right"></i></a>`;
  } else if (name === 'Analytics Hub') {
    html = platforms.map(x => `<div class="status-row">${x.name}<button class="btn btn-ghost btn-icon" data-open="${x.name}"><i data-lucide="arrow-right"></i></button></div>`).join('');
  } else if (name === 'Notifications') {
    html = '<p>You’re all caught up. No new notifications.</p>';
  } else if (name === 'My Profile') {
    html = '<div class="profile"><div class="avatar">CM</div>Christine Mullen</div><p>Client · Aspire Hub</p>';
  } else if (name === 'Log Out') {
    html = '<p>There is no active sign-in session to log out of.</p><button class="btn btn-primary modal-submit" data-close>Back to Dashboard</button>';
  } else {
    const text = name === 'My Websites' ? '1 connected website · All operational'
      : name === 'Active Services' ? '3 subscribed services'
      : name.includes('Reports') ? 'Latest report · September 2026'
      : name === 'Growth Snapshot' ? 'Your digital performance overview' : 'Aspire Hub resources';
    html = `<p>${text}</p><p>Live account details are not connected.</p>`;
  }
  body.innerHTML = html;
  overlay.classList.remove('hidden');
  icons();
  const form = $('#ticketForm');
  if (form) form.addEventListener('submit', e => {
    e.preventDefault();
    body.innerHTML = '<p>Your ticket is ready. This page is not connected to a support service, so it has not been sent.</p><button class="btn btn-primary modal-submit" data-close>Back to Dashboard</button>';
  });
}
function closeModal() { overlay.classList.add('hidden'); }

document.addEventListener('click', e => {
  const opener = e.target.closest('[data-open]');
  if (opener) return openModal(opener.dataset.open);
  const nav = e.target.closest('.nav-item');
  if (nav) {
    if (nav.dataset.label === 'Dashboard') { closeModal(); sidebar.classList.remove('is-open'); }
    else openModal(nav.dataset.label);
    return;
  }
  if (e.target.closest('[data-close]') || e.target.closest('#modalClose') || e.target === overlay) closeModal();
  if (e.target.closest('.back-button')) { closeModal(); $('#quickSearch').value = ''; filterNav(''); }
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); $('#quickSearch').focus(); }
});

// Mobile menu
$('#menuBtn').addEventListener('click', () => sidebar.classList.toggle('is-open'));

// Quick search
function filterNav(q) {
  document.querySelectorAll('.nav-item').forEach(b => b.classList.toggle('hidden', !b.dataset.label.toLowerCase().includes(q.toLowerCase())));
}
$('#quickSearch').addEventListener('input', e => filterNav(e.target.value));

// Dark mode
const themeBtn = $('#themeToggle');
function setTheme(dark) {
  document.body.classList.toggle('dark', dark);
  themeBtn.innerHTML = dark ? '<i data-lucide="sun"></i><span>Light</span>' : '<i data-lucide="moon"></i><span>Dark</span>';
  localStorage.setItem('aspire-theme', dark ? 'dark' : 'light');
  icons();
}
themeBtn.addEventListener('click', () => setTheme(!document.body.classList.contains('dark')));
setTheme(localStorage.getItem('aspire-theme') === 'dark');
icons();
