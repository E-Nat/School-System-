<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function model(string $model)
    {
        $class = 'App\\Models\\' . $model;
        return new $class();
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
