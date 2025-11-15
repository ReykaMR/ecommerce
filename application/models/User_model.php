<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all users
    public function get_all_users()
    {
        return $this->db->get('users')->result();
    }

    // Get user by ID
    public function get_user_by_id($user_id)
    {
        return $this->db->get_where('users', array('user_id' => $user_id))->row();
    }

    // Get user by email
    public function get_user_by_email($email)
    {
        return $this->db->get_where('users', array('email' => $email))->row();
    }

    // Get user by username
    public function get_user_by_username($username)
    {
        return $this->db->get_where('users', array('username' => $username))->row();
    }

    // Create new user
    public function create_user($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // Update user
    public function update_user($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Delete user
    public function delete_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('users');
    }

    // Verify login
    public function verify_login($email, $password)
    {
        $user = $this->get_user_by_email($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // Check if email exists
    public function email_exists($email, $exclude_user_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_user_id) {
            $this->db->where('user_id !=', $exclude_user_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    // Get user by ID with related data
    public function get_user_with_related_data($user_id)
    {
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return null;
        }

        $this->db->select('o.order_id, p.payment_proof');
        $this->db->from('orders o');
        $this->db->join('payments p', 'o.order_id = p.order_id', 'left');
        $this->db->where('o.user_id', $user_id);
        $user->orders = $this->db->get()->result();

        // $this->db->select('product_id');
        // $this->db->from('products');
        // $this->db->where('user_id', $user_id);
        // $user->products = $this->db->get()->result();

        $user->products = [];

        return $user;
    }

    // Delete user with file cleanup
    public function delete_user_with_files($user_id)
    {
        $user = $this->get_user_with_related_data($user_id);
        if (!$user) {
            return false;
        }

        // Start transaction
        $this->db->trans_start();

        try {
            if ($user->avatar && file_exists(FCPATH . $user->avatar)) {
                unlink(FCPATH . $user->avatar);
            }

            foreach ($user->orders as $order) {
                if ($order->payment_proof && file_exists(FCPATH . $order->payment_proof)) {
                    unlink(FCPATH . $order->payment_proof);
                }
            }

            foreach ($user->products as $product) {
                $this->load->model('Product_image_model');
                $images = $this->Product_image_model->get_images_by_product($product->product_id);
                foreach ($images as $image) {
                    if (file_exists(FCPATH . $image->image_url)) {
                        unlink(FCPATH . $image->image_url);
                    }
                }
                $this->Product_image_model->delete_images_by_product($product->product_id);
            }

            $this->db->where('user_id', $user_id);
            $delete_success = $this->db->delete('users');

            $this->db->trans_complete();

            return $delete_success;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log('Error deleting user with files: ' . $e->getMessage());
            return false;
        }
    }
}
?>