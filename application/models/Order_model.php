<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all orders with user info
    public function get_all_orders($limit = null, $offset = null)
    {
        $this->db->select('o.*, u.full_name, u.email');
        $this->db->from('orders o');
        $this->db->join('users u', 'o.user_id = u.user_id');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('o.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get order by ID with user info
    public function get_order_by_id($order_id)
    {
        $this->db->select('o.*, u.full_name, u.email, u.phone');
        $this->db->from('orders o');
        $this->db->join('users u', 'o.user_id = u.user_id');
        $this->db->where('o.order_id', $order_id);
        return $this->db->get()->row();
    }

    // Get orders by user ID
    public function get_orders_by_user($user_id, $limit = null, $offset = null)
    {
        $this->db->where('user_id', $user_id);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('orders')->result();
    }

    // Create new order
    public function create_order($data)
    {
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }

    // Update order
    public function update_order($order_id, $data)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->update('orders', $data);
    }

    // Update order status
    public function update_order_status($order_id, $status)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->update('orders', array('status' => $status));
    }

    // Generate unique order number
    public function generate_order_number()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = mt_rand(1000, 9999);
        return $prefix . $date . $random;
    }

    // Get orders by status
    public function get_orders_by_status($status, $limit = null, $offset = null)
    {
        $this->db->select('o.*, u.full_name');
        $this->db->from('orders o');
        $this->db->join('users u', 'o.user_id = u.user_id');
        $this->db->where('o.status', $status);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('o.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
?>