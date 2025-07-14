<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $this->requireAuth();

        $supplier = $this->request->getPost('filtersupp') ?: 'all';
        $type = $this->request->getPost('filtertype') ?: 'all';

        $builder = $this->productModel->builder();
        
        if ($supplier !== 'all') {
            $builder->where('supplier_id', $supplier);
        }
        
        if ($type !== 'all') {
            $builder->where('type', $type);
        }

        $data = [
            'products' => $builder->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'supplierF' => $supplier,
            'typeF' => $type,
            'title' => 'Products Management'
        ];

        return $this->render('products/index', $data);
    }

    public function add()
    {
        $this->requireAuth();

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'category_id' => $this->request->getPost('category_id'),
                'cost' => $this->request->getPost('cost'),
                'price' => $this->request->getPost('price'),
                'tax' => $this->request->getPost('tax') ?: 0,
                'stock_quantity' => $this->request->getPost('stock_quantity') ?: 0,
                'min_stock' => $this->request->getPost('min_stock') ?: 0,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->productModel->insert($data)) {
                session()->setFlashdata('success', 'Product added successfully');
            } else {
                session()->setFlashdata('error', 'Failed to add product');
            }

            return redirect()->to('/products');
        }

        $data = [
            'categories' => $this->categoryModel->findAll(),
            'title' => 'Add Product'
        ];

        return $this->render('products/add', $data);
    }

    public function edit($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/products');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            session()->setFlashdata('error', 'Product not found');
            return redirect()->to('/products');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'category_id' => $this->request->getPost('category_id'),
                'cost' => $this->request->getPost('cost'),
                'price' => $this->request->getPost('price'),
                'tax' => $this->request->getPost('tax'),
                'stock_quantity' => $this->request->getPost('stock_quantity'),
                'min_stock' => $this->request->getPost('min_stock'),
                'status' => $this->request->getPost('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->productModel->update($id, $data)) {
                session()->setFlashdata('success', 'Product updated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to update product');
            }

            return redirect()->to('/products');
        }

        $data = [
            'product' => $product,
            'categories' => $this->categoryModel->findAll(),
            'title' => 'Edit Product'
        ];

        return $this->render('products/edit', $data);
    }

    public function delete($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/products');
        }

        if ($this->productModel->delete($id)) {
            session()->setFlashdata('success', 'Product deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete product');
        }

        return redirect()->to('/products');
    }

    public function csv()
    {
        $this->requireAuth();

        $products = $this->productModel->select('code, name, category_id, cost, tax, description, price, stock_quantity')->findAll();
        
        $filename = 'products_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, ['Code', 'Name', 'Category', 'Cost', 'Tax', 'Description', 'Price', 'Stock']);
        
        // CSV data
        foreach ($products as $product) {
            $category = $this->categoryModel->find($product['category_id']);
            $row = [
                $product['code'],
                $product['name'],
                $category ? $category['name'] : '',
                $product['cost'],
                $product['tax'],
                $product['description'],
                $product['price'],
                $product['stock_quantity']
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }

    public function findByCode($code)
    {
        $this->requireAuth();
        
        $product = $this->productModel->where('code', $code)->first();
        
        if ($product) {
            return $this->response->setJSON($product);
        } else {
            return $this->response->setJSON(['error' => 'Product not found'], 404);
        }
    }
}