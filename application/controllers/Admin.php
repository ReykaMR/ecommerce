<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Order_model');
        $this->load->model('Payment_model');
        $this->load->library('session');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
    }

    // Admin dashboard
    public function dashboard()
    {
        $data['title'] = 'Admin Dashboard - Ecommerce';
        $data['total_products'] = $this->Product_model->get_total_products();
        $data['total_orders'] = $this->db->count_all('orders');
        $data['total_users'] = $this->db->count_all('users');
        $data['recent_orders'] = $this->Order_model->get_all_orders(5);

        $this->db->where('status', 'pending');
        $data['pending_orders'] = $this->db->count_all_results('orders');

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/templates/footer');
    }

    // Product management
    public function products()
    {
        $data['title'] = 'Manage Products - Ecommerce';
        $data['products'] = $this->Product_model->get_all_products();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/products/index', $data);
        $this->load->view('admin/templates/footer');
    }

    // Add product
    public function add_product()
    {
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer');
        $this->form_validation->set_rules('category_id', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Add Product - Ecommerce';
            $data['categories'] = $this->Category_model->get_all_categories();

            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/products/add', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $product_data = array(
                'product_name' => $this->input->post('product_name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'weight' => $this->input->post('weight'),
                'category_id' => $this->input->post('category_id')
            );

            $product_id = $this->Product_model->create_product($product_data);

            if ($product_id) {
                if (!empty($_FILES['product_images']['name'][0])) {
                    $this->upload_product_images($product_id);
                }

                $this->session->set_flashdata('success', 'Product added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add product.');
            }

            redirect('admin/products');
        }
    }

    // Edit product
    public function edit_product($product_id)
    {
        $product = $this->Product_model->get_product_by_id($product_id);
        if (!$product) {
            show_404();
        }

        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer');
        $this->form_validation->set_rules('category_id', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Product - Ecommerce';
            $data['product'] = $product;
            $data['categories'] = $this->Category_model->get_all_categories();
            $data['images'] = $this->Product_image_model->get_images_by_product($product_id);

            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/products/edit', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $product_data = array(
                'product_name' => $this->input->post('product_name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'weight' => $this->input->post('weight'),
                'category_id' => $this->input->post('category_id'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );

            if ($this->Product_model->update_product($product_id, $product_data)) {
                if (!empty($_FILES['product_images']['name'][0])) {
                    $this->upload_product_images($product_id);
                }

                $this->session->set_flashdata('success', 'Product updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update product.');
            }

            redirect('admin/products');
        }
    }

    // Delete product
    public function delete_product($product_id)
    {
        if ($this->Product_model->delete_product($product_id)) {
            $this->session->set_flashdata('success', 'Product deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete product.');
        }
        redirect('admin/products');
    }

    // Order management
    public function orders()
    {
        $data['title'] = 'Manage Orders - Ecommerce';
        $data['orders'] = $this->Order_model->get_all_orders();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/orders/index', $data);
        $this->load->view('admin/templates/footer');
    }

    // Update order status
    public function update_order_status($order_id)
    {
        $status = $this->input->post('status');

        if ($this->Order_model->update_order_status($order_id, $status)) {
            $this->session->set_flashdata('success', 'Order status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update order status.');
        }
        redirect('admin/orders');
    }

    // Category management
    public function categories()
    {
        $data['title'] = 'Manage Categories - Ecommerce';
        $data['categories'] = $this->Category_model->get_all_categories();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/categories/index', $data);
        $this->load->view('admin/templates/footer');
    }

    // Add category
    public function add_category()
    {
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|is_unique[categories.category_name]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Add Category - Ecommerce';
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/categories/add', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $category_data = array(
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description')
            );

            if ($this->Category_model->create_category($category_data)) {
                $this->session->set_flashdata('success', 'Category added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add category.');
            }
            redirect('admin/categories');
        }
    }

    // Edit category
    public function edit_category($category_id)
    {
        $category = $this->Category_model->get_category_by_id($category_id);
        if (!$category) {
            show_404();
        }

        $this->form_validation->set_rules('category_name', 'Category Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Category - Ecommerce';
            $data['category'] = $category;
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/categories/edit', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $category_data = array(
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description')
            );

            if ($this->Category_model->update_category($category_id, $category_data)) {
                $this->session->set_flashdata('success', 'Category updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update category.');
            }
            redirect('admin/categories');
        }
    }

    // Delete category
    public function delete_category($category_id)
    {
        $this->load->model('Product_model');
        $product_count = $this->Product_model->get_total_products_by_category($category_id);

        if ($product_count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete category that has products. Please delete or move the products first.');
            redirect('admin/categories');
        }

        if ($this->Category_model->delete_category($category_id)) {
            $this->session->set_flashdata('success', 'Category deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete category.');
        }
        redirect('admin/categories');
    }

    // User management
    public function users()
    {
        $data['title'] = 'Manage Users - Ecommerce';
        $data['users'] = $this->User_model->get_all_users();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/users/index', $data);
        $this->load->view('admin/templates/footer');
    }

    // Add user
    public function add_user()
    {
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Add User - Ecommerce';
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/users/add', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'phone' => $this->input->post('phone'),
                'role' => $this->input->post('role')
            );

            if ($this->User_model->create_user($user_data)) {
                $this->session->set_flashdata('success', 'User added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add user.');
            }
            redirect('admin/users');
        }
    }

    // Edit user
    public function edit_user($user_id)
    {
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            show_404();
        }

        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit User - Ecommerce';
            $data['user'] = $user;
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/users/edit', $data);
            $this->load->view('admin/templates/footer');
        } else {
            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'phone' => $this->input->post('phone'),
                'role' => $this->input->post('role')
            );

            if ($this->input->post('password')) {
                $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            if ($this->User_model->email_exists($this->input->post('email'), $user_id)) {
                $this->session->set_flashdata('error', 'Email already exists for another user.');
                redirect('admin/users/edit/' . $user_id);
            }

            if ($this->User_model->update_user($user_id, $user_data)) {
                $this->session->set_flashdata('success', 'User updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
            }
            redirect('admin/users');
        }
    }

    // Delete user with file cleanup
    public function delete_user($user_id)
    {
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admin/users');
        }

        if ($this->User_model->delete_user_with_files($user_id)) {
            $this->session->set_flashdata('success', 'User and all associated files deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user. Please try again.');
        }
        redirect('admin/users');
    }

    // View user details
    public function view_user($user_id)
    {
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            show_404();
        }

        $data['title'] = 'User Details - Ecommerce';
        $data['user'] = $user;

        $this->load->model('Order_model');
        $data['user_orders'] = $this->Order_model->get_orders_by_user($user_id);

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/users/view', $data);
        $this->load->view('admin/templates/footer');
    }

    // View order details
    public function view_order($order_id)
    {
        $order = $this->Order_model->get_order_by_id($order_id);
        if (!$order) {
            show_404();
        }

        $data['title'] = 'Order #' . $order->order_number . ' - Admin';
        $data['order'] = $order;
        $data['order_items'] = $this->Order_item_model->get_order_items($order_id);
        $data['payment'] = $this->Payment_model->get_payment_by_order($order_id);

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/orders/view', $data);
        $this->load->view('admin/templates/footer');
    }

    // Confirm payment
    public function confirm_payment($payment_id)
    {
        $payment = $this->Payment_model->get_payment_by_id($payment_id);
        if (!$payment) {
            show_404();
        }

        if ($this->Payment_model->update_payment_status($payment_id, 'success')) {
            $this->Order_model->update_order_status($payment->order_id, 'paid');
            $this->session->set_flashdata('success', 'Payment confirmed successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to confirm payment.');
        }
        redirect('admin/orders/view/' . $payment->order_id);
    }

    // Reject payment
    public function reject_payment($payment_id)
    {
        $payment = $this->Payment_model->get_payment_by_id($payment_id);
        if (!$payment) {
            show_404();
        }

        if ($this->Payment_model->update_payment_status($payment_id, 'failed')) {
            $this->session->set_flashdata('success', 'Payment rejected successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to reject payment.');
        }
        redirect('admin/orders/view/' . $payment->order_id);
    }

    // Delete product image
    public function delete_image($image_id)
    {
        $image = $this->Product_image_model->get_image_by_id($image_id);
        if (!$image) {
            show_404();
        }

        $product_id = $image->product_id;

        if ($this->Product_image_model->delete_image($image_id)) {
            $remaining_images = $this->Product_image_model->get_images_by_product($product_id);
            if (!empty($remaining_images)) {
                $this->Product_image_model->set_primary_image($product_id, $remaining_images[0]->image_id);
            }

            $this->session->set_flashdata('success', 'Image deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete image.');
        }

        redirect('admin/products/edit/' . $product_id);
    }

    // Set primary image
    public function set_primary_image()
    {
        $image_id = $this->input->post('image_id');
        $product_id = $this->input->post('product_id');

        if ($this->Product_image_model->set_primary_image($product_id, $image_id)) {
            echo json_encode(['success' => true, 'message' => 'Primary image updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update primary image.']);
        }
    }

    // File cleanup utility
    public function file_cleanup()
    {
        $this->load->library('File_cleanup');

        $cleaned_files = $this->file_cleanup->cleanup_orphaned_files();

        $data['title'] = 'File Cleanup - Ecommerce';
        $data['cleaned_files'] = $cleaned_files;
        $data['total_cleaned'] = count($cleaned_files['avatars']) + count($cleaned_files['product_images']) + count($cleaned_files['payment_proofs']);

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/file_cleanup', $data);
        $this->load->view('admin/templates/footer');
    }

    // Run file cleanup (AJAX)
    public function run_file_cleanup()
    {
        $this->load->library('File_cleanup');

        $cleaned_files = $this->file_cleanup->cleanup_orphaned_files();

        $total_cleaned = count($cleaned_files['avatars']) + count($cleaned_files['product_images']) + count($cleaned_files['payment_proofs']);

        echo json_encode([
            'success' => true,
            'message' => 'File cleanup completed. ' . $total_cleaned . ' orphaned files deleted.',
            'cleaned_files' => $cleaned_files,
            'total_cleaned' => $total_cleaned
        ]);
    }

    // Helper function for uploading product images
    private function upload_product_images($product_id)
    {
        if (!empty($_FILES['product_images']['name'][0])) {
            $files = $_FILES['product_images'];
            $upload_count = count($files['name']);
            $uploaded_images = [];

            for ($i = 0; $i < $upload_count; $i++) {
                if ($files['name'][$i] != '') {
                    $_FILES['product_image']['name'] = $files['name'][$i];
                    $_FILES['product_image']['type'] = $files['type'][$i];
                    $_FILES['product_image']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['product_image']['error'] = $files['error'][$i];
                    $_FILES['product_image']['size'] = $files['size'][$i];

                    $config['upload_path'] = FCPATH . 'uploads/products/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = 'product_' . $product_id . '_' . time() . '_' . $i;
                    $config['overwrite'] = false;

                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0755, true);
                    }

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('product_image')) {
                        $upload_data = $this->upload->data();
                        $image_data = array(
                            'product_id' => $product_id,
                            'image_url' => 'uploads/products/' . $upload_data['file_name'],
                            'is_primary' => ($i == 0 && !$this->Product_image_model->has_images($product_id)) ? 1 : 0
                        );
                        $image_id = $this->Product_image_model->add_image($image_data);
                        $uploaded_images[] = $image_id;
                    } else {
                        error_log('Image upload error: ' . $this->upload->display_errors());
                    }
                }
            }
            return $uploaded_images;
        }
        return [];
    }
}
?>