<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkout extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cart_model');
        $this->load->model('Order_model');
        $this->load->model('Order_item_model');
        $this->load->model('Product_model');
        $this->load->model('Payment_model');
        $this->load->library('session');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Checkout page
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $cart_items = $this->Cart_model->get_cart_items($user_id);
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('cart');
        }

        foreach ($cart_items as $item) {
            if ($item->stock < $item->quantity) {
                $this->session->set_flashdata('error', "Insufficient stock for {$item->product_name}. Only {$item->stock} available.");
                redirect('cart');
            }
        }

        $data['title'] = 'Checkout - Ecommerce';
        $data['cart_items'] = $cart_items;
        $data['cart_total'] = $this->Cart_model->calculate_cart_total($user_id);
        $data['user'] = $this->User_model->get_user_by_id($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('checkout/index', $data);
        $this->load->view('templates/footer');
    }

    // Process checkout
    public function process()
    {
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('checkout');
        }

        $cart_items = $this->Cart_model->get_cart_items($user_id);
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('cart');
        }

        $subtotal = $this->Cart_model->calculate_cart_total($user_id);
        $shipping_cost = 10000; // Fixed shipping cost for demo
        $total_amount = $subtotal + $shipping_cost;

        $order_data = array(
            'user_id' => $user_id,
            'order_number' => $this->Order_model->generate_order_number(),
            'total_amount' => $total_amount,
            'shipping_address' => $this->input->post('shipping_address'),
            'shipping_cost' => $shipping_cost,
            'notes' => $this->input->post('notes'),
            'status' => 'pending'
        );

        $order_id = $this->Order_model->create_order($order_data);

        if ($order_id) {
            $order_items = array();
            foreach ($cart_items as $item) {
                $order_items[] = array(
                    'order_id' => $order_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'subtotal' => $item->price * $item->quantity
                );

                $this->Product_model->update_stock($item->product_id, $item->quantity);
            }

            $this->Order_item_model->add_order_items_batch($order_items);

            $payment_data = array(
                'order_id' => $order_id,
                'payment_method' => $this->input->post('payment_method'),
                'amount' => $total_amount,
                'status' => 'pending'
            );

            $this->Payment_model->create_payment($payment_data);

            $this->Cart_model->clear_cart($user_id);

            $this->session->set_flashdata('success', 'Order placed successfully!');
            redirect('orders/view/' . $order_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to place order. Please try again.');
            redirect('checkout');
        }
    }
}
?>