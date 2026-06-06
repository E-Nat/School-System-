<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Middleware\RoleMiddleware;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? '';
            if (in_array($role, RoleMiddleware::ALL_ROLES, true)) {
                $this->redirect('index.php');
            }

            session_unset();
            session_destroy();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid CSRF token.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if ($email && $password) {
                    $userModel = new User();
                    $user = $userModel->findByEmail($email);

                    if ($user && password_verify($password, $user['password'])) {
                        unset($user['password']);
                        $_SESSION['user'] = $user;
                        $userModel->logLogin($user['id'], $_SERVER['REMOTE_ADDR'] ?? 'unknown');

                        switch ($user['role']) {
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
                                $this->redirect('index.php');
                                break;
                        }
                    }

                    $error = 'Invalid email or password.';
                }
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php?page=login');
    }

    public function register(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('index.php');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid CSRF token.';
            } else {
                $data = [
                    'first_name' => Security::sanitize($_POST['first_name'] ?? ''),
                    'last_name' => Security::sanitize($_POST['last_name'] ?? ''),
                    'email' => Security::sanitize($_POST['email'] ?? ''),
                    'password' => $_POST['password'] ?? '',
                    'role' => Security::sanitize($_POST['role'] ?? 'student'),
                    'phone' => Security::sanitize($_POST['phone'] ?? ''),
                    'student_number' => Security::sanitize($_POST['student_number'] ?? ''),
                    'employee_id' => Security::sanitize($_POST['employee_id'] ?? ''),
                    'department' => Security::sanitize($_POST['department'] ?? ''),
                    'subject_area' => Security::sanitize($_POST['subject_area'] ?? ''),
                ];

                if ($data['password'] !== (($_POST['confirm_password'] ?? ''))) {
                    $error = 'Passwords do not match.';
                } elseif ($data['role'] !== 'student' && $data['role'] !== 'lecturer' && empty($data['employee_id'])) {
                    $error = 'Employee ID is required for staff and lecturer accounts.';
                } elseif ($data['role'] === 'student' && empty($data['student_number'])) {
                    $error = 'Student number is required for student accounts.';
                } else {
                    $userModel = new User();
                    if ($userModel->findByEmail($data['email'])) {
                        $error = 'Email is already registered.';
                    } else {
                        $userId = $userModel->create($data);

                        if ($data['role'] === 'lecturer') {
                            $lecturerModel = new Lecturer();
                            $lecturerModel->createProfile($userId, $data['employee_id'], $data['department'] ?: 'Lecturer');
                        }

                        if ($data['role'] === 'student') {
                            $studentModel = new Student();
                            $studentModel->createProfile($userId, $data['student_number']);
                        }

                        $this->redirect('index.php?page=login');
                    }
                }
            }
        }

        $this->view('auth/register', ['error' => $error]);
    }
}
