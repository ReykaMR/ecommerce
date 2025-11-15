<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wishlist extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Wishlist_model');
        $this->load->model('Product_model');
        $this->load->library('session');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // View wishlist
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['title'] = 'My Wishlist - Ecommerce';
        $data['wishlist_items'] = $this->Wishlist_model->get_wishlist_items($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('wishlist/index', $data);
        $this->load->view('templates/footer');
    }

    // Add to wishlist (AJAX)
    public function add()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Please login first.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $product_id = $this->input->post('product_id');

        $product = $this->Product_model->get_product_by_id($product_id);
        if (!$product || !$product->is_active) {
            echo json_encode(['success' => false, 'message' => 'Product not available.']);
            return;
        }

        $wishlist_data = array(
            'user_id' => $user_id,
            'product_id' => $product_id
        );

        if ($this->Wishlist_model->add_to_wishlist($wishlist_data)) {
            $wishlist_count = $this->Wishlist_model->get_wishlist_count($user_id);
            echo json_encode([
                'success' => true,
                'message' => 'Product added to wishlist!',
                'wishlist_count' => $wishlist_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product already in wishlist.']);
        }
    }

    // Remove from wishlist (AJAX)
    public function remove()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Please login first.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $product_id = $this->input->post('product_id');

        if ($this->Wishlist_model->remove_by_user_and_product($user_id, $product_id)) {
            $wishlist_count = $this->Wishlist_model->get_wishlist_count($user_id);
            echo json_encode([
                'success' => true,
                'message' => 'Product removed from wishlist!',
                'wishlist_count' => $wishlist_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove product from wishlist.']);
        }
    }

    // Remove from wishlist by ID
    public function remove_item($wishlist_id)
    {
        $user_id = $this->session->userdata('user_id');

        $wishlist_item = $this->Wishlist_model->get_wishlist_item_by_id($wishlist_id);
        if (!$wishlist_item || $wishlist_item->user_id != $user_id) {
            show_404();
        }

        if ($this->Wishlist_model->remove_from_wishlist($wishlist_id)) {
            $this->session->set_flashdata('success', 'Product removed from wishlist.');
        } else {
            $this->session->set_flashdata('error', 'Failed to remove product from wishlist.');
        }
        redirect('wishlist');
    }

    // Get wishlist count (AJAX)
    public function get_count()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['count' => 0]);
            return;
        }

        $user_id = $this->session->userdata('user_id');

        if (!isset($this->Wishlist_model)) {
            $this->load->model('Wishlist_model');
        }

        try {
            $count = $this->Wishlist_model->get_wishlist_count($user_id);
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            error_log('Wishlist count error: ' . $e->getMessage());
            echo json_encode(['count' => 0]);
        }
    }

    // Check wishlist status for multiple products
    public function check_status()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode([
                'success' => true,
                'in_wishlist' => []
            ]);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $product_ids = $this->input->post('product_ids');

        if (!isset($this->Wishlist_model)) {
            $this->load->model('Wishlist_model');
        }

        $in_wishlist = [];
        if (is_array($product_ids)) {
            foreach ($product_ids as $product_id) {
                if ($this->Wishlist_model->is_in_wishlist($user_id, $product_id)) {
                    $in_wishlist[] = $product_id;
                }
            }
        }

        echo json_encode([
            'success' => true,
            'in_wishlist' => $in_wishlist
        ]);
    }
}
?>