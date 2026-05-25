<header id="site-header" class="relative z-50 border-b border-black/10 bg-white" role="banner">
  <div class="container mx-auto flex items-center justify-between gap-4 py-3 px-4">
    {{-- Logo / nom du site --}}
    <div class="site-branding shrink-0">
      @if (has_custom_logo())
        {!! get_custom_logo() !!}
      @else
        <a class="brand text-lg font-semibold text-gray-900 no-underline" href="{{ home_url('/') }}" rel="home">
          {!! $siteName !!}
        </a>
      @endif
    </div>

    @if (has_nav_menu('primary_navigation'))
      {{-- Bouton burger : visible uniquement en version mobile --}}
      <button
        type="button"
        class="nav-toggle inline-flex items-center justify-center rounded-md p-2 text-gray-900 hover:bg-black/5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 lg:hidden"
        aria-expanded="false"
        aria-controls="primary-menu"
        data-nav-toggle
        data-label-open="{{ __('Open main menu', 'sage') }}"
        data-label-close="{{ __('Close main menu', 'sage') }}"
      >
        <span class="sr-only" data-nav-toggle-label>{{ __('Open main menu', 'sage') }}</span>
        {{-- Icône ouverte (3 barres) --}}
        <svg class="nav-toggle__icon-open size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false">
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
        {{-- Icône fermée (croix) --}}
        <svg class="nav-toggle__icon-close size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false">
          <line x1="6" y1="6" x2="18" y2="18" />
          <line x1="18" y1="6" x2="6" y2="18" />
        </svg>
      </button>

      <nav
        id="primary-menu"
        class="nav-primary"
        aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}"
        data-nav-panel
        data-submenu-label="{{ __('Submenu of %s', 'sage') }}"
      >
        {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'container' => false,
            'menu_class' => 'nav',
            'echo' => false,
            'fallback_cb' => false,
        ]) !!}
      </nav>
    @endif
  </div>
</header>
