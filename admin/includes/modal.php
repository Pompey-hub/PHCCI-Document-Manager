<!-- ===== New Folder Modal ===== -->
<div id="folderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl dark:bg-gray-900">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Create New Folder
            </h2>

            <button onclick="toggleFolderModal(false)" class="text-gray-500 hover:text-red-500">
                ✖
            </button>
        </div>

        <!-- Form -->
        <form action="my_drive.php<?= $current_folder_id ? '?folder_id=' . $current_folder_id : '' ?>" method="POST">

            <input type="hidden" name="parent_folder_id" value="<?= $current_folder_id ?>">

            <div class="mb-4">
                <label class="block mb-2 text-sm text-gray-600 dark:text-gray-300">
                    Folder Name
                </label>

                <input type="text" name="folder_name" required
                    class="w-full px-4 py-2 text-sm border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                    placeholder="Enter folder name">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleFolderModal(false)"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>

                <button type="submit" name="create_folder"
                    class="px-4 py-2 text-sm text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                    Create
                </button>
            </div>

        </form>

    </div>
</div>

<div id="renameModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">

    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-semibold mb-4">Rename Folder</h2>

        <form method="POST">

            <input type="hidden" name="folder_id" id="rename_folder_id">

            <input type="text" name="new_name" id="rename_folder_name"
                class="w-full p-2 border rounded mb-4 dark:bg-gray-800" required>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleRename(false)" class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit" name="rename_folder" class="px-4 py-2 bg-gray-300 rounded">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">

    <form method="POST" enctype="multipart/form-data"
        class="bg-white dark:bg-gray-900 w-[420px] p-6 rounded-lg shadow-lg">

        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Upload File
        </h2>

        <!-- Folder ID (optional - default root) -->
        <input type="hidden" name="folder_id" id="upload_folder_id" value="">

        <!-- File Input -->
        <input type="file" name="file" accept=".zip,.rar,.7z" required
            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg p-2 mb-2">
        <p class="text-xs text-gray-500">
            Allowed formats: ZIP, RAR, 7Z (Maximum 20 MB)
        </p>

        <!-- Actions -->
        <div class="flex justify-end gap-2">

            <button type="button" onclick="toggleUploadModal(false)"
                class="px-4 py-2 text-sm text-gray-600 hover:text-black dark:text-gray-300">
                Cancel
            </button>

            <button type="submit" name="upload_file"
                class="px-4 py-2 text-sm text-white bg-success-500 rounded-lg hover:bg-success-600">
                Upload
            </button>

        </div>

    </form>
</div>

<!-- =========================
     RENAME FILE MODAL
========================= -->
<div id="renameFileModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black/50">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg dark:bg-gray-900">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Rename File
            </h3>

            <button type="button" onclick="toggleRenameFileModal(false)" class="text-gray-500 hover:text-red-500">
                ✕
            </button>
        </div>

        <form method="POST">

            <input type="hidden" name="file_id" id="rename_file_id">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    File Name
                </label>

                <input type="text" name="new_name" id="rename_file_name" required
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div class="flex justify-end gap-2">

                <button type="button" onclick="toggleRenameFileModal(false)"
                    class="px-4 py-2 text-sm border rounded-lg">
                    Cancel
                </button>

                <button type="submit" name="rename_file"
                    class="px-4 py-2 text-sm text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                    Save Changes
                </button>

            </div>

        </form>

    </div>
</div>
<!-- =========================
     Share MODAL
========================= -->
<div id="shareFolderModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black/50">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg dark:bg-gray-900">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Share Folder
            </h3>

            <button type="button" onclick="toggleShareFolderModal(false)" class="text-gray-500 hover:text-red-500">
                ✕
            </button>
        </div>

        <form method="POST">

            <input type="hidden" name="folder_id" id="share_folder_id">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    User
                </label>

                <select name="shared_to" required
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                    <option value="">Select User</option>

                    <?php
                    $users = $conn->query("
                        SELECT id, first_name, last_name
                        FROM users
                        WHERE id != $user_id
                    ");

                    while ($u = $users->fetch_assoc()):
                        ?>
                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Permission
                </label>

                <select name="permission"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                    <option value="view">View</option>

                </select>
            </div>

            <div class="flex justify-end gap-2">

                <button type="button" onclick="toggleShareFolderModal(false)"
                    class="px-4 py-2 text-sm border rounded-lg">
                    Cancel
                </button>

                <button type="submit" name="share_folder"
                    class="px-4 py-2 text-sm text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                    Share
                </button>

            </div>

        </form>

    </div>

</div>

<!-- ============================= -->
<!-- SHARE FILE MODAL -->
<!-- ============================= -->
<div id="shareFileModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black/50">

    <div class="w-full max-w-md p-6 bg-white rounded-2xl dark:bg-gray-900">

        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
            Share File
        </h3>

        <form method="POST">

            <input type="hidden" name="file_id" id="share_file_id">

            <div class="mb-4">

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    User
                </label>

                <select name="shared_to"
                    class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>

                    <option value="">Select User</option>

                    <?php
                    $users = $conn->query("
                        SELECT id, first_name, last_name
                        FROM users
                        WHERE id != $user_id
                        ORDER BY first_name, last_name
                    ");

                    while ($u = $users->fetch_assoc()):
                        ?>

                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Permission
                </label>

                <select name="permission"
                    class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                    <option value="view">View Only</option>
                    <option value="edit">Can Edit</option>

                </select>

            </div>

            <div class="flex justify-end gap-2">

                <button type="button" onclick="toggleShareFileModal(false)"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">

                    Cancel

                </button>

                <button type="submit" name="share_file"
                    class="px-4 py-2 text-white rounded-lg bg-brand-500 hover:bg-brand-600">

                    Share

                </button>

            </div>

        </form>

    </div>

</div>