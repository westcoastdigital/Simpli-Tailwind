  <!-- Footer -->
  <footer class="bg-white border-t">
      <div class="max-w-6xl mx-auto px-4 py-10 text-sm text-gray-600 flex flex-col md:flex-row justify-between gap-4">
          <p>&copy; <?= date('Y') ?> <?= get_bloginfo('name') ?>. All rights reserved.</p>

          <div class="flex space-x-4">
              <a href="#" class="hover:text-brand-600">Privacy</a>
              <a href="#" class="hover:text-brand-600">Terms</a>
              <a href="https://github.com/westcoastdigital/Simpli-Tailwind" class="hover:text-brand-600" target="_blank">GitHub</a>
          </div>
      </div>
  </footer>

  <?php wp_footer(); ?>
  </body>

  </html>