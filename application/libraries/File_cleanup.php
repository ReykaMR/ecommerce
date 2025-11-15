<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_cleanup
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('User_model');
        $this->CI->load->model('Product_image_model');
        $this->CI->load->model('Payment_model');
    }

    // Clean up orphaned files
    public function cleanup_orphaned_files()
    {
        $cleaned_files = [];

        // Clean avatars
        $cleaned_files['avatars'] = $this->cleanup_orphaned_avatars();

        // Clean product images
        $cleaned_files['product_images'] = $this->cleanup_orphaned_product_images();

        // Clean payment proofs
        $cleaned_files['payment_proofs'] = $this->cleanup_orphaned_payment_proofs();

        return $cleaned_files;
    }

    // Clean orphaned avatars
    private function cleanup_orphaned_avatars()
    {
        $cleaned = [];
        $avatars_dir = FCPATH . 'uploads/avatars/';

        if (!is_dir($avatars_dir)) {
            return $cleaned;
        }

        // Get all avatar files
        $files = glob($avatars_dir . '*');

        // Get all avatars from database
        $this->CI->db->select('avatar');
        $this->CI->db->from('users');
        $this->CI->db->where('avatar IS NOT NULL');
        $this->CI->db->where('avatar !=', '');
        $db_avatars = $this->CI->db->get()->result();

        $db_avatar_paths = [];
        foreach ($db_avatars as $avatar) {
            $db_avatar_paths[] = FCPATH . $avatar->avatar;
        }

        // Delete files not in database
        foreach ($files as $file) {
            if (!in_array($file, $db_avatar_paths) && is_file($file)) {
                unlink($file);
                $cleaned[] = basename($file);
            }
        }

        return $cleaned;
    }

    // Clean orphaned product images
    private function cleanup_orphaned_product_images()
    {
        $cleaned = [];
        $products_dir = FCPATH . 'uploads/products/';

        if (!is_dir($products_dir)) {
            return $cleaned;
        }

        // Get all product image files
        $files = glob($products_dir . '*');

        // Get all product images from database
        $this->CI->db->select('image_url');
        $this->CI->db->from('product_images');
        $db_images = $this->CI->db->get()->result();

        $db_image_paths = [];
        foreach ($db_images as $image) {
            $db_image_paths[] = FCPATH . $image->image_url;
        }

        // Delete files not in database
        foreach ($files as $file) {
            if (!in_array($file, $db_image_paths) && is_file($file)) {
                unlink($file);
                $cleaned[] = basename($file);
            }
        }

        return $cleaned;
    }

    // Clean orphaned payment proofs
    private function cleanup_orphaned_payment_proofs()
    {
        $cleaned = [];
        $payments_dir = FCPATH . 'uploads/payments/';

        if (!is_dir($payments_dir)) {
            return $cleaned;
        }

        // Get all payment proof files
        $files = glob($payments_dir . '*');

        // Get all payment proofs from database
        $this->CI->db->select('payment_proof');
        $this->CI->db->from('payments');
        $this->CI->db->where('payment_proof IS NOT NULL');
        $this->CI->db->where('payment_proof !=', '');
        $db_proofs = $this->CI->db->get()->result();

        $db_proof_paths = [];
        foreach ($db_proofs as $proof) {
            $db_proof_paths[] = FCPATH . $proof->payment_proof;
        }

        // Delete files not in database
        foreach ($files as $file) {
            if (!in_array($file, $db_proof_paths) && is_file($file)) {
                unlink($file);
                $cleaned[] = basename($file);
            }
        }

        return $cleaned;
    }
}
?>