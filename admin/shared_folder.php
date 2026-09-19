<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
include('includes/share_helper.php');
include('../config/database.php');

$user_id = $_SESSION['user_id'] ?? 0;

$folder_id = isset($_GET['folder_id']) ? (int) $_GET['folder_id'] : 0;

if ($folder_id <= 0) {
    die("Invalid folder.");
}

/* Verify that this folder is shared with the current user */

$sharedFolder = getInheritedFolderShare(
    $conn,
    $folder_id,
    $user_id
);

if (!$sharedFolder) {
    die("Access denied.");
}

$permission = $sharedFolder['permission'];

/* Load subfolders */

$stmt = $conn->prepare("
SELECT *
FROM folders
WHERE parent_folder_id=?
AND status='active'
ORDER BY folder_name
");

$stmt->bind_param("i", $folder_id);
$stmt->execute();

$folders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* Load files */

$stmt = $conn->prepare("
SELECT *
FROM files
WHERE folder_id=?
AND status='active'
ORDER BY uploaded_at DESC
");

$stmt->bind_param("i", $folder_id);
$stmt->execute();

$files = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!-- ===== Main Content Start ===== -->
<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

        <!-- Header -->
        <div class="mb-6">

            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                <?= htmlspecialchars($sharedFolder['folder_name']) ?>
            </h1>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Shared by
                <strong>
                    <?= htmlspecialchars($sharedFolder['first_name'] . ' ' . $sharedFolder['last_name']) ?>
                </strong>
                •
                Permission:
                <span class="font-medium">
                    <?= ucfirst($permission) ?>
                </span>
            </p>

        </div>

        <!-- Folders -->
        <div class="mb-8">

            <h2 class="mb-4 text-sm font-semibold text-gray-600 dark:text-gray-300">
                Folders
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                <?php if (!empty($folders)): ?>

                    <?php foreach ($folders as $folder): ?>

                        <a href="shared_folder.php?folder_id=<?= $folder['id'] ?>"
                            class="flex items-center gap-3 p-4 transition bg-white border rounded-xl hover:shadow-md dark:bg-white/[0.03] dark:border-gray-800">

                            <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-500/20">
                                📁
                            </div>

                            <div>

                                <p class="font-medium text-gray-800 dark:text-white">
                                    <?= htmlspecialchars($folder['folder_name']) ?>
                                </p>

                                <span class="text-xs text-gray-500">
                                    Folder
                                </span>

                            </div>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="text-sm text-gray-500">
                        No folders inside this folder.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <!-- Files -->
        <div class="mb-8">

            <h2 class="mb-4 text-sm font-semibold text-gray-600 dark:text-gray-300">
                Files
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                <?php if (!empty($files)): ?>

                    <?php foreach ($files as $file): ?>

                        <div
                            class="flex items-center justify-between p-4 transition bg-white border rounded-xl hover:shadow-md dark:bg-white/[0.03] dark:border-gray-800">

                            <div class="flex items-center gap-3">

                                <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-500/20">
                                    📄
                                </div>

                                <div>

                                    <p class="font-medium text-gray-800 dark:text-white">
                                        <?= htmlspecialchars($file['original_name']) ?>
                                    </p>

                                    <span class="text-xs text-gray-500">
                                        <?= strtoupper($file['file_extension']) ?>
                                    </span>

                                </div>

                            </div>

                            <div class="relative">

                                <button type="button" onclick="toggleFileMenu(this)"
                                    class="px-2 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                                    ⋮
                                </button>

                                <div
                                    class="hidden absolute right-0 z-50 w-36 mt-2 bg-white border rounded-lg shadow-lg dark:bg-gray-900 dark:border-gray-700 file-menu">

                                    <a href="<?= htmlspecialchars($file['file_path']) ?>"
                                        download="<?= htmlspecialchars($file['original_name']) ?>"
                                        class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                        Download
                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="text-sm text-gray-500">
                        No files inside this folder.
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </div>
</main>

<?php include('includes/modal.php'); ?>
<!-- ===== Page Wrapper End ===== -->
<script defer src="../assets/js/bundle.js"></script>
<script>
    function toggleFileMenu(button) {

        document.querySelectorAll('.file-menu').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.add('hidden');
            }
        });

        button.nextElementSibling.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {

        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.file-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }

    });
</script>

<?php include('includes/footer.php'); ?>