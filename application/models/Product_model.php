<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all products with category name
    public function get_all_products($limit = null, $offset = null)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.is_active', 1);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get product by ID with category name
    public function get_product_by_id($product_id)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.product_id', $product_id);
        return $this->db->get()->row();
    }

    // Get products by category
    public function get_products_by_category($category_id, $limit = null, $offset = null)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.category_id', $category_id);
        $this->db->where('p.is_active', 1);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Create new product
    public function create_product($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    // Update product
    public function update_product($product_id, $data)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->update('products', $data);
    }

    // Delete product (soft delete)
    public function delete_product($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->update('products', array('is_active' => 0));
    }

    // Update stock
    public function update_stock($product_id, $quantity)
    {
        $this->db->set('stock', 'stock - ' . $quantity, FALSE);
        $this->db->where('product_id', $product_id);
        return $this->db->update('products');
    }

    // Search products
    public function search_products($keyword, $limit = null, $offset = null)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.is_active', 1);
        $this->db->group_start();
        $this->db->like('p.product_name', $keyword);
        $this->db->or_like('p.description', $keyword);
        $this->db->or_like('c.category_name', $keyword);
        $this->db->group_end();

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    // Get total products count
    public function get_total_products()
    {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('products');
    }

    // Get total products for search
    public function get_total_search_products($keyword)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.is_active', 1);
        $this->db->group_start();
        $this->db->like('p.product_name', $keyword);
        $this->db->or_like('p.description', $keyword);
        $this->db->or_like('c.category_name', $keyword);
        $this->db->group_end();

        return $this->db->count_all_results();
    }

    // Get total products by category
    public function get_total_products_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('products');
    }

    // Get products with sorting
    public function get_products_sorted($sort = 'newest', $limit = null, $offset = null)
    {
        $this->db->select('p.*, c.category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.category_id');
        $this->db->where('p.is_active', 1);

        switch ($sort) {
            case 'name_asc':
                $this->db->order_by('p.product_name', 'ASC');
                break;
            case 'name_desc':
                $this->db->order_by('p.product_name', 'DESC');
                break;
            case 'price_asc':
                $this->db->order_by('p.price', 'ASC');
                break;
            case 'price_desc':
                $this->db->order_by('p.price', 'DESC');
                break;
            case 'newest':
            default:
                $this->db->order_by('p.created_at', 'DESC');
                break;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }
}
?>