<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_image_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get images by product ID
    public function get_images_by_product($product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->order_by('is_primary', 'DESC');
        return $this->db->get('product_images')->result();
    }

    // Get primary image by product ID
    public function get_primary_image($product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('is_primary', 1);
        return $this->db->get('product_images')->row();
    }

    // Get first image by product ID (fallback if no primary)
    public function get_first_image($product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->order_by('is_primary', 'DESC');
        $this->db->limit(1);
        return $this->db->get('product_images')->row();
    }

    // Add product image
    public function add_image($data)
    {
        $this->db->insert('product_images', $data);
        return $this->db->insert_id();
    }

    // Delete product image
    public function delete_image($image_id)
    {
        $image = $this->get_image_by_id($image_id);
        if (!$image) {
            return false;
        }

        $this->db->where('image_id', $image_id);
        $result = $this->db->delete('product_images');

        if ($result) {
            $this->delete_physical_image($image->image_url);
            return true;
        }
        return false;
    }

    // Delete physical image file
    private function delete_physical_image($image_path)
    {
        $full_path = FCPATH . $image_path;
        if (file_exists($full_path) && is_file($full_path)) {
            unlink($full_path);
            return true;
        }
        return false;
    }

    // Set primary image
    public function set_primary_image($product_id, $image_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('product_images', array('is_primary' => 0));

        $this->db->where('image_id', $image_id);
        return $this->db->update('product_images', array('is_primary' => 1));
    }

    // Delete all images for product
    public function delete_images_by_product($product_id)
    {
        $images = $this->get_images_by_product($product_id);

        $this->db->where('product_id', $product_id);
        $result = $this->db->delete('product_images');

        if ($result) {
            foreach ($images as $image) {
                $this->delete_physical_image($image->image_url);
            }
            return true;
        }
        return false;
    }

    // Get image by ID
    public function get_image_by_id($image_id)
    {
        return $this->db->get_where('product_images', array('image_id' => $image_id))->row();
    }

    // Check if product has images
    public function has_images($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->count_all_results('product_images') > 0;
    }

    // Get image count for product
    public function get_image_count($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->count_all_results('product_images');
    }
}
?>