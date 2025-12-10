<!-- SIDEBAR DESKTOP -->
<div class="w-64 bg-white dark:bg-gray-800 shadow-xl hidden md:block">

    <div class="p-6 border-b dark:border-gray-700">
        <h2 class="text-xl font-bold">User Panel</h2>
    </div>

    <nav class="mt-4 space-y-1 px-4">

        <a href="index.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Dashboard
        </a>

        <a href="koleksi.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Koleksi Baju
        </a>

        <a href="pesanan_saya.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Pesanan Saya
        </a>

          <a href="profil.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Pengaturan Profil
        </a>

        <a href="logout.php"
           class="block px-4 py-3 mt-6 rounded-lg bg-red-500 text-white text-center hover:bg-red-600">
            Logout
        </a>

    </nav>
</div>


<!-- SIDEBAR MOBILE (AUTOMATIS MENIRU DESKTOP) -->
<div id="mobileSidebar" 
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden md:hidden"
     onclick="this.classList.add('hidden')">

    <div class="w-64 bg-white dark:bg-gray-800 h-full shadow-xl p-6"
         onclick="event.stopPropagation()">

        <h2 class="text-xl font-bold mb-4">User Panel</h2>

        <!-- Copy menu yang sama dengan desktop -->
        <nav class="space-y-1">

            <a href="index.php"
               class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                Dashboard
            </a>

            <a href="koleksi.php"
               class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                Koleksi Baju
            </a>

            <a href="pesanan_saya.php"
               class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                Pesanan Saya
            </a>
            <a href="profil.php"
                class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                 Pengaturan Profil
            </a>

            <a href="logout.php"
               class="block px-4 py-3 mt-6 rounded-lg bg-red-500 text-white text-center hover:bg-red-600">
                Logout
            </a>

        </nav>

    </div>
</div>
