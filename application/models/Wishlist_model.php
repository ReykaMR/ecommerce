<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wishlist_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Add to wishlist
    public function add_to_wishlist($data)
    {
        $existing = $this->db->get_where('wishlists', array(
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id']
        ))->row();

        if (!$existing) {
            return $this->db->insert('wishlists', $data);
        }
        return false;
    }

    // Remove from wishlist
    public function remove_from_wishlist($wishlist_id)
    {
        $this->db->where('wishlist_id', $wishlist_id);
        return $this->db->delete('wishlists');
    }

    // Remove from wishlist by user and product
    public function remove_by_user_and_product($user_id, $product_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('product_id', $product_id);
        return $this->db->delete('wishlists');
    }

    // Get wishlist items by user ID
    public function get_wishlist_items($user_id)
    {
        $this->db->select('w.*, p.product_name, p.price, p.stock, p.description, pi.image_url');
        $this->db->from('wishlists w');
        $this->db->join('products p', 'w.product_id = p.product_id');
        $this->db->join('product_images pi', 'p.product_id = pi.product_id AND pi.is_primary = 1', 'left');
        $this->db->where('w.user_id', $user_id);
        $this->db->where('p.is_active', 1);
        $this->db->order_by('w.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Check if product is in wishlist
    public function is_in_wishlist($user_id, $product_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('product_id', $product_id);
        return $this->db->get('wishlists')->num_rows() > 0;
    }

    // Get wishlist count by user
    public function get_wishlist_count($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('wishlists');
    }

    // Get wishlist item by ID
    public function get_wishlist_item_by_id($wishlist_id)
    {
        return $this->db->get_where('wishlists', array('wishlist_id' => $wishlist_id))->row();
    }
}
?>