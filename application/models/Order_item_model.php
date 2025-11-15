<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_item_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get order items by order ID
    public function get_order_items($order_id)
    {
        $this->db->select('oi.*, p.product_name, p.weight');
        $this->db->from('order_items oi');
        $this->db->join('products p', 'oi.product_id = p.product_id');
        $this->db->where('oi.order_id', $order_id);
        return $this->db->get()->result();
    }

    // Add order item
    public function add_order_item($data)
    {
        $this->db->insert('order_items', $data);
        return $this->db->insert_id();
    }

    // Add multiple order items
    public function add_order_items_batch($data)
    {
        return $this->db->insert_batch('order_items', $data);
    }

    // Update order item
    public function update_order_item($order_item_id, $data)
    {
        $this->db->where('order_item_id', $order_item_id);
        return $this->db->update('order_items', $data);
    }

    // Delete order item
    public function delete_order_item($order_item_id)
    {
        $this->db->where('order_item_id', $order_item_id);
        return $this->db->delete('order_items');
    }

    // Delete all items for order
    public function delete_items_by_order($order_id)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->delete('order_items');
    }

    // Calculate order total
    public function calculate_order_total($order_id)
    {
        $this->db->select_sum('subtotal');
        $this->db->where('order_id', $order_id);
        $result = $this->db->get('order_items')->row();
        return $result->subtotal ? $result->subtotal : 0;
    }
}
?>