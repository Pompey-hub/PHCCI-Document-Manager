<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
include('../classes/master.php');
include('../config/database.php');

$myDrive = new myDrive();
$user_id = $_SESSION['user_id'] ?? 0;

/* =========================
   BULK RESTORE
========================= */
if (isset($_POST['bulk_restore'])) {

    if (!empty($_POST['selected_items'])) {

        foreach ($_POST['selected_items'] as $item) {
            [$type, $id] = explode(':', $item);

            if ($type === 'folder') {
                $conn->query("UPDATE folders SET status='active' WHERE id=" . (int) $id);
            }

            if ($type === 'file') {
                $conn->query("UPDATE files SET status='active' WHERE id=" . (int) $id);
            }
        }
    }
}

/* =========================
   BULK DELETE (PERMANENT)
========================= */
if (isset($_POST['bulk_delete'])) {

    if (!empty($_POST['selected_items'])) {

        foreach ($_POST['selected_items'] as $item) {
            [$type, $id] = explode(':', $item);

            if ($type === 'folder') {
                $conn->query("DELETE FROM folders WHERE id=" . (int) $id);
            }

            if ($type === 'file') {

                // delete physical file
                $res = $conn->query("SELECT file_path FROM files WHERE id=" . (int) $id);
                if ($row = $res->fetch_assoc()) {
                    if (file_exists($row['file_path'])) {
                        unlink($row['file_path']);
                    }
                }

                $conn->query("DELETE FROM files WHERE id=" . (int) $id);
            }
        }
    }
}

/* =========================
   LOAD DELETED ITEMS
========================= */

$stmt = $conn->prepare("SELECT * FROM folders WHERE user_id=? AND status='deleted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$folders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT * FROM files WHERE user_id=? AND status='deleted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$files = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Trash
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Deleted files and folders can be restored or permanently removed.
            </p>
        </div>

        <!-- BULK ACTION BAR -->
        <form method="POST" id="bulkForm">

            <div class="flex items-center justify-between mb-4">

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        Select All
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" name="bulk_restore" class="px-3 py-1 text-sm bg-green-500 rounded">
                        Restore Selected
                    </button>

                    <button type="submit" name="bulk_delete"
                        onclick="return confirm('Delete selected items permanently?')"
                        class="px-3 py-1 text-sm bg-red-500 rounded">
                        Delete Selected
                    </button>
                </div>

            </div>

            <!-- Deleted Folders -->
            <div class="mb-8">

                <h2 class="mb-3 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    Deleted Folders
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                    <?php if (!empty($folders)): ?>
                        <?php foreach ($folders as $folder): ?>

                            <div class="p-4 bg-white border rounded-xl">

                                <div class="flex items-center gap-3 mb-3">

                                    <input type="checkbox" name="selected_items[]" value="folder:<?= $folder['id'] ?>">

                                    <div class="p-3 bg-red-100 rounded-lg">📁</div>

                                    <div>
                                        <p class="font-medium">
                                            <?= htmlspecialchars($folder['folder_name']) ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <p class="text-sm text-gray-500">
                            No deleted folders.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Deleted Files -->
            <div class="mb-8">

                <h2 class="mb-3 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    Deleted Files
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                    <?php if (!empty($files)): ?>
                        <?php foreach ($files as $file): ?>

                            <div class="p-4 bg-white border rounded-xl">

                                <div class="flex items-center gap-3 mb-3">

                                    <input type="checkbox" name="selected_items[]" value="file:<?= $file['id'] ?>">

                                    <div class="p-3 bg-red-100 rounded-lg">📄</div>

                                    <div>
                                        <p class="font-medium">
                                            <?= htmlspecialchars($file['original_name']) ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <p class="text-sm text-gray-500">
                            No deleted files.
                        </p>

                    <?php endif; ?>

                </div>

            </div>
        </form>
    </div>
</main>

<script defer src="../assets/js/bundle.js"></script>

<script>
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll(
            'input[name="selected_items[]"]'
        );

        checkboxes.forEach(cb => {
            cb.checked = source.checked;
        });
    }
</script>

<?php include('includes/footer.php'); ?>