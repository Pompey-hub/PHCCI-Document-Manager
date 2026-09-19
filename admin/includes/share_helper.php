<?php

function getInheritedFolderShare(mysqli $conn, int $folderId, int $userId)
{
    while ($folderId > 0) {

        // Check whether THIS folder is shared
        $stmt = $conn->prepare("
            SELECT
                fs.permission,
                fs.owner_id,
                f.folder_name,
                f.parent_folder_id,
                u.first_name,
                u.last_name
            FROM folder_shares fs
            INNER JOIN folders f ON fs.folder_id = f.id
            INNER JOIN users u ON fs.owner_id = u.id
            WHERE fs.folder_id = ?
              AND fs.shared_to = ?
              AND f.status='active'
            LIMIT 1
        ");

        $stmt->bind_param("ii", $folderId, $userId);
        $stmt->execute();

        $share = $stmt->get_result()->fetch_assoc();

        if ($share) {
            return $share;
        }

        // Go to parent folder
        $stmt = $conn->prepare("
            SELECT parent_folder_id
            FROM folders
            WHERE id=?
            LIMIT 1
        ");

        $stmt->bind_param("i", $folderId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            break;
        }

        $folderId = (int) $row['parent_folder_id'];
    }

    return false;
}