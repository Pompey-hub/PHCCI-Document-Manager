<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
include('../classes/master.php');
include('../config/database.php');

$myDrive = new myDrive();
$search = trim($_POST['search'] ?? '');
/* =========================
   detect folder
========================= */
$current_folder_id = isset($_GET['folder_id'])
    ? (int) $_GET['folder_id']
    : null;
/* =========================
   CREATE FOLDER
========================= */
if (isset($_POST['create_folder'])) {

    $user_id = $_SESSION['user_id'] ?? 0;
    $folder_name = trim($_POST['folder_name']);

    if (!empty($folder_name) && $user_id > 0) {

        // Use current folder as parent
        $parent_folder_id = $current_folder_id;

        if ($parent_folder_id == 0) {
            $parent_folder_id = null;
        }

        $result = $myDrive->createFolder(
            $user_id,
            $folder_name,
            $parent_folder_id
        );

        if ($result) {
            $_SESSION['success'] = "Folder created successfully!";
        } else {
            $_SESSION['error'] = "Failed to create folder!";
        }
    }
}

/* =========================
   LOAD CURRENT FOLDER
========================= */

$user_id = $_SESSION['user_id'] ?? 0;

/* Current folder details */
$currentFolder = null;

if ($current_folder_id) {
    $currentFolder = $myDrive->getFolderById($current_folder_id);
}

/* Get folders inside current folder */
if (!empty($search)) {

    $results = $myDrive->searchDrive(
        $user_id,
        $search
    );

} else {

    $folders = $myDrive->getFolders(
        $user_id,
        $current_folder_id
    );

    $files = $myDrive->getFiles(
        $user_id,
        $current_folder_id
    );
}

/* =========================
   RENAME FOLDERS (IMPORTANT FIX)
========================= */

if (isset($_POST['rename_folder'])) {

    $folder_id = $_POST['folder_id'];
    $new_name = trim($_POST['new_name']);

    if (!empty($new_name)) {
        $myDrive->renameFolder($folder_id, $new_name);
    }
}

/* =========================
   DELETE FOLDERS (IMPORTANT FIX)
========================= */

if (isset($_POST['delete_folder'])) {

    $folder_id = $_POST['folder_id'];

    $myDrive->deleteFolder($folder_id);
}

/* =========================
   upload files 
========================= */

if (isset($_POST['upload_file'])) {

    $user_id = $_SESSION['user_id'] ?? 0;

    // ALWAYS use current folder from URL
    $folder_id = $current_folder_id;

    // normalize root
    if ($folder_id === 0) {
        $folder_id = null;
    }

    if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        $file = $_FILES['file'];

        $result = $myDrive->uploadFile($user_id, $folder_id, $file);

        if ($result) {
            $_SESSION['success'] = "File uploaded successfully!";
        } else {
            $_SESSION['error'] = "File upload failed!";
        }

    } else {
        $_SESSION['error'] = "Please select a valid file!";
    }
}

/* =========================
   DELETE FILE
========================= */
if (isset($_POST['delete_file'])) {

    $file_id = (int) $_POST['file_id'];

    if ($myDrive->deleteFile($file_id)) {
        $_SESSION['success'] = "File deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete file.";
    }
}

/* =========================
   RENAME FILE
========================= */
if (isset($_POST['rename_file'])) {

    $file_id = (int) $_POST['file_id'];
    $new_name = trim($_POST['new_name']);

    if ($myDrive->renameFile($file_id, $new_name)) {
        $_SESSION['success'] = "File renamed successfully.";
    } else {
        $_SESSION['error'] = "Failed to rename file.";
    }
}

/* =========================
   share folder
========================= */
if (isset($_POST['share_folder'])) {

    $folder_id = (int) $_POST['folder_id'];
    $shared_to = (int) $_POST['shared_to'];
    $permission = $_POST['permission'];

    $owner_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        INSERT INTO folder_shares
        (
            folder_id,
            owner_id,
            shared_to,
            permission
        )
        VALUES
        (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iiis",
        $folder_id,
        $owner_id,
        $shared_to,
        $permission
    );

    $stmt->execute();
}

/* =========================
   share file
========================= */

if (isset($_POST['share_file'])) {

    $file_id = (int) $_POST['file_id'];
    $shared_to = (int) $_POST['shared_to'];
    $permission = $_POST['permission'];

    $owner_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        INSERT INTO file_shares
        (
            file_id,
            owner_id,
            shared_to,
            permission
        )
        VALUES
        (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iiis",
        $file_id,
        $owner_id,
        $shared_to,
        $permission
    );

    $stmt->execute();
}
?>

