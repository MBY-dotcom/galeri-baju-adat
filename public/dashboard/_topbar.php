<div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 shadow rounded-lg">

    <!-- Mobile Toggle -->
    <button class="md:hidden text-2xl" onclick="document.getElementById('mobileSidebar').classList.toggle('hidden')">
        ☰
    </button>

    <h2 class="text-lg font-semibold">Galeri Bu Nunuk Sahid</h2>

    <button id="darkModeToggle" class="ml-4 px-3 py-1 border rounded border-color:black text-sm">🌙</button>

    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Cek preferensi mode gelap di localStorage
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark');
            darkModeToggle.textContent = '☀️';
        }

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark');
            if (body.classList.contains('dark')) {
            darkModeToggle.textContent = '☀️';
            localStorage.setItem('darkMode', 'enabled');
            } else {
            darkModeToggle.textContent = '🌙';
            localStorage.setItem('darkMode', 'disabled');
            }
        });
    </script>
</div>
