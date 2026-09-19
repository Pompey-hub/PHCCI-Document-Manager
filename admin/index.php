<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
require_once('../config/database.php');

$user_id = $_SESSION['user_id'];

/* Total Folders */
$totalFolders = $conn->query("
    SELECT COUNT(*) AS total
    FROM folders
    WHERE user_id = $user_id
    AND status = 'active'
")->fetch_assoc()['total'];

/* Total Files */
$totalFiles = $conn->query("
    SELECT COUNT(*) AS total
    FROM files
    WHERE user_id = $user_id
    AND status = 'active'
")->fetch_assoc()['total'];

/* Total Users */
$totalUsers = $conn->query("
    SELECT COUNT(*) AS total
    FROM users
")->fetch_assoc()['total'];

/* Total Storage */
$totalStorage = $conn->query("
    SELECT IFNULL(SUM(file_size), 0) AS total
    FROM files
    WHERE user_id = $user_id
    AND status = 'active'
")->fetch_assoc()['total'];

function formatBytes($bytes)
{
  if ($bytes >= 1073741824) {
    return round($bytes / 1073741824, 2) . ' GB';
  }

  if ($bytes >= 1048576) {
    return round($bytes / 1048576, 2) . ' MB';
  }

  if ($bytes >= 1024) {
    return round($bytes / 1024, 2) . ' KB';
  }

  return $bytes . ' B';
}
?>
<main>
  <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">

      <!-- Total Folders -->
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              Total Folders
            </span>

            <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
              <?= number_format($totalFolders) ?>
            </h4>
          </div>

          <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-yellow-100 text-2xl">
            📁
          </div>
        </div>
      </div>

      <!-- Total Files -->
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              Total Files
            </span>

            <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
              <?= number_format($totalFiles) ?>
            </h4>
          </div>

          <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-2xl">
            📄
          </div>
        </div>
      </div>

      <!-- Total Users -->
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              Total Users
            </span>

            <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
              <?= number_format($totalUsers) ?>
            </h4>
          </div>

          <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-2xl">
            👥
          </div>
        </div>
      </div>

      <!-- Storage Used -->
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              Storage Used
            </span>

            <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
              <?= formatBytes($totalStorage ?? 0) ?>
            </h4>
          </div>

          <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-purple-100 text-2xl">
            💾
          </div>
        </div>
      </div>

    </div>


    <div class="mt-6 bg-white dark:bg-white/[0.03] p-6 rounded-2xl border dark:border-gray-800">

      <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
        Recent Uploads
      </h3>

      <div class="overflow-x-auto">

        <table class="w-full">

          <thead>
            <tr class="border-b dark:border-gray-800">
              <th class="p-3 text-left">File Name</th>
              <th class="p-3 text-left">Type</th>
              <th class="p-3 text-left">Size</th>
              <th class="p-3 text-left">Uploaded</th>
            </tr>
          </thead>

          <tbody>

            <?php
            $recentFiles = $conn->query("
                    SELECT *
                    FROM files
                    WHERE status='active'
                    ORDER BY uploaded_at DESC
                    LIMIT 5
                ");

            while ($file = $recentFiles->fetch_assoc()):
              ?>

              <tr class="border-b dark:border-gray-800">

                <td class="p-3">
                  <?= htmlspecialchars($file['original_name']) ?>
                </td>

                <td class="p-3">
                  <?= strtoupper($file['file_extension']) ?>
                </td>

                <td class="p-3">
                  <?= formatBytes($file['file_size']) ?>
                </td>

                <td class="p-3">
                  <?= date('M d, Y', strtotime($file['uploaded_at'])) ?>
                </td>

              </tr>

            <?php endwhile; ?>

          </tbody>

        </table>

      </div>

    </div>
  </div>
</main>

<script defer src="../assets/js/bundle.js"></script>

<?php include('includes/footer.php'); ?>