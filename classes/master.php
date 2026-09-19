<?php
class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "phcci_document_manager";
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

class myDrive
{

    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "phcci_document_manager");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function createFolder($user_id, $folderName, $parent_id = null)
    {
        $stmt = $this->conn->prepare("
        INSERT INTO folders (
            user_id,
            parent_folder_id,
            folder_name
        )
        VALUES (?, ?, ?)
    ");

        $stmt->bind_param(
            "iis",
            $user_id,
            $parent_id,
            $folderName
        );

        return $stmt->execute();
    }

    /* =========================
        RENAME FOLDER
    ========================= */
    public function renameFolder($folder_id, $new_name)
    {
        $new_name = $this->conn->real_escape_string($new_name);

        return $this->conn->query("
            UPDATE folders 
            SET folder_name = '$new_name'
            WHERE id = '$folder_id'
        ");
    }

    /* =========================
        DELETE FOLDER (SOFT DELETE)
    ========================= */
    public function deleteFolder($folder_id)
    {

        return $this->conn->query("
            UPDATE folders 
            SET status = 'deleted'
            WHERE id = '$folder_id'
        ");
    }

    /* =========================
        Files Functions
    ========================= */

    public function uploadFile($user_id, $folder_id, $file)
    {
        $uploadDir = "../uploads/" . $user_id . "/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = $file["name"];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $fileType = $file["type"];
        $fileSize = $file["size"];

        $allowedExtensions = ['zip', 'rar', '7z'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['error'] = "Invalid file type.";
            return false;
        }

        $storedName = time() . "_" . uniqid() . "." . $fileExtension;
        $filePath = $uploadDir . $storedName;

        if (!move_uploaded_file($file["tmp_name"], $filePath)) {
            $_SESSION['error'] = "Failed to move uploaded file.";
            return false;
        }

        // SAFE folder_id handling (IMPORTANT FIX)
        $folder_id = !empty($folder_id) ? $folder_id : NULL;

        $stmt = $this->conn->prepare("
        INSERT INTO files (
            user_id,
            folder_id,
            file_name,
            original_name,
            file_extension,
            file_type,
            file_size,
            file_path,
            uploaded_at,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'active')
    ");

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "iissssis",
            $user_id,
            $folder_id,
            $storedName,
            $originalName,
            $fileExtension,
            $fileType,
            $fileSize,
            $filePath
        );

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return true;
    }

    /* =========================
    RENAME FILE
========================= */
    public function renameFile($file_id, $new_name)
    {
        $new_name = $this->conn->real_escape_string($new_name);

        $result = $this->conn->query("
        SELECT file_extension
        FROM files
        WHERE id = '$file_id'
        LIMIT 1
    ");

        if ($row = $result->fetch_assoc()) {

            $extension = $row['file_extension'];

            // Add extension if user omitted it
            if (pathinfo($new_name, PATHINFO_EXTENSION) == '') {
                $new_name .= '.' . $extension;
            }

            return $this->conn->query("
            UPDATE files
            SET original_name = '$new_name'
            WHERE id = '$file_id'
        ");
        }

        return false;
    }

    /* =========================
    DELETE FILE (SOFT DELETE)
========================= */
    public function deleteFile($file_id)
    {
        return $this->conn->query("
        UPDATE files
        SET status = 'deleted'
        WHERE id = '$file_id'
    ");
    }

    /* =========================
    GET SINGLE FOLDER
========================= */
    public function getFolderById($folder_id)
    {
        $folder_id = (int) $folder_id;

        $result = $this->conn->query("
        SELECT *
        FROM folders
        WHERE id = $folder_id
        LIMIT 1
    ");

        return $result->fetch_assoc();
    }

    public function getFolders($user_id, $parent_folder_id = null)
    {
        $user_id = (int) $user_id;

        if ($parent_folder_id === null) {

            $sql = "
            SELECT *
            FROM folders
            WHERE user_id = $user_id
            AND parent_folder_id IS NULL
            AND status = 'active'
            ORDER BY folder_name ASC
        ";

        } else {

            $parent_folder_id = (int) $parent_folder_id;

            $sql = "
            SELECT *
            FROM folders
            WHERE user_id = $user_id
            AND parent_folder_id = $parent_folder_id
            AND status = 'active'
            ORDER BY folder_name ASC
        ";
        }

        $result = $this->conn->query($sql);

        $folders = [];

        while ($row = $result->fetch_assoc()) {
            $folders[] = $row;
        }

        return $folders;
    }

    public function getFiles($user_id, $folder_id = null)
    {
        $user_id = (int) $user_id;

        if ($folder_id === null) {

            $sql = "
            SELECT *
            FROM files
            WHERE user_id = $user_id
            AND folder_id IS NULL
            AND status = 'active'
            ORDER BY uploaded_at DESC
        ";

        } else {

            $folder_id = (int) $folder_id;

            $sql = "
            SELECT *
            FROM files
            WHERE user_id = $user_id
            AND folder_id = $folder_id
            AND status = 'active'
            ORDER BY uploaded_at DESC
        ";
        }

        $result = $this->conn->query($sql);

        $files = [];

        while ($row = $result->fetch_assoc()) {
            $files[] = $row;
        }

        return $files;
    }

    /* =========================
   SEARCH FILES & FOLDERS
========================= */
    public function searchDrive($user_id, $keyword)
    {
        $keyword = "%{$keyword}%";

        $stmt = $this->conn->prepare("
        SELECT
            id,
            folder_name AS name,
            'folder' AS item_type,
            created_at AS item_date
        FROM folders
        WHERE user_id = ?
        AND status = 'active'
        AND folder_name LIKE ?

        UNION ALL

        SELECT
            id,
            original_name AS name,
            'file' AS item_type,
            uploaded_at AS item_date
        FROM files
        WHERE user_id = ?
        AND status = 'active'
        AND original_name LIKE ?

        ORDER BY item_date DESC
    ");

        $stmt->bind_param(
            "isis",
            $user_id,
            $keyword,
            $user_id,
            $keyword
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }



}
