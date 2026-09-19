<?php
include('includes/header.php');
include('includes/sidebar.php');
include('includes/navbar.php');
require_once('../config/database.php');

$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("
    SELECT id, first_name, last_name, email, role
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'update_profile'
) {

    $id = (int) $_POST['id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users
            SET first_name = ?, 
                last_name = ?, 
                email = ?, 
                password = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $first_name,
            $last_name,
            $email,
            $hashedPassword,
            $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE users
            SET first_name = ?, 
                last_name = ?, 
                email = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssi",
            $first_name,
            $last_name,
            $email,
            $id
        );
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

}
?>

<!-- ===== Main Content Start ===== -->
<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Profile`}">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName"></h2>

                <nav>
                    <ol class="flex items-center gap-1.5">
                        <li>
                            <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                                href="index.php">
                                Home
                                <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                        <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName"></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Breadcrumb End -->

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="flex flex-col items-center mb-8">

                <h3 class="mt-4 text-xl font-semibold text-gray-800 dark:text-white">
                    <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?>
                </h3>

                <p class="text-sm text-gray-500">
                    <?= htmlspecialchars($user['email']) ?>
                </p>

            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-700">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-700">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="user_profile.php" method="POST">

                <input type="hidden" name="action" value="update_profile">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                    <!-- Role -->
                    <div>
                        <label class="mb-2 block text-sm font-medium dark:text-white">
                            Role
                        </label>

                        <input type="text" value="<?= ucfirst($user['role']) ?>" disabled
                            class="w-full rounded-lg border bg-gray-100 px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-2 block text-sm font-medium dark:text-white">
                            Email
                        </label>

                        <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"
                            class="w-full rounded-lg border px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="mb-2 block text-sm font-medium dark:text-white">
                            First Name
                        </label>

                        <input type="text" name="first_name" required
                            value="<?= htmlspecialchars($user['first_name']) ?>"
                            class="w-full rounded-lg border px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="mb-2 block text-sm font-medium dark:text-white">
                            Last Name
                        </label>

                        <input type="text" name="last_name" required value="<?= htmlspecialchars($user['last_name']) ?>"
                            class="w-full rounded-lg border px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <!-- New Password -->
                    <div class="lg:col-span-2">
                        <label class="mb-2 block text-sm font-medium dark:text-white">
                            New Password
                        </label>

                        <input type="password" name="password"
                            placeholder="Leave blank if you don't want to change your password"
                            class="w-full rounded-lg border px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 text-white hover:bg-brand-600">
                        Update Profile
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>
<!-- ===== Main Content End ===== -->

<script defer src="../assets/js/bundle.js"></script>