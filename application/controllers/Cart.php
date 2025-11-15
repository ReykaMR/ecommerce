<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cart_model');
        $this->load->model('Product_model');
        $this->load->library('session');

        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access cart.');
            redirect('auth/login');
        }
    }

    // View cart
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['title'] = 'Shopping Cart - Ecommerce';
        $data['cart_items'] = $this->Cart_model->get_cart_items($user_id);
        $data['cart_total'] = $this->Cart_model->calculate_cart_total($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('cart/index', $data);
        $this->load->view('templates/footer');
    }

    // Add to cart
    public function add()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Please login first.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity') ?: 1;

        if (empty($product_id)) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required.']);
            return;
        }

        $product = $this->Product_model->get_product_by_id($product_id);
        if (!$product || !$product->is_active) {
            echo json_encode(['success' => false, 'message' => 'Product not available.']);
            return;
        }

        if ($product->stock < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock. Only ' . $product->stock . ' available.']);
            return;
        }

        $cart_data = array(
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        );

        if ($this->Cart_model->add_to_cart($cart_data)) {
            $cart_count = $this->Cart_model->get_cart_count($user_id);
            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cart_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart.']);
        }
    }

    // Update cart item quantity
    public function update_quantity()
    {
        $cart_id = $this->input->post('cart_id');
        $quantity = $this->input->post('quantity');

        if ($quantity <= 0) {
            $this->remove_item($cart_id);
            return;
        }

        $cart_item = $this->Cart_model->get_cart_item_by_id($cart_id);
        $product = $this->Product_model->get_product_by_id($cart_item->product_id);

        if ($product->stock < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock.']);
            return;
        }

        if ($this->Cart_model->update_cart_quantity($cart_id, $quantity)) {
            $user_id = $this->session->userdata('user_id');
            $cart_total = $this->Cart_model->calculate_cart_total($user_id);
            echo json_encode([
                'success' => true,
                'message' => 'Cart updated!',
                'cart_total' => number_format($cart_total, 2)
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart.']);
        }
    }

    // Remove item from cart
    public function remove_item($cart_id)
    {
        if ($this->Cart_model->remove_from_cart($cart_id)) {
            $this->session->set_flashdata('success', 'Item removed from cart.');
        } else {
            $this->session->set_flashdata('error', 'Failed to remove item from cart.');
        }
        redirect('cart');
    }

    // Clear cart
    public function clear()
    {
        $user_id = $this->session->userdata('user_id');

        if ($this->Cart_model->clear_cart($user_id)) {
            $this->session->set_flashdata('success', 'Cart cleared successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to clear cart.');
        }
        redirect('cart');
    }

    // Get cart count (AJAX)
    public function get_cart_count()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['count' => 0]);
            return;
        }

        $user_id = $this->session->userdata('user_id');

        if (!isset($this->Cart_model)) {
            $this->load->model('Cart_model');
        }

        try {
            $count = $this->Cart_model->get_cart_count($user_id);
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            error_log('Cart count error: ' . $e->getMessage());
            echo json_encode(['count' => 0]);
        }
    }
}
?>