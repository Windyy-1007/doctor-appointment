<?php

class Controller
{
    // Render a view inside the main layout
    protected function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new RuntimeException("View {$view} not found");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        include __DIR__ . '/../views/layouts/main.php';
    }

    // Redirect to a path relative to the BASE_URL constant
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
