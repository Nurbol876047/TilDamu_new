    </main>

    <!-- Footer -->
    <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-200/50 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-gray-500">
                <p><?= $t['footer'] ?></p>
            </div>
        </div>
    </footer>

    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
