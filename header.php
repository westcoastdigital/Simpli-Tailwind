<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php simpli_schema_type() ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- Navbar -->
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <div class="text-xl font-bold text-brand-600">
        <?= get_bloginfo('name') ?>
      </div>

      <?php
      $walker = new Tailwind_Nav_Walker();

      wp_nav_menu(array(
        'theme_location' => 'main-menu',
        'container' => 'nav',
        'container_class' => 'hidden lg:flex items-center gap-10',
        'items_wrap' => '%3$s',
        'walker' => $walker
      ));

      // Render the last item as button after </nav>
      echo $walker->render_button();
      ?>

      <!-- Mobile hamburger — sits fixed so it stays on top of the fullscreen menu -->
      <button id="menuBtn"
        class="lg:hidden fixed top-0 right-0 z-[60] flex items-center justify-center w-20 h-20 text-stone-600 dark:text-stone-300 transition-colors"
        aria-label="Toggle menu"
        aria-expanded="false"
        aria-controls="mobileMenu">
        <svg id="menuIconOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="menuIconClose" class="w-6 h-6 hidden text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

    </div>

    <!-- Fullscreen mobile menu -->
    <div id="mobileMenu" class="hidden lg:hidden fixed h-svh inset-0 z-50 bg-stone-900/95 backdrop-blur-xl flex-col px-8 pt-32 pb-12 overflow-y-auto">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'main-menu',
        'container'      => false,
        'items_wrap'     => '<ul class="flex flex-col w-full divide-y divide-white/10">%3$s</ul>',
        'walker'         => new Tailwind_Mobile_Nav_Walker(),
      ));
      ?>
    </div>
  </header>
