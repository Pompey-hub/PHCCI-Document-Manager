<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
include('../config/database.php');

$user_id = $_SESSION['user_id'] ?? 0;

/* =========================
   SHARED FOLDERS
========================= */
$stmt = $conn->prepare("
    SELECT
        fs.*,
        f.folder_name,
        u.first_name,
        u.last_name
    FROM folder_shares fs
    INNER JOIN folders f ON fs.folder_id = f.id
    INNER JOIN users u ON fs.owner_id = u.id
    WHERE fs.shared_to = ?
    AND f.status = 'active'
    ORDER BY fs.shared_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$sharedFolders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================
   SHARED FILES
========================= */
$stmt = $conn->prepare("
    SELECT
        fs.*,
        f.original_name,
        f.file_extension,
        f.file_path,
        u.first_name,
        u.last_name
    FROM file_shares fs
    INNER JOIN files f ON fs.file_id = f.id
    INNER JOIN users u ON fs.owner_id = u.id
    WHERE fs.shared_to = ?
    AND f.status = 'active'
    ORDER BY fs.shared_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$sharedFiles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Shared With Me
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Files and folders others have shared with you
            </p>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <input type="text" placeholder="Search shared files..."
                class="w-full h-11 px-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-700 dark:text-white/90 focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <!-- Shared Folders -->
        <div class="mb-8">

            <h2 class="mb-3 text-sm font-semibold text-gray-600 dark:text-gray-300">
                Shared Folders
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                <?php if (!empty($sharedFolders)): ?>

                    <?php foreach ($sharedFolders as $folder): ?>

                        <a href="shared_folder.php?folder_id=<?= $folder['folder_id'] ?>"
                            class="p-4 bg-white border rounded-xl dark:bg-white/[0.03] dark:border-gray-800 hover:shadow-md">

                            <div class="flex items-center gap-3">

                                <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-500/20">
                                    📁
                                </div>

                                <div>

                                    <p class="font-medium text-gray-800 dark:text-white/90">
                                        <?= htmlspecialchars($folder['folder_name']) ?>
                                    </p>

                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Shared by
                                        <?= htmlspecialchars($folder['first_name'] . ' ' . $folder['last_name']) ?>
                                    </span>

                                    <br>

                                    <span class="text-xs text-blue-500">
                                        <?= ucfirst($folder['permission']) ?>
                                    </span>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="text-sm text-gray-500">
                        No shared folders found.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <!-- Shared Files Table -->
        <div class="bg-white border rounded-2xl dark:bg-white/[0.03] dark:border-gray-800">

            <div class="flex items-center justify-between p-4 border-b dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-white/90">
                    Shared Files
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">

                    <thead>
                        <tr class="text-left border-b dark:border-gray-800">
                            <th class="p-3 text-sm font-medium text-gray-500 dark:text-gray-400">Name</th>
                            <th class="p-3 text-sm font-medium text-gray-500 dark:text-gray-400">Shared By</th>
                            <th class="p-3 text-sm font-medium text-gray-500 dark:text-gray-400">Type</th>
                            <th class="p-3 text-sm font-medium text-gray-500 dark:text-gray-400">Date Shared</th>
                            <th class="p-3 text-sm font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($sharedFiles)): ?>

                            <?php foreach ($sharedFiles as $file): ?>

                                <tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/5">

                                    <td class="p-3 text-gray-800 dark:text-white/90">
                                        📄
                                        <?= htmlspecialchars($file['original_name']) ?>
                                    </td>

                                    <td class="p-3 text-gray-500 dark:text-gray-400">
                                        <?= htmlspecialchars($file['first_name'] . ' ' . $file['last_name']) ?>
                                    </td>

                                    <td class="p-3 text-gray-500 dark:text-gray-400">
                                        <?= strtoupper($file['file_extension']) ?>
                                    </td>

                                    <td class="p-3 text-gray-500 dark:text-gray-400">
                                        <?= date('M d, Y', strtotime($file['shared_at'])) ?>
                                    </td>

                                    <td class="p-3">

                                        <div class="flex gap-3">

                                            <a href="<?= htmlspecialchars($file['file_path']) ?>"
                                                download="<?= htmlspecialchars($file['original_name']) ?>"
                                                class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                                Download
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="5" class="p-4 text-center text-gray-500">
                                    No files shared with you.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</main>
<!-- ===== Main Content End ===== -->
<!-- ===== Main Content End ===== -->
</div>
<!-- ===== Content Area End ===== -->
</div>
<!-- ===== Page Wrapper End ===== -->
<script defer src="../assets/js/bundle.js"></script>

<?php include('includes/footer.php'); ?>