<?php
// Simulate Expect Extension for Educational Purposes
// This allows the lab to demonstrate XXE to RCE without the brittle 'pecl install expect' build process.
if (!in_array("expect", stream_get_wrappers())) {
    class ExpectWrapper {
        public $context;
        private $data = '';
        private $position = 0;

        public function stream_open($path, $mode, $options, &$opened_path) {
            // Parse command from expect://command
            $command = substr($path, 9);
            // Execute command and return output
            // WARNING: This is intentional RCE for the lab
            $output = shell_exec($command);
            if ($output === null) {
                $output = "";
            }
            $this->data = $output;
            $this->position = 0;
            return true;
        }
        public function stream_read($count) {
            $ret = substr($this->data, $this->position, $count);
            $this->position += strlen($ret);
            return $ret;
        }
        public function stream_eof() {
            return $this->position >= strlen($this->data);
        }
        public function stream_stat() {
            $stats = [
                'dev' => 0, 'ino' => 0, 'mode' => 33188, 'nlink' => 1,
                'uid' => 0, 'gid' => 0, 'rdev' => 0, 'size' => strlen($this->data),
                'atime' => 0, 'mtime' => 0, 'ctime' => 0, 'blksize' => -1, 'blocks' => -1
            ];
            // PHP stream_stat expects both associative and numeric indices
            $result = array_values($stats);
            foreach ($stats as $key => $value) {
                $result[$key] = $value;
            }
            return $result;
        }

        public function url_stat($path, $flags) {
            $stats = [
                'dev' => 0, 'ino' => 0, 'mode' => 33188, 'nlink' => 1,
                'uid' => 0, 'gid' => 0, 'rdev' => 0, 'size' => 100,
                'atime' => time(), 'mtime' => time(), 'ctime' => time(), 'blksize' => -1, 'blocks' => -1
            ];
            $result = array_values($stats);
            foreach ($stats as $key => $value) {
                $result[$key] = $value;
            }
            return $result;
        }
    }
    // Register the wrapper if not already present
    if (!in_array("expect", stream_get_wrappers())) {
        stream_wrapper_register("expect", "ExpectWrapper");
    }
}

// Function to check if the 'expect' extension is loaded or simulated
$expect_loaded = extension_loaded('expect') || in_array("expect", stream_get_wrappers());

$output = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xmlData = '';
    
    // 1. Try to get from POST parameter (standard form submission)
    if (isset($_POST['xml'])) {
        $xmlData = $_POST['xml'];
    } 
    // 2. Try raw input (curl -d @file.xml or similar)
    else {
        $rawInput = file_get_contents('php://input');
        if (!empty($rawInput)) {
            $xmlData = $rawInput;
        }
    }

    $xmlData = trim($xmlData);

    // Aggressive cleaning: find the first '<' to handle cases where raw POST body `xml=...` is processed manually
    // or if there is garbage before the XML declaration.
    $firstTag = strpos($xmlData, '<');
    if ($firstTag !== false) {
        $xmlData = substr($xmlData, $firstTag);
    }

    if (!empty($xmlData)) {
        // Allow external entities
        if (function_exists('libxml_disable_entity_loader')) {
            libxml_disable_entity_loader(false);
        }
        
        $dom = new DOMDocument();
        $dom->resolveExternals = true;
        $dom->substituteEntities = true; 
        
        // Use internal errors to suppress warnings and capture them
        libxml_use_internal_errors(true);
        $loaded = $dom->loadXML($xmlData, LIBXML_NOENT | LIBXML_DTDLOAD);
        
        if ($loaded) {
            $creds = simplexml_import_dom($dom);
            if ($creds && $creds->user) {
                $user = (string)$creds->user;
                $output = "Login failed for user: " . htmlspecialchars($user); 
            } else {
                $output = "Invalid XML format: Missing <user> tag.";
            }
        } else {
            $errors = libxml_get_errors();
            $output = "<strong>XML Parse Error(s):</strong><br>";
            foreach ($errors as $error) {
                $output .= htmlspecialchars($error->message) . "<br>";
            }
            libxml_clear_errors();
            
            // Debugging aid:
            $output .= "<br><small>Received Data Start (Hex): " . bin2hex(substr($xmlData, 0, 16)) . "</small>";
            $output .= "<br><small>Received Data Start (Text): " . htmlspecialchars(substr($xmlData, 0, 100)) . "</small>";
        }
    } else {
        $output = "No XML data received.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>XXE Lab</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h1 { color: #333; text-align: center; }
        textarea { width: 100%; height: 150px; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; }
        button { background-color: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background-color: #c82333; }
        .info { font-size: 0.9em; color: #666; margin-top: 10px; background: #fff3cd; padding: 10px; border-radius: 4px; }
        .output { margin-top: 20px; padding: 15px; background: #e2e3e5; border-radius: 4px; border-left: 5px solid #383d41; }
    </style>
</head>
<body>
    <div class="container">
        <h1>XML Parser</h1>
        <p>System Status: <strong><?php echo $expect_loaded ? 'EXPECT ENABLED (RCE Possible via expect://id)' : 'EXPECT DISABLED (File Read Only)'; ?></strong></p>
        
        <form method="post">
            <label>Submit User XML:</label>
            <textarea name="xml">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;creds&gt;
    &lt;user&gt;admin&lt;/user&gt;
    &lt;pass&gt;secret&lt;/pass&gt;
&lt;/creds&gt;</textarea>
            <button type="submit">Parse XML</button>
        </form>

        <?php if ($output): ?>
            <div class="output">
                <strong>Result:</strong> <?php echo htmlspecialchars($output); ?>
            </div>
        <?php endif; ?>
        
        <div class="info">
            Hint: Try defining an entity in the DTD! <br>
            <code>&lt;!DOCTYPE foo [&lt;!ENTITY xxe SYSTEM "file:///etc/passwd" &gt; ]&gt;</code> <br>
            If 'expect' is enabled: <code>expect://id</code>
        </div>
    </div>
</body>
</html>
