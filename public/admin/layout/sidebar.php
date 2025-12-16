<!-- SIDEBAR DESKTOP -->
<div class="w-64 bg-white dark:bg-gray-800 shadow-xl hidden md:block">

    <div class="p-6 border-b dark:border-gray-700">
        <h2 class="text-xl font-bold">Admin Panel</h2>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2">

        <a href="dashboard.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Dashboard
        </a>

        <a href="admin_list.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Koleksi Baju
        </a>

        <a href="pesanan_list.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Daftar Pesanan
        </a>

          <a href="user_list.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Daftar User
        </a>


    </nav>
    
    <div class="p-4">
        <a href="../index.php"
           class="block px-4 py-3 mt-6 rounded-lg bg-red-500 text-white text-center hover:bg-red-600">Logout
        </a>
    </div>
    
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

        <a href="dashboard.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Dashboard
         </a>

        <a href="adminlist.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Koleksi Baju
        </a>

        <a href="pesanan_list.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Daftar Pesanan
        </a>

          <a href="user_list.php"
           class="block px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
            Daftar User
        </a>

        <a href="../index.php"
               class="block px-4 py-3 mt-6 rounded-lg bg-red-500 text-white text-center hover:bg-red-600">
                Logout
        </a>

        </nav>

    </div>
</div>
