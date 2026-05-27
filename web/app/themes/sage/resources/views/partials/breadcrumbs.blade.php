{{--
  Fil d'Ariane fourni par Yoast SEO (yoast_breadcrumb).

  - Masqué sur la page d'accueil (redondant).
  - Le <nav aria-label> fournit le point de repère RGAA ; Yoast marque
    l'élément courant avec aria-current="page".
  - Le balisage BreadcrumbList (schema.org) est géré séparément par Yoast.
--}}
@if (function_exists('yoast_breadcrumb') && ! is_front_page())
  <nav class="breadcrumb py-3" aria-label="{{ __('Breadcrumb', 'sage') }}">
    @php(yoast_breadcrumb())
  </nav>
@endif
