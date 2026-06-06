<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Middleware\RoleMiddleware;

class DashboardController extends Controller
{
    public function home(): void
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? '';
            switch ($role) {
                case 'super_admin':
                case 'admin':
                case 'registrar':
                case 'dean':
                case 'department_head':
                case 'finance_staff':
                    $this->redirect('index.php?page=admin_dashboard');
                    break;
                case 'lecturer':
                    $this->redirect('index.php?page=lecturer_dashboard');
                    break;
                case 'student':
                    $this->redirect('index.php?page=student_dashboard');
                    break;
                default:
                    session_unset();
                    session_destroy();
                    $this->redirect('index.php?page=login');
            }
        }

        $this->redirect('index.php?page=login');
    }

    public function admin(): void
    {
        if (!isset($_SESSION['user']) || !RoleMiddleware::authorizeAdmin($_SESSION['user'])) {
            $this->redirect('index.php?page=login');
        }

        $stats = [
            'students' => 0,
            'lecturers' => 0,
            'courses' => 0,
            'faculties' => 0,
            'active_users' => 0,
        ];
        $this->view('dashboard/admin', ['stats' => $stats]);
    }

    public function lecturer(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'lecturer') {
            $this->redirect('index.php?page=login');
        }

        $this->view('dashboard/lecturer', []);
    }

    public function student(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            $this->redirect('index.php?page=login');
        }

        $this->view('dashboard/student', []);
    }
}
