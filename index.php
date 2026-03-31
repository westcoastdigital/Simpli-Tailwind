<?php get_header(); ?>

<main id="content">

  <!-- Hero -->
  <section class="bg-gradient-to-b from-white to-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-24 text-center">
      <h1 class="text-4xl md:text-6xl font-bold leading-tight">
        A modern Tailwind CSS starter theme
      </h1>
      <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto">
        Build fast, responsive websites with a clean structure, ready for WordPress, Vue, or static HTML projects.
      </p>

      <div class="mt-8 flex justify-center gap-4">
        <a href="#" class="bg-brand-600 text-white px-6 py-3 rounded-lg hover:bg-brand-700">
          Get Started
        </a>
        <a href="https://github.com/westcoastdigital/Simpli-Tailwind" class="border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-100" target="_blank">
          View Docs
        </a>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="max-w-6xl mx-auto px-4 py-20">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold">Built for developers</h2>
      <p class="text-gray-600 mt-2">Simple, scalable, and easy to extend.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-2">Fast Setup</h3>
        <p class="text-gray-600">Get started instantly with a clean Tailwind base structure.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-2">Responsive</h3>
        <p class="text-gray-600">Mobile-first design that works on all screen sizes.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-2">Customisable</h3>
        <p class="text-gray-600">Easily extend colours, spacing, and components.</p>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="bg-brand-600 text-white">
    <div class="max-w-6xl mx-auto px-4 py-16 text-center">
      <h2 class="text-3xl font-bold">Ready to build something awesome?</h2>
      <p class="mt-3 text-white/80">Start using this Tailwind starter in your next project.</p>

      <a href="#" class="mt-6 inline-block bg-white text-brand-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
        Start Now
      </a>
    </div>
  </section>
</main>

<?php get_footer(); ?>