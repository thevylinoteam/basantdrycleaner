<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Login status check endpoint
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        if (!$this->wantsJson()) {
            return view('auth/login');
        }

        return $this->response->setJSON([
            'status'  => 'info',
            'message' => 'Silakan masukkan email dan password untuk login.',
        ]);
    }

    /**
     * Process authentication
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Silakan masukkan email yang valid dan password.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $email    = (string) $this->request->getVar('email');
        $password = (string) $this->request->getVar('password');

        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
            ])->setStatusCode(401);
        }

        // Set session
        session()->set([
            'userId'     => $user['id'],
            'userName'   => $user['name'],
            'userEmail'  => $user['email'],
            'userRole'   => $user['role'],
            'isLoggedIn' => true,
        ]);

        if (!$this->wantsJson()) {
            return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . $user['name']);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'data'    => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ]);
    }

    /**
     * Logout and destroy session
     */
    public function logout()
    {
        session()->destroy();

        if (!$this->wantsJson()) {
            return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil. Sesi telah diakhiri.',
        ]);
    }

    /**
     * Get current user profile
     */
    public function profile()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
            ])->setStatusCode(404);
        }

        unset($user['password']);

        if (!$this->wantsJson()) {
            return view('auth/profile', ['user' => $user]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $user,
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $rules = [
            'current_password'     => 'required',
            'new_password'         => 'required|min_length[6]',
            'new_password_confirm' => 'permit_empty|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->with('error', 'Konfirmasi kata sandi tidak cocok atau kurang dari 6 karakter.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $currentPassword = (string) $this->request->getVar('current_password');
        $newPassword     = (string) $this->request->getVar('new_password');

        $user = $this->userModel->find($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            if (!$this->wantsJson()) {
                return redirect()->back()->with('error', 'Kata sandi saat ini salah.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Password saat ini salah.',
            ])->setStatusCode(400);
        }

        $this->userModel->update($userId, [
            'password' => $newPassword,
        ]);

        if (!$this->wantsJson()) {
            return redirect()->back()->with('success', 'Kata sandi Anda berhasil diperbarui.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}
