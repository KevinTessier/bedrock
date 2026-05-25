<footer class="border-t border-black/10">
  <div class="content-grid">
      <nav id="footer-menu" class="nav-footer" aria-label="{{ wp_get_nav_menu_name('footer_navigation') }}">
      {!! wp_nav_menu([
          'theme_location' => 'footer_navigation',
          'container' => false,
          'menu_class' => 'nav',
          'echo' => false,
          'fallback_cb' => false,
      ]) !!}
    </nav>    
  </div>
</footer>
