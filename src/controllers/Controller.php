<?php
class Controller {
    // Load model
    public function model($model) {
        $modelFile = dirname(dirname(__DIR__)) . '/src/models/' . $model . '.php';
        
        if (!file_exists($modelFile)) {
            throw new Exception("Model file not found: {$modelFile}");
        }
        
        require_once $modelFile;
        
        if (!class_exists($model)) {
            throw new Exception("Model class '{$model}' not found in file: {$modelFile}");
        }
        
        try {
            return new $model();
        } catch (Exception $e) {
            throw new Exception("Error creating instance of {$model}: " . $e->getMessage());
        }
    }

    // Load view
    public function view($view, $data = []) {
        // Extract data array to individual variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = dirname(dirname(__DIR__)) . '/src/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die('View does not exist: ' . $viewFile);
        }
        
        // Get the contents of the buffer and clean it
        $content = ob_get_clean();
        
        // Include the layout
        require_once dirname(dirname(__DIR__)) . '/src/views/layouts/main.php';
    }
    
    // Load view (alias for view method)
    public function loadView($view, $data = []) {
        // Extract data array to individual variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = dirname(dirname(__DIR__)) . '/src/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die('View does not exist: ' . $viewFile);
        }
        
        // Get the contents of the buffer and clean it
        $content = ob_get_clean();
        
        // Include the layout
        require_once dirname(dirname(__DIR__)) . '/src/views/layouts/main.php';
    }

    // Redirect to a specific URL
    protected function redirect($url) {
        header('Location: ' . URL_ROOT . $url);
        exit();
    }

    // Check if user is logged in
    protected function requireLogin() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Por favor, inicia sesión para acceder a esta página');
            $this->redirect('/login');
        }
    }

    // Check if user is admin
    protected function requireAdmin() {
        $this->requireLogin();
        if (!isAdmin()) {
            setFlashMessage('error', 'Acceso denegado: se requieren privilegios de administrador');
            $this->redirect('/');
        }
    }
}
