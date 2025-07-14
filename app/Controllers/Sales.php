<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;

class Sales extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $customerModel;
    protected $productModel;

    public function __construct()
    {
        $this->saleModel = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->requireAuth();

        $builder = $this->saleModel->builder();
        $builder->select('sales.*, customers.name as customer_name, users.username as user_name');
        $builder->join('customers', 'customers.id = sales.customer_id', 'left');
        $builder->join('users', 'users.id = sales.user_id', 'left');
        $builder->orderBy('sales.created_at', 'DESC');

        $data = [
            'sales' => $builder->get()->getResultArray(),
            'title' => 'Sales Management'
        ];

        return $this->render('sales/index', $data);
    }

    public function view($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/sales');
        }

        $sale = $this->saleModel->find($id);
        if (!$sale) {
            session()->setFlashdata('error', 'Sale not found');
            return redirect()->to('/sales');
        }

        // Get sale items with product details
        $builder = $this->saleItemModel->builder();
        $builder->select('sale_items.*, products.name as product_name, products.code as product_code');
        $builder->join('products', 'products.id = sale_items.product_id');
        $builder->where('sale_items.sale_id', $id);
        $saleItems = $builder->get()->getResultArray();

        // Get customer details
        $customer = $this->customerModel->find($sale['customer_id']);

        // Get user details
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($sale['user_id']);

        $data = [
            'sale' => $sale,
            'sale_items' => $saleItems,
            'customer' => $customer,
            'user' => $user,
            'title' => 'Sale Details'
        ];

        return $this->render('sales/view', $data);
    }

    public function delete($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/sales');
        }

        $sale = $this->saleModel->find($id);
        if (!$sale) {
            session()->setFlashdata('error', 'Sale not found');
            return redirect()->to('/sales');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete sale items first
            $this->saleItemModel->where('sale_id', $id)->delete();
            
            // Delete the sale
            $this->saleModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                session()->setFlashdata('error', 'Failed to delete sale');
            } else {
                session()->setFlashdata('success', 'Sale deleted successfully');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to delete sale: ' . $e->getMessage());
        }

        return redirect()->to('/sales');
    }

    public function receipt($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/sales');
        }

        $sale = $this->saleModel->find($id);
        if (!$sale) {
            session()->setFlashdata('error', 'Sale not found');
            return redirect()->to('/sales');
        }

        // Get sale items with product details
        $builder = $this->saleItemModel->builder();
        $builder->select('sale_items.*, products.name as product_name, products.code as product_code');
        $builder->join('products', 'products.id = sale_items.product_id');
        $builder->where('sale_items.sale_id', $id);
        $saleItems = $builder->get()->getResultArray();

        // Get customer details
        $customer = $this->customerModel->find($sale['customer_id']);

        $data = [
            'sale' => $sale,
            'sale_items' => $saleItems,
            'customer' => $customer,
            'title' => 'Receipt'
        ];

        return $this->render('sales/receipt', $data);
    }

    public function dailySales()
    {
        $this->requireAuth();

        $date = $this->request->getGet('date') ?: date('Y-m-d');
        
        $builder = $this->saleModel->builder();
        $builder->select('sales.*, customers.name as customer_name, users.username as user_name');
        $builder->join('customers', 'customers.id = sales.customer_id', 'left');
        $builder->join('users', 'users.id = sales.user_id', 'left');
        $builder->where('DATE(sales.created_at)', $date);
        $builder->orderBy('sales.created_at', 'DESC');

        $sales = $builder->get()->getResultArray();

        // Calculate totals
        $totalSales = array_sum(array_column($sales, 'total'));
        $totalTax = array_sum(array_column($sales, 'tax'));
        $totalDiscount = array_sum(array_column($sales, 'discount'));

        $data = [
            'sales' => $sales,
            'date' => $date,
            'total_sales' => $totalSales,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'sales_count' => count($sales),
            'title' => 'Daily Sales Report'
        ];

        return $this->render('sales/daily', $data);
    }

    public function export()
    {
        $this->requireAuth();

        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        $builder = $this->saleModel->builder();
        $builder->select('sales.*, customers.name as customer_name, users.username as user_name');
        $builder->join('customers', 'customers.id = sales.customer_id', 'left');
        $builder->join('users', 'users.id = sales.user_id', 'left');
        $builder->where('DATE(sales.created_at) >=', $startDate);
        $builder->where('DATE(sales.created_at) <=', $endDate);
        $builder->orderBy('sales.created_at', 'DESC');

        $sales = $builder->get()->getResultArray();

        $filename = 'sales_' . $startDate . '_to_' . $endDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, ['Sale ID', 'Date', 'Customer', 'User', 'Subtotal', 'Tax', 'Discount', 'Total', 'Payment Method']);
        
        // CSV data
        foreach ($sales as $sale) {
            $row = [
                $sale['id'],
                $sale['created_at'],
                $sale['customer_name'] ?: 'Walk-in Customer',
                $sale['user_name'],
                $sale['subtotal'],
                $sale['tax'],
                $sale['discount'],
                $sale['total'],
                $sale['payment_method']
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}