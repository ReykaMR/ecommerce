<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all categories
    public function get_all_categories()
    {
        return $this->db->get('categories')->result();
    }

    // Get category by ID
    public function get_category_by_id($category_id)
    {
        return $this->db->get_where('categories', array('category_id' => $category_id))->row();
    }

    // Create new category
    public function create_category($data)
    {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    // Update category
    public function update_category($category_id, $data)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->update('categories', $data);
    }

    // Delete category
    public function delete_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->delete('categories');
    }

    // Get categories with product count
    public function get_categories_with_count()
    {
        $this->db->select('c.*, COUNT(p.product_id) as product_count');
        $this->db->from('categories c');
        $this->db->join('products p', 'c.category_id = p.category_id AND p.is_active = 1', 'left');
        $this->db->group_by('c.category_id');
        return $this->db->get()->result();
    }

    // Method untuk mendapatkan jumlah produk per kategori
    public function get_product_count_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('products');
    }
}
?>