<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get payment by order ID
    public function get_payment_by_order($order_id)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->get('payments')->row();
    }

    // Get payment by ID
    public function get_payment_by_id($payment_id)
    {
        return $this->db->get_where('payments', array('payment_id' => $payment_id))->row();
    }

    // Create payment
    public function create_payment($data)
    {
        $this->db->insert('payments', $data);
        return $this->db->insert_id();
    }

    // Update payment
    public function update_payment($payment_id, $data)
    {
        $this->db->where('payment_id', $payment_id);
        return $this->db->update('payments', $data);
    }

    // Update payment status
    public function update_payment_status($payment_id, $status)
    {
        $data = array('status' => $status);
        if ($status == 'success') {
            $data['paid_at'] = date('Y-m-d H:i:s');
        }
        $this->db->where('payment_id', $payment_id);
        return $this->db->update('payments', $data);
    }

    // Get payments by status
    public function get_payments_by_status($status)
    {
        $this->db->select('p.*, o.order_number, u.full_name');
        $this->db->from('payments p');
        $this->db->join('orders o', 'p.order_id = o.order_id');
        $this->db->join('users u', 'o.user_id = u.user_id');
        $this->db->where('p.status', $status);
        return $this->db->get()->result();
    }
}
?>