<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    // Register page
    public function register()
    {
        if ($this->session->userdata('user_id')) {
            redirect('home');
        }

        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Register - Ecommerce';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/footer');
        } else {
            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'phone' => $this->input->post('phone'),
                'role' => 'customer'
            );

            $user_id = $this->User_model->create_user($user_data);

            if ($user_id) {
                $this->session->set_flashdata('success', 'Registration successful! Please login.');
                $this->session->mark_as_flash('success');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                $this->session->mark_as_flash('error');
                redirect('auth/register');
            }
        }
    }

    // Login page
    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('home');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Login - Ecommerce';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->verify_login($email, $password);

            if ($user) {
                $session_data = array(
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($session_data);

                if ($user->role == 'admin') {
                    redirect('admin/dashboard');
                } else {
                    redirect('home');
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password');
                $this->session->mark_as_flash('error');
                redirect('auth/login');
            }
        }
    }

    // Logout
    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'username', 'email', 'full_name', 'role', 'logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        $this->session->mark_as_flash('success');
        redirect('auth/login');
    }

    // Profile page
    public function profile()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['title'] = 'My Profile - Ecommerce';

        $this->load->model('Order_model');
        $data['order_count'] = count($this->Order_model->get_orders_by_user($user_id));
        $data['completed_orders'] = count($this->Order_model->get_orders_by_status('delivered'));
        $data['pending_orders'] = count($this->Order_model->get_orders_by_status('pending'));

        $this->load->view('templates/header', $data);
        $this->load->view('auth/profile', $data);
        $this->load->view('templates/footer');
    }

    // Update profile
    public function update_profile()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $this->session->mark_as_flash('error');
            redirect('auth/profile');
        } else {
            $email = $this->input->post('email');

            if ($this->User_model->email_exists($email, $user_id)) {
                $this->session->set_flashdata('error', 'Email already exists for another user.');
                $this->session->mark_as_flash('error');
                redirect('auth/profile');
            }

            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $email,
                'phone' => $this->input->post('phone')
            );

            if (!empty($_FILES['avatar']['name'])) {
                $config['upload_path'] = './uploads/avatars/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['file_name'] = 'avatar_' . $user_id . '_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('avatar')) {
                    $upload_data = $this->upload->data();
                    $user_data['avatar'] = 'uploads/avatars/' . $upload_data['file_name'];
                }
            }

            if ($this->User_model->update_user($user_id, $user_data)) {
                $this->session->set_userdata('full_name', $user_data['full_name']);
                $this->session->set_userdata('email', $user_data['email']);

                $this->session->set_flashdata('success', 'Profile updated successfully.');
                $this->session->mark_as_flash('success');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile.');
                $this->session->mark_as_flash('error');
            }

            redirect('auth/profile');
        }
    }
}
?>