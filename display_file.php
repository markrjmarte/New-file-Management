<?php
session_start();
include 'db_connect.php';

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];
    $fileQuery = $conn->query("SELECT * FROM files WHERE id = $fileId ");
    if ($fileQuery->num_rows > 0) {
        $fileData = $fileQuery->fetch_assoc();
        
        // Get the file name and type from the database
        $fileName = $fileData['file_path'];
        $fileType = $fileData['file_type'];

        // Build the complete file path dynamically based on the stored file name
        $filePath = 'assets1/uploads/' . $fileName;

        try {
            if (file_exists($filePath)) {
                // Display the PDF viewer using the <embed> tag
                echo '<embed src="' . $filePath . '" width="100%" height="800px" type="application/pdf">';
            } else {
                echo 'File not found.';
            }
        } catch (Exception $e) {
            echo 'An error occurred: ' . $e->getMessage();
        }
    } else {
        echo 'File not found or unauthorized access.';
    }
} else {
    echo 'Invalid file ID.';
}
?>
