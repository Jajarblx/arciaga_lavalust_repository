<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('ProductModel');
    }

    public function index()
    {
        $this->call->view('products', [
            'products' => $this->ProductModel->getAllProducts(),
            'flash' => $this->session->flashdata('products_flash'),
            'username' => $this->session->userdata('auth_username'),
        ]);
    }

    public function create()
    {
        $this->render_form('create', $this->empty_product());
    }

    public function store()
    {
        $product = $this->product_input();
        $errors = $this->validate_product($product);

        if ($errors) {
            $this->render_form('create', $product, $errors);
            return;
        }

        if ($this->ProductModel->createProduct($this->database_values($product)) === false) {
            $this->render_form('create', $product, [
                'general' => 'The product could not be created. Please try again.',
            ]);
            return;
        }

        $this->flash('success', 'Product created successfully.');
        $this->response->redirect_after_post(site_url('products'));
    }

    public function edit($id)
    {
        $id = (int) $id;
        $product = $this->ProductModel->getProductById($id);

        if (!$product) {
            $this->missing_product();
        }

        $this->render_form('edit', $this->normalize_product($product), [], $id);
    }

    public function update($id)
    {
        $id = (int) $id;

        if (!$this->ProductModel->getProductById($id)) {
            $this->missing_product();
        }

        $product = $this->product_input();
        $errors = $this->validate_product($product);

        if ($errors) {
            $this->render_form('edit', $product, $errors, $id);
            return;
        }

        if ($this->ProductModel->updateProduct($id, $this->database_values($product)) === false) {
            $this->render_form('edit', $product, [
                'general' => 'The product could not be updated. Please try again.',
            ], $id);
            return;
        }

        $this->flash('success', 'Product updated successfully.');
        $this->response->redirect_after_post(site_url('products'));
    }

    public function delete($id)
    {
        $id = (int) $id;
        $product = $this->ProductModel->getProductById($id);

        if (!$product) {
            $this->missing_product();
        }

        if (!$this->request->is_post()) {
            $this->call->view('product_delete', [
                'product' => $product,
                'username' => $this->session->userdata('auth_username'),
            ]);
            return;
        }

        if ($this->ProductModel->deleteProduct($id) < 1) {
            $this->flash('error', 'The product could not be deleted.');
        } else {
            $this->flash('success', 'Product deleted successfully.');
        }

        $this->response->redirect_after_post(site_url('products'));
    }

    private function render_form($mode, array $product, array $errors = [], $id = null)
    {
        $this->call->view('product_form', [
            'mode' => $mode,
            'product' => $product,
            'errors' => $errors,
            'id' => $id,
            'username' => $this->session->userdata('auth_username'),
        ]);
    }

    private function product_input()
    {
        return [
            'product_name' => trim((string) $this->request->post('product_name', '')),
            'description' => trim((string) $this->request->post('description', '')),
            'price' => trim((string) $this->request->post('price', '')),
            'quantity' => trim((string) $this->request->post('quantity', '')),
        ];
    }

    private function validate_product(array $product)
    {
        $errors = [];

        if ($product['product_name'] === '') {
            $errors['product_name'] = 'Product name is required.';
        } elseif (strlen($product['product_name']) > 100) {
            $errors['product_name'] = 'Product name must not exceed 100 characters.';
        }

        if ($product['price'] === '') {
            $errors['price'] = 'Price is required.';
        } elseif (!is_numeric($product['price'])) {
            $errors['price'] = 'Price must be numeric.';
        } elseif ((float) $product['price'] < 0) {
            $errors['price'] = 'Price must be at least 0.';
        } elseif ((float) $product['price'] > 99999999.99) {
            $errors['price'] = 'Price exceeds the supported database range.';
        }

        if ($product['quantity'] === '') {
            $errors['quantity'] = 'Quantity is required.';
        } elseif (filter_var($product['quantity'], FILTER_VALIDATE_INT) === false) {
            $errors['quantity'] = 'Quantity must be an integer.';
        } elseif ((int) $product['quantity'] < 0) {
            $errors['quantity'] = 'Quantity must be at least 0.';
        } elseif ((int) $product['quantity'] > 2147483647) {
            $errors['quantity'] = 'Quantity exceeds the supported database range.';
        }

        return $errors;
    }

    private function database_values(array $product)
    {
        return [
            'product_name' => $product['product_name'],
            'description' => $product['description'] === '' ? null : $product['description'],
            'price' => number_format((float) $product['price'], 2, '.', ''),
            'quantity' => (int) $product['quantity'],
        ];
    }

    private function normalize_product(array $product)
    {
        return [
            'product_name' => (string) ($product['product_name'] ?? ''),
            'description' => (string) ($product['description'] ?? ''),
            'price' => (string) ($product['price'] ?? ''),
            'quantity' => (string) ($product['quantity'] ?? ''),
        ];
    }

    private function empty_product()
    {
        return [
            'product_name' => '',
            'description' => '',
            'price' => '',
            'quantity' => '',
        ];
    }

    private function missing_product()
    {
        $this->flash('error', 'The requested product was not found.');
        $this->response->redirect(site_url('products'));
    }

    private function flash($type, $message)
    {
        $this->session->set_flashdata('products_flash', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
