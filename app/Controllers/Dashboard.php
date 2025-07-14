<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\CategoryModel;
use App\Models\SaleModel;

class Dashboard extends BaseController
{
    public function __construct()
    {
        // Constructor logic if needed
    }

    public function index()
    {
        // Check authentication
        $authCheck = $this->requireAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Load models
        $productModel = new ProductModel();
        $customerModel = new CustomerModel();
        $categoryModel = new CategoryModel();
        $saleModel = new SaleModel();

        // Get data for POS interface
        $data = [
            'products' => $productModel->getProductsWithCategory(),
            'customers' => $customerModel->findAll(),
            'categories' => $categoryModel->findAll(),
            'daily_sales' => $saleModel->getDailySalesTotal(),
            'low_stock_products' => $productModel->getLowStockProducts(),
            'page_title' => 'POS Dashboard'
        ];

        return $this->render('pos/dashboard', $data);
    }

    public function changeLanguage($type)
    {
        $this->session->set('lang', $type);
        
        // Update user settings if needed
        if ($this->user) {
            $userModel = new \App\Models\UserModel();
            $userModel->update($this->user['id'], ['language' => $type]);
        }
        
        return redirect()->to('/');
    }

    public function getProductsByCategory()
    {
        $categoryId = $this->request->getPost('category_id');
        
        if (!$categoryId) {
            return $this->jsonResponse(['error' => 'Category ID required'], 400);
        }

        $productModel = new ProductModel();
        $products = $productModel->getByCategory($categoryId);

        return $this->jsonResponse(['products' => $products]);
    }

    public function searchProducts()
    {
        $term = $this->request->getPost('term');
        
        if (!$term) {
            return $this->jsonResponse(['error' => 'Search term required'], 400);
        }

        $productModel = new ProductModel();
        $products = $productModel->like('name', $term)
                                ->orLike('code', $term)
                                ->findAll();

        return $this->jsonResponse(['products' => $products]);
    }

    public function getProductByCode()
    {
        $code = $this->request->getPost('code');
        
        if (!$code) {
            return $this->jsonResponse(['error' => 'Product code required'], 400);
        }

        $productModel = new ProductModel();
        $product = $productModel->getByCode($code);

        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        return $this->jsonResponse(['product' => $product]);
    }
}