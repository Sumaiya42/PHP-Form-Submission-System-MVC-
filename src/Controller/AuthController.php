<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;
use App\Core\Validator;
use App\Model\UserModel;

class AuthController extends BaseController
{
    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        $this->render('auth/login', ['title' => 'Login']);
    }

    public function signup(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        $this->render('auth/signup', ['title' => 'Sign Up']);
    }

    public function handleSignup(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $validator = new Validator();

        if (!$validator->validateSignup($input)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->getErrors()
            ], 400);
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($input['email'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Email already registered.'], 409);
        }

        $passwordHash = password_hash($input['password'], PASSWORD_DEFAULT);
        $userId = $userModel->create($input['name'], $input['email'], $passwordHash);

        if ($userId) {
            $this->jsonResponse(['success' => true, 'message' => 'Registration successful.'], 201);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Registration failed due to a server error.'], 500);
        }
    }

    public function handleLogin(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $validator = new Validator();

        if (!$validator->validateLogin($input)) {
            $this->jsonResponse(['success' => false, 'message' => 'Validation failed.'], 400);
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($input['email']);

        if ($user && password_verify($input['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            $this->jsonResponse(['success' => true, 'message' => 'Login successful.'], 200);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid email or password.'], 401);
        }
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
}
