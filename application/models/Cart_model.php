<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get cart items by user ID
    public function get_cart_items($user_id)
    {
        $this->db->select('c.*, p.product_name, p.price, p.stock, pi.image_url');
        $this->db->from('cart c');
        $this->db->join('products p', 'c.product_id = p.product_id');
        $this->db->join('product_images pi', 'p.product_id = pi.product_id AND pi.is_primary = 1', 'left');
        $this->db->where('c.user_id', $user_id);
        $this->db->where('p.is_active', 1);
        return $this->db->get()->result();
    }

    // Get cart item by ID
    public function get_cart_item_by_id($cart_id)
    {
        return $this->db->get_where('cart', array('cart_id' => $cart_id))->row();
    }

    // Add to cart
    public function add_to_cart($data)
    {
        $existing = $this->db->get_where('cart', array(
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id']
        ))->row();

        if ($existing) {
            $this->db->where('cart_id', $existing->cart_id);
            $this->db->set('quantity', 'quantity + ' . $data['quantity'], FALSE);
            return $this->db->update('cart');
        } else {
            return $this->db->insert('cart', $data);
        }
    }

    // Update cart item quantity
    public function update_cart_quantity($cart_id, $quantity)
    {
        $this->db->where('cart_id', $cart_id);
        return $this->db->update('cart', array('quantity' => $quantity));
    }

    // Remove from cart
    public function remove_from_cart($cart_id)
    {
        $this->db->where('cart_id', $cart_id);
        return $this->db->delete('cart');
    }

    // Clear user cart
    public function clear_cart($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('cart');
    }

    // Get cart item count
    public function get_cart_count($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('cart');
    }

    // Calculate cart total
    public function calculate_cart_total($user_id)
    {
        $this->db->select('SUM(c.quantity * p.price) as total');
        $this->db->from('cart c');
        $this->db->join('products p', 'c.product_id = p.product_id');
        $this->db->where('c.user_id', $user_id);
        $result = $this->db->get()->row();
        return $result->total ? $result->total : 0;
    }

    // Check if product exists in cart
    public function is_product_in_cart($user_id, $product_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('product_id', $product_id);
        return $this->db->get('cart')->num_rows() > 0;
    }
}
?>