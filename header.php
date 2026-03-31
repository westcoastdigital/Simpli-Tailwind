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
        Simpli Tailwind
      </div>

      <nav class="hidden md:flex space-x-6 text-sm font-medium">
        <a href="#" class="hover:text-brand-600">Home</a>
        <a href="#" class="hover:text-brand-600">Features</a>
        <a href="#" class="hover:text-brand-600">Docs</a>
        <a href="#" class="hover:text-brand-600">Contact</a>
      </nav>

      <!-- <a href="#" class="border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-100"> -->
      <a href="#" class="hidden md:inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
        Get Started
      </a>


      <!-- Mobile Button -->
      <button id="menuBtn" class="md:hidden flex items-center">
        ☰
      </button>

    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden px-4 pb-4 space-y-2">
      <a href="#" class="block py-2">Home</a>
      <a href="#" class="block py-2">Features</a>
      <a href="#" class="block py-2">Docs</a>
      <a href="#" class="block py-2">Contact</a>

      <a href="#" class="block mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-center">
        Get Started
      </a>
    </div>
  </header>