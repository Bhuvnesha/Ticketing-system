<?php
namespace App\Controllers;
use App\Models\UserModel;
use Config\Services;

class UserController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
        helper('form');
    }

    // Register a new user
    public function register()
    {
        // Redirect if already logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/tickets');
        }
        
        if ($this->request->getMethod() == 'post') {
            // Validate input
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                return view('register', [
                    'validation' => $this->validator
                ]);
            }

            // Save user to database
            $data = [
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => 'user' // Default role
            ];

            $this->model->save($data);
            session()->setFlashdata('success', 'Registration successful! Please login.');
            return redirect()->to('/login');
        }

        return view('register');
    }

    // Login user
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/tickets');
        }

        if ($this->request->getMethod() == 'post') {
            // Validate input
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return view('login', [
                    'validation' => $this->validator
                ]);
            }

            // Check credentials
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $this->model->where('email', $email)->first();

            if (!$user || !password_verify($password, $user['password'])) {
                session()->setFlashdata('error', 'Invalid email or password.');
                return redirect()->back();
            }

            // Set user session
            $sessionData = [
                'user_id'  => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'    => $user['role'],
                'logged_in' => true
            ];
            session()->set($sessionData);

            // Redirect to dashboard
            return redirect()->to('/tickets');
        }

        return view('login');
    }

    // Logout user
    public function logout()
    {
        $session = session();
        session()->destroy();
        return redirect()->to('/login');
    }


    // app/Controllers/UserController.php

// Forgot Password Form
public function forgotPassword()
{
    if ($this->request->getMethod() == 'post') {
        $email = $this->request->getPost('email');
        $user = $this->model->where('email', $email)->first();

        if ($user) {
            // Generate token (valid for 1 hour)
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Debug: Check if user exists before update
            if (!$user) {
                log_message('error', "User not found for email: {$email}");
                session()->setFlashdata('error', 'Email not registered!');
                return redirect()->back();
            }

            // Right before $this->model->update()
                log_message('debug', "Updating user ID: {$user['id']} with token: {$token}");
                log_message('debug', "User data: " . print_r($user, true));

            // Update user record
            $updated = $this->model->update($user['id'], [
                'reset_token' => $token,
                'reset_expires_at' => $expires
            ]);

            if (!$updated) {
                log_message('error', "Failed to update user ID: {$user['id']}");
                session()->setFlashdata('error', 'Failed to generate reset link.');
                return redirect()->back();
            }

            // Send email
            $emailService = \Config\Services::email();
            $emailService->setFrom('bhuvneshanand28@gmail.com', 'Bhuvnesh');
            $emailService->setTo($user['email']);
            $emailService->setSubject('Password Reset Request');
            $emailService->setMessage("
                Click to reset your password:
                " . base_url("reset-password/{$token}")
            );

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Reset link sent to your email!');
            } else {
                log_message('error', "Email failed to send to: {$user['email']}");
                session()->setFlashdata('error', 'Failed to send email.');
            }

            return redirect()->to('/login');
        }

        session()->setFlashdata('error', 'Email not found!');
    }

    return view('auth/forgot_password');
}

// Reset Password Form
public function resetPassword($token)
{
    $user = $this->model->where('reset_token', $token)
                       ->where('reset_expires_at >', date('Y-m-d H:i:s'))
                       ->first();

    if (!$user) {
        session()->setFlashdata('error', 'Invalid/expired token!');
        return redirect()->to('/forgot-password');
    }

    if ($this->request->getMethod() == 'post') {
        $rules = [
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if ($this->validate($rules)) {
            $this->model->update($user['id'], [
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expires_at' => null
            ]);

            session()->setFlashdata('success', 'Password updated! Login with new password.');
            return redirect()->to('/login');
        }
    }

    return view('auth/reset_password', ['token' => $token]);
}

public function profile()
{
    $userId = session()->get('user_id');
    $user = $this->model->find($userId);

    if ($this->request->getMethod() == 'post') {
        $rules = [
            'username' => "required|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];

        if ($this->validate($rules)) {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email')
            ];

            // Only update password if provided
            if ($this->request->getPost('password')) {
                $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $this->model->update($userId, $data);
            session()->setFlashdata('success', 'Profile updated!');
            return redirect()->to('/profile');
        }
    }

    return view('auth/profile', ['user' => $user]);
}

}