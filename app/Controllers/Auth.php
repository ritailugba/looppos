<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->user) {
            return redirect()->to('/');
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->processLogin();
        }

        return $this->render('auth/login');
    }

    private function processLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (!$username || !$password) {
            $this->setMessage('error', 'Username and password are required');
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->getByUsername($username);

        if (!$user) {
            $user = $userModel->getByEmail($username);
        }

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            $this->setMessage('error', 'Invalid username or password');
            return redirect()->back()->withInput();
        }

        if ($user['status'] != 1) {
            $this->setMessage('error', 'Your account is inactive');
            return redirect()->back()->withInput();
        }

        // Set session data
        $this->session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'logged_in' => true
        ]);

        // Update last login
        $userModel->updateLastLogin($user['id']);

        $this->setMessage('success', 'Welcome back, ' . $user['first_name']);
        return redirect()->to('/');
    }

    public function logout()
    {
        $this->session->destroy();
        $this->setMessage('success', 'You have been logged out successfully');
        return redirect()->to('/auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->processRegister();
        }

        return $this->render('auth/register');
    }

    private function processRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'role' => 'user',
            'status' => 1
        ];

        if ($userModel->insert($userData)) {
            $this->setMessage('success', 'Account created successfully. Please login.');
            return redirect()->to('/auth/login');
        } else {
            $this->setMessage('error', 'Failed to create account. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->processForgotPassword();
        }

        return $this->render('auth/forgot_password');
    }

    private function processForgotPassword()
    {
        $email = $this->request->getPost('email');

        if (!$email) {
            $this->setMessage('error', 'Email is required');
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);

        if (!$user) {
            $this->setMessage('error', 'Email not found');
            return redirect()->back()->withInput();
        }

        // Generate reset token (you would implement this)
        // Send email (you would implement this)
        
        $this->setMessage('success', 'Password reset instructions sent to your email');
        return redirect()->to('/auth/login');
    }
}