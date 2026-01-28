<?php
if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    // VULNERABLE: No file type check
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.<br>";
        echo "Access it here: <a href='$target_file'>$target_file</a>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Upload Service</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        h1 { color: #0d6efd; margin-bottom: 20px; }
        input[type="file"] { margin: 20px 0; width: 100%; }
        input[type="submit"] { background-color: #0d6efd; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; transition: background-color 0.2s; }
    </div>
        input[type="submit"]:hover { background-color: #0b5ed7; }
        .alert { margin-bottom: 20px; padding: 10px; border-radius: 4px; text-align: left; }
        .alert a { color: #0d6efd; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
    <h1>Free File Hosting</h1>
    <form method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="upload">
    </form>
</body>
</html>