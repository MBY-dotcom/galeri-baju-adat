<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: ../login_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Dashboard Admin" ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="flex min-h-screen">

        <?php include __DIR__ . "/sidebar.php"; ?>

        <main class="flex-1 pt-2 px-6 pb-6 md:pt-2 md:px-8 md:pb-8">

            <?php include __DIR__ . "/topbar.php"; ?>

            <div class="mt-2">
                <?= $content ?>
            </div>

        </main>
    </div>

</body>
</html>
