<?php
// HomeController.php - Process data
class HomeController {
    public function index() {
        // Process data here
        $message = "Hello World";
        $timestamp = date('Y-m-d H:i:s');
        
        // Pass data to view
        return [
            'message' => $message,
            'timestamp' => $timestamp
        ];
    }
}
?>
