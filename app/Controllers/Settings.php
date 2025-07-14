<?php

namespace App\Controllers;

use App\Models\UserModel;

class Settings extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->requireAuth();

        $data = [
            'title' => 'Settings'
        ];

        return $this->render('settings/index', $data);
    }

    public function users()
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        $data = [
            'users' => $this->userModel->findAll(),
            'title' => 'User Management'
        ];

        return $this->render('settings/users', $data);
    }

    public function addUser()
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'role' => $this->request->getPost('role'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->insert($data)) {
                session()->setFlashdata('success', 'User added successfully');
            } else {
                session()->setFlashdata('error', 'Failed to add user');
            }

            return redirect()->to('/settings/users');
        }

        $data = [
            'title' => 'Add User'
        ];

        return $this->render('settings/add_user', $data);
    }

    public function editUser($id = null)
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        if (!$id) {
            return redirect()->to('/settings/users');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User not found');
            return redirect()->to('/settings/users');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'role' => $this->request->getPost('role'),
                'status' => $this->request->getPost('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Only update password if provided
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User updated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to update user');
            }

            return redirect()->to('/settings/users');
        }

        $data = [
            'user' => $user,
            'title' => 'Edit User'
        ];

        return $this->render('settings/edit_user', $data);
    }

    public function deleteUser($id = null)
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        if (!$id) {
            return redirect()->to('/settings/users');
        }

        // Don't allow deleting the current user
        if ($id == $this->user['id']) {
            session()->setFlashdata('error', 'You cannot delete your own account');
            return redirect()->to('/settings/users');
        }

        if ($this->userModel->delete($id)) {
            session()->setFlashdata('success', 'User deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete user');
        }

        return redirect()->to('/settings/users');
    }

    public function profile()
    {
        $this->requireAuth();

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Only update password if provided
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($this->user['id'], $data)) {
                session()->setFlashdata('success', 'Profile updated successfully');
                
                // Update session data
                $updatedUser = $this->userModel->find($this->user['id']);
                session()->set('user', $updatedUser);
            } else {
                session()->setFlashdata('error', 'Failed to update profile');
            }

            return redirect()->to('/settings/profile');
        }

        $data = [
            'user' => $this->user,
            'title' => 'My Profile'
        ];

        return $this->render('settings/profile', $data);
    }

    public function system()
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        if ($this->request->getMethod() === 'POST') {
            // Handle system settings update
            $settings = [
                'company_name' => $this->request->getPost('company_name'),
                'company_address' => $this->request->getPost('company_address'),
                'company_phone' => $this->request->getPost('company_phone'),
                'company_email' => $this->request->getPost('company_email'),
                'tax_rate' => $this->request->getPost('tax_rate'),
                'currency' => $this->request->getPost('currency'),
                'timezone' => $this->request->getPost('timezone')
            ];

            // Save settings to a configuration file or database
            // For now, we'll save to session as an example
            session()->set('system_settings', $settings);
            session()->setFlashdata('success', 'System settings updated successfully');

            return redirect()->to('/settings/system');
        }

        // Load current settings
        $settings = session()->get('system_settings') ?: [
            'company_name' => 'LoopPOS',
            'company_address' => '',
            'company_phone' => '',
            'company_email' => '',
            'tax_rate' => '10.00',
            'currency' => 'USD',
            'timezone' => 'UTC'
        ];

        $data = [
            'settings' => $settings,
            'title' => 'System Settings'
        ];

        return $this->render('settings/system', $data);
    }

    public function backup()
    {
        $this->requireAuth();
        $this->requireRole(['admin']);

        if ($this->request->getMethod() === 'POST') {
            // Generate database backup
            $db = \Config\Database::connect();
            $forge = \Config\Database::forge();
            
            $filename = 'looppos_backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // This is a simplified backup - in production you'd want to use mysqldump or similar
            session()->setFlashdata('success', 'Backup functionality would be implemented here');
            
            return redirect()->to('/settings/backup');
        }

        $data = [
            'title' => 'Backup & Restore'
        ];

        return $this->render('settings/backup', $data);
    }
}