<!-- ===== Main Content Start ===== -->
<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

        <!-- Top Bar -->
        <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">

            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                    My Drive
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage your files and folders
                </p>
            </div>

            <div class="flex flex-wrap gap-2">

                <button onclick="toggleFolderModal(true)"
                    class="px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                    New Folder
                </button>

                <button onclick="toggleUploadModal(true)"
                    class="px-4 py-2 text-sm font-medium text-white bg-success-500 rounded-lg hover:bg-success-600">
                    Upload File
                </button>

            </div>
        </div>

        <!-- Search -->
        <form method="POST" class="mb-6">

            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Search files and folders..."
                class="w-full h-11 px-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-700 dark:text-white/90 focus:outline-none focus:ring-2 focus:ring-brand-500">

        </form>
        <?php if ($currentFolder): ?>

            <div class="mb-4">

                <a href="my_drive.php" class="text-blue-500 hover:underline">
                    My Drive
                </a>

                <span class="mx-2 text-gray-400">/</span>

                <span class="font-medium text-gray-800 dark:text-white">
                    <?= htmlspecialchars($currentFolder['folder_name']) ?>
                </span>

            </div>

        <?php endif; ?>
        <?php if (!empty($search)): ?>

            <div class="mb-8">

                <h2 class="mb-3 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    Search Results
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                    <?php if (!empty($results)): ?>

                        <?php foreach ($results as $item): ?>

                            <div class="p-4 bg-white border rounded-xl dark:bg-white/[0.03] dark:border-gray-800">

                                <div class="flex items-center gap-3">

                                    <div class="p-3 rounded-lg bg-yellow-100">

                                        <?= $item['item_type'] === 'folder' ? '📁' : '📄' ?>

                                    </div>

                                    <div>

                                        <p class="font-medium">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </p>

                                        <span class="text-xs text-gray-500">
                                            <?= ucfirst($item['item_type']) ?>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p class="text-sm text-gray-500">
                            No results found.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>
        <?php if (empty($search)): ?>
            <!-- Folders Section -->
            <div class="mb-8">
                <h2 class="mb-3 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    Folders
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                    <!-- Created folders -->
                    <?php if (!empty($folders)): ?>
                        <?php foreach ($folders as $folder): ?>

                            <div
                                class="p-4 bg-white border rounded-xl dark:bg-white/[0.03] dark:border-gray-800 hover:shadow-md flex items-center justify-between">

                                <!-- CLICKABLE FOLDER AREA -->
                                <a href="my_drive.php?folder_id=<?= $folder['id'] ?>" class="flex items-center gap-3 flex-1">

                                    <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-500/20">
                                        📁
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white/90">
                                            <?= htmlspecialchars($folder['folder_name']) ?>
                                        </p>

                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Folder
                                        </span>
                                    </div>

                                </a>

                                <!-- ACTION MENU -->
                                <div class="relative">

                                    <button type="button" onclick="toggleFolderMenu(this)"
                                        class="text-gray-500 hover:text-gray-800 dark:hover:text-white px-2">
                                        ⋮
                                    </button>

                                    <div
                                        class="hidden absolute right-0 mt-2 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow-lg w-32 z-50 folder-menu">

                                        <a href="my_drive.php?folder_id=<?= $folder['id'] ?>"
                                            class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Open
                                        </a>

                                        <button type="button"
                                            onclick="openRename('<?= $folder['id'] ?>','<?= htmlspecialchars($folder['folder_name'], ENT_QUOTES) ?>')"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Rename
                                        </button>

                                        <form method="POST">
                                            <input type="hidden" name="folder_id" value="<?= $folder['id'] ?>">

                                            <button type="submit" name="delete_folder"
                                                onclick="return confirm('Delete this folder?')"
                                                class="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                Delete
                                            </button>
                                        </form>

                                        <button type="button" onclick="toggleShareFolderModal(true, <?= $folder['id'] ?>)"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Share
                                        </button>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No folders found.
                        </p>

                    <?php endif; ?>

                    <!-- Uploaded files without folder -->

                    <?php if (!empty($files)): ?>
                        <?php foreach ($files as $file): ?>

                            <div
                                class="p-4 bg-white border rounded-xl dark:bg-white/[0.03] dark:border-gray-800 hover:shadow-md cursor-pointer flex items-center justify-between">

                                <div class="flex items-center gap-3">

                                    <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-500/20">
                                        📄
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white/90">
                                            <?= htmlspecialchars($file['original_name']) ?>
                                        </p>

                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <?= strtoupper($file['file_extension']) ?> File
                                        </span>
                                    </div>

                                </div>

                                <!-- ACTIONS -->
                                <div class="relative">

                                    <!-- Trigger Button -->
                                    <button type="button" onclick="toggleFileMenu(this)"
                                        class="text-gray-500 hover:text-gray-800 dark:hover:text-white px-2">
                                        ⋮
                                    </button>

                                    <!-- Dropdown -->
                                    <div
                                        class="hidden absolute right-0 mt-2 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow-lg w-36 z-50 file-menu">

                                        <!-- Download -->
                                        <a href="<?= $file['file_path'] ?>"
                                            download="<?= htmlspecialchars($file['original_name']) ?>"
                                            class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Download
                                        </a>

                                        <!-- Rename -->
                                        <button type="button" onclick="openRenameFile(
                '<?= $file['id'] ?>',
                '<?= htmlspecialchars($file['original_name'], ENT_QUOTES) ?>'
            )" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Rename
                                        </button>

                                        <!-- Delete -->
                                        <form method="POST">
                                            <input type="hidden" name="file_id" value="<?= $file['id'] ?>">

                                            <button type="submit" name="delete_file" onclick="return confirm('Delete this file?')"
                                                class="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                Delete
                                            </button>
                                        </form>

                                        <button type="button" onclick="toggleShareFileModal(true, <?= $file['id'] ?>)"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Share
                                        </button>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No files found.
                        </p>

                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>

        <!-- Files Table -->
        <div class="bg-white border rounded-2xl dark:bg-white/[0.03] dark:border-gray-800">

            <div class="flex items-center justify-between p-4 border-b dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-white/90">
                    Recent Files
                </h2>
            </div>

        </div>

    </div>
