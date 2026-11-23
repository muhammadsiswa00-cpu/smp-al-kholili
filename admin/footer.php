        </div> <!-- End Content Body -->

        <!-- Admin Footer -->
        <footer class="bg-white border-t border-slate-200 px-6 py-4 text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p>&copy; <?= date('Y') ?> SMP Islam Al-Kholili Kota Probolinggo. By AMIK Taruna Memet &bull; Panel Kendali Administrator.</p>
            <p class="text-brand-700 font-semibold">Sistem Pengelolaan Terintegrasi</p>
        </footer>

    </main>

    <!-- Admin Mobile Menu Toggle Script -->
    <script>
        const adminMobileBtn = document.getElementById('adminMobileBtn');
        const adminSidebar = document.getElementById('adminSidebar');
        if (adminMobileBtn && adminSidebar) {
            adminMobileBtn.addEventListener('click', () => {
                adminSidebar.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
