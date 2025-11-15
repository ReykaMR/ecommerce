<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Order_item_model');
        $this->load->model('Payment_model');
        $this->load->library('session');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // User orders history
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['title'] = 'My Orders - Ecommerce';
        $data['orders'] = $this->Order_model->get_orders_by_user($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('orders/index', $data);
        $this->load->view('templates/footer');
    }

    // View order detail
    public function view($order_id)
    {
        $user_id = $this->session->userdata('user_id');

        $order = $this->Order_model->get_order_by_id($order_id);

        if ($order->user_id != $user_id && $this->session->userdata('role') != 'admin') {
            show_404();
        }

        $data['title'] = 'Order #' . $order->order_number . ' - Ecommerce';
        $data['order'] = $order;
        $data['order_items'] = $this->Order_item_model->get_order_items($order_id);
        $data['payment'] = $this->Payment_model->get_payment_by_order($order_id);

        $this->load->view('templates/header', $data);
        $this->load->view('orders/view', $data);
        $this->load->view('templates/footer');
    }

    // Cancel order
    public function cancel($order_id)
    {
        $user_id = $this->session->userdata('user_id');
        $order = $this->Order_model->get_order_by_id($order_id);

        if ($order->user_id != $user_id) {
            show_404();
        }

        if ($order->status != 'pending') {
            $this->session->set_flashdata('error', 'Cannot cancel order that is already processed.');
            redirect('orders/view/' . $order_id);
        }

        if ($this->Order_model->update_order_status($order_id, 'cancelled')) {
            $order_items = $this->Order_item_model->get_order_items($order_id);
            foreach ($order_items as $item) {
                $this->Product_model->update_stock($item->product_id, -$item->quantity);
            }

            $this->session->set_flashdata('success', 'Order cancelled successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel order.');
        }

        redirect('orders/view/' . $order_id);
    }

    // Upload payment proof
    public function upload_payment_proof($order_id)
    {
        $user_id = $this->session->userdata('user_id');
        $order = $this->Order_model->get_order_by_id($order_id);

        if ($order->user_id != $user_id) {
            show_404();
        }

        $payment = $this->Payment_model->get_payment_by_order($order_id);

        if (!empty($_FILES['payment_proof']['name'])) {
            $config['upload_path'] = './uploads/payments/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['max_size'] = 2048;
            $config['file_name'] = 'payment_' . $order_id . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('payment_proof')) {
                $upload_data = $this->upload->data();
                $payment_proof = 'uploads/payments/' . $upload_data['file_name'];

                if ($this->Payment_model->update_payment($payment->payment_id, ['payment_proof' => $payment_proof])) {
                    $this->session->set_flashdata('success', 'Payment proof uploaded successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update payment proof.');
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        } else {
            $this->session->set_flashdata('error', 'Please select a file to upload.');
        }

        redirect('orders/view/' . $order_id);
    }
}
?>