</main>
<!-- ===== Main Content End ===== -->
</div>
<!-- ===== Content Area End ===== -->
</div>


<?php include('includes/Modal.php'); ?>
<!-- ===== Page Wrapper End ===== -->
<script defer src="../assets/js/bundle.js"></script>
<script>
    function toggleFolderModal(show) {
        const modal = document.getElementById('folderModal');

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openRename(id, name) {
        document.getElementById('rename_folder_id').value = id;
        document.getElementById('rename_folder_name').value = name;

        document.getElementById('renameModal').classList.remove('hidden');
        document.getElementById('renameModal').classList.add('flex');
    }

    function toggleRename(show) {
        const modal = document.getElementById('renameModal');

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function closeAllMenus() {
        document.querySelectorAll('.folder-menu').forEach(menu => {
            menu.classList.add('hidden');
        });

        document.querySelectorAll('.file-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    /* Folder Menu */
    function toggleFolderMenu(button) {

        closeAllMenus();

        const menu = button.nextElementSibling;
        menu.classList.toggle('hidden');
    }

    /* File Menu */
    function toggleFileMenu(button) {

        closeAllMenus();

        const menu = button.nextElementSibling;
        menu.classList.toggle('hidden');
    }

    /* Close when clicking outside */
    document.addEventListener('click', function (e) {

        if (!e.target.closest('.relative')) {
            closeAllMenus();
        }

    });

    function toggleUploadModal(show) {
        const modal = document.getElementById('uploadModal');

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    /* =========================
       RENAME FILE MODAL
    ========================= */

    function toggleRenameFileModal(show) {

        const modal = document.getElementById('renameFileModal');

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openRenameFile(fileId, fileName) {

        document.getElementById('rename_file_id').value = fileId;
        document.getElementById('rename_file_name').value = fileName;

        toggleRenameFileModal(true);
    }

    /* =========================
   SHARE FOLDER MODAL
========================= */

    function toggleShareFolderModal(show, folderId = null) {
        const modal = document.getElementById('shareFolderModal');

        if (folderId !== null) {
            document.getElementById('share_folder_id').value = folderId;
        }

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('share_folder_id').value = '';
        }
    }

    /* =========================
       SHARE FILE MODAL
    ========================= */

    function toggleShareFileModal(show, fileId = null) {
        const modal = document.getElementById('shareFileModal');

        if (fileId !== null) {
            document.getElementById('share_file_id').value = fileId;
        }

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('share_file_id').value = '';
        }
    }
</script>

<?php include('includes/footer.php'); ?>