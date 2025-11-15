<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Cart_model');
        $this->load->model('Product_image_model');
    }

    // Home page
    public function index()
    {
        $data['title'] = 'Home - Ecommerce';
        $data['featured_products'] = $this->Product_model->get_all_products(8);
        $data['categories'] = $this->Category_model->get_categories_with_count();

        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }

    // Products page
    public function products()
    {
        $category_id = $this->input->get('category');
        $search = $this->input->get('search');
        $sort = $this->input->get('sort') ?: 'newest';

        $config = array();
        $config['base_url'] = site_url('home/products');
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE;

        if ($category_id) {
            $config['total_rows'] = $this->Product_model->get_total_products_by_category($category_id);
            $data['products'] = $this->Product_model->get_products_by_category($category_id, $config['per_page'], $this->uri->segment(3));
        } elseif ($search) {
            $config['total_rows'] = $this->Product_model->get_total_search_products($search);
            $data['products'] = $this->Product_model->search_products($search, $config['per_page'], $this->uri->segment(3));
        } else {
            $config['total_rows'] = $this->Product_model->get_total_products();
            $data['products'] = $this->Product_model->get_products_sorted($sort, $config['per_page'], $this->uri->segment(3));
        }

        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $data['title'] = 'Products - Ecommerce';
        $data['categories'] = $this->Category_model->get_categories_with_count();
        $data['links'] = $this->pagination->create_links();
        $data['total_products'] = $config['total_rows'];
        $data['current_page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['per_page'] = $config['per_page'];

        $this->load->view('templates/header', $data);
        $this->load->view('home/products', $data);
        $this->load->view('templates/footer');
    }

    // Product detail page
    public function product($product_id)
    {
        $data['product'] = $this->Product_model->get_product_by_id($product_id);

        if (!$data['product']) {
            show_404();
        }

        $data['title'] = $data['product']->product_name . ' - Ecommerce';
        $data['images'] = $this->Product_image_model->get_images_by_product($product_id);
        $data['related_products'] = $this->Product_model->get_products_by_category($data['product']->category_id, 4);

        $this->load->view('templates/header', $data);
        $this->load->view('home/product_detail', $data);
        $this->load->view('templates/footer');
    }
}
?>