/**
 * Accessible primary navigation (mobile burger + multi-level submenus).
 *
 * Pattern: WAI-ARIA APG "Disclosure Navigation Menu".
 * - The mobile burger is a <button aria-expanded> controlling the whole panel.
 * - Each parent item keeps its link AND gets a separate <button aria-expanded>
 *   (injected here) controlling its submenu — same behaviour on every viewport.
 * - Escape closes the innermost open submenu first (focus back on its button),
 *   then the mobile panel.
 * - Clicking / tabbing outside the header closes everything.
 * - State is reset when crossing the desktop breakpoint.
 *
 * Without JS, submenus stay reachable through the CSS :hover / :focus-within
 * fallback (see app.css, `.nav-primary:not(.is-enhanced)`).
 */
const DESKTOP_BREAKPOINT = '(min-width: 64rem)'; // Tailwind `lg`

function initNavigation() {
  const header = document.getElementById('site-header');

  if (!header) {
    return;
  }

  const toggle = header.querySelector('[data-nav-toggle]');
  const nav = header.querySelector('[data-nav-panel]');

  // --- Mobile panel (burger) -------------------------------------------------
  let panelIsOpen = () => false;
  let closePanel = () => {};

  if (toggle && nav) {
    const label = toggle.querySelector('[data-nav-toggle-label]');
    const labelOpen = toggle.dataset.labelOpen || '';
    const labelClose = toggle.dataset.labelClose || '';

    panelIsOpen = () => toggle.getAttribute('aria-expanded') === 'true';

    const setPanel = (open) => {
      toggle.setAttribute('aria-expanded', String(open));
      nav.classList.toggle('is-open', open);
      if (label) {
        label.textContent = open ? labelClose : labelOpen;
      }
    };

    closePanel = ({ focusToggle = false } = {}) => {
      if (!panelIsOpen()) {
        return;
      }
      setPanel(false);
      if (focusToggle) {
        toggle.focus();
      }
    };

    toggle.addEventListener('click', () => setPanel(!panelIsOpen()));
  }

  // --- Submenus (injected disclosure buttons) --------------------------------
  const submenuToggles = [];

  if (nav) {
    // Tell CSS to switch from the no-JS :hover/:focus-within fallback to the
    // button-driven behaviour.
    nav.classList.add('is-enhanced');

    const labelTemplate = nav.dataset.submenuLabel || '%s';

    nav.querySelectorAll('.menu-item-has-children').forEach((item, index) => {
      const link = item.querySelector(':scope > a');
      const submenu = item.querySelector(':scope > .sub-menu, :scope > ul');

      if (!submenu) {
        return;
      }

      if (!submenu.id) {
        submenu.id = `submenu-${index + 1}`;
      }

      const parentLabel = (link ? link.textContent : '').trim();

      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'submenu-toggle';
      button.setAttribute('aria-expanded', 'false');
      button.setAttribute('aria-controls', submenu.id);
      button.innerHTML =
        `<span class="sr-only">${labelTemplate.replace('%s', parentLabel)}</span>` +
        '<svg class="submenu-toggle__icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polyline points="6 9 12 15 18 9"></polyline></svg>';

      if (link) {
        link.insertAdjacentElement('afterend', button);
      } else {
        item.insertBefore(button, submenu);
      }

      submenuToggles.push(button);
    });
  }

  const closeSubmenu = (button, { focusButton = false } = {}) => {
    button.setAttribute('aria-expanded', 'false');
    button.closest('.menu-item-has-children')?.classList.remove('is-open');
    if (focusButton) {
      button.focus();
    }
  };

  const openSubmenu = (button) => {
    const item = button.closest('.menu-item-has-children');

    // Close sibling submenus at the same level.
    item?.parentElement
      ?.querySelectorAll(':scope > .menu-item-has-children.is-open > .submenu-toggle')
      .forEach((sibling) => sibling !== button && closeSubmenu(sibling));

    button.setAttribute('aria-expanded', 'true');
    item?.classList.add('is-open');
  };

  submenuToggles.forEach((button) => {
    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';
      expanded ? closeSubmenu(button) : openSubmenu(button);
    });
  });

  const openSubmenuButtons = () =>
    submenuToggles.filter((button) => button.getAttribute('aria-expanded') === 'true');

  const closeAllSubmenus = () => openSubmenuButtons().forEach((button) => closeSubmenu(button));

  // --- Shared keyboard / outside handlers ------------------------------------
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    const openButtons = openSubmenuButtons();

    if (openButtons.length) {
      // Close the INNERMOST open submenu containing the focus first: its toggle
      // is the one owned by the nearest open parent item (handles deep nesting).
      const nearestOpenItem = document.activeElement?.closest('.menu-item-has-children.is-open');
      const target =
        nearestOpenItem?.querySelector(':scope > .submenu-toggle') ||
        openButtons[openButtons.length - 1];

      closeSubmenu(target, { focusButton: true });
      return;
    }

    closePanel({ focusToggle: true });
  });

  const closeOnOutside = (event) => {
    if (event.target.closest('#site-header')) {
      return;
    }
    closeAllSubmenus();
    closePanel();
  };

  document.addEventListener('click', closeOnOutside);
  document.addEventListener('focusin', closeOnOutside);

  // Reset everything when switching to/from the desktop layout.
  window.matchMedia(DESKTOP_BREAKPOINT).addEventListener('change', () => {
    closeAllSubmenus();
    closePanel();
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initNavigation);
} else {
  initNavigation();
}
