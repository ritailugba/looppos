<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class Reports extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $productModel;
    protected $customerModel;

    public function __construct()
    {
        $this->saleModel = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $this->requireAuth();

        $data = [
            'title' => 'Reports Dashboard'
        ];

        return $this->render('reports/index', $data);
    }

    public function salesReport()
    {
        $this->requireAuth();

        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        // Sales summary
        $builder = $this->saleModel->builder();
        $builder->select('COUNT(*) as total_sales, SUM(total) as total_revenue, SUM(tax) as total_tax, AVG(total) as avg_sale');
        $builder->where('DATE(created_at) >=', $startDate);
        $builder->where('DATE(created_at) <=', $endDate);
        $summary = $builder->get()->getRowArray();

        // Daily sales breakdown
        $builder = $this->saleModel->builder();
        $builder->select('DATE(created_at) as sale_date, COUNT(*) as daily_sales, SUM(total) as daily_revenue');
        $builder->where('DATE(created_at) >=', $startDate);
        $builder->where('DATE(created_at) <=', $endDate);
        $builder->groupBy('DATE(created_at)');
        $builder->orderBy('sale_date', 'ASC');
        $dailySales = $builder->get()->getResultArray();

        // Payment method breakdown
        $builder = $this->saleModel->builder();
        $builder->select('payment_method, COUNT(*) as count, SUM(total) as total');
        $builder->where('DATE(created_at) >=', $startDate);
        $builder->where('DATE(created_at) <=', $endDate);
        $builder->groupBy('payment_method');
        $paymentMethods = $builder->get()->getResultArray();

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'summary' => $summary,
            'daily_sales' => $dailySales,
            'payment_methods' => $paymentMethods,
            'title' => 'Sales Report'
        ];

        return $this->render('reports/sales', $data);
    }

    public function productReport()
    {
        $this->requireAuth();

        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        // Top selling products
        $builder = $this->saleItemModel->builder();
        $builder->select('products.name, products.code, SUM(sale_items.quantity) as total_sold, SUM(sale_items.total) as total_revenue');
        $builder->join('products', 'products.id = sale_items.product_id');
        $builder->join('sales', 'sales.id = sale_items.sale_id');
        $builder->where('DATE(sales.created_at) >=', $startDate);
        $builder->where('DATE(sales.created_at) <=', $endDate);
        $builder->groupBy('sale_items.product_id');
        $builder->orderBy('total_sold', 'DESC');
        $builder->limit(20);
        $topProducts = $builder->get()->getResultArray();

        // Low stock products
        $lowStockProducts = $this->productModel->where('stock_quantity <=', 'min_stock', false)->findAll();

        // Category performance
        $builder = $this->saleItemModel->builder();
        $builder->select('categories.name as category_name, SUM(sale_items.quantity) as total_sold, SUM(sale_items.total) as total_revenue');
        $builder->join('products', 'products.id = sale_items.product_id');
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('sales', 'sales.id = sale_items.sale_id');
        $builder->where('DATE(sales.created_at) >=', $startDate);
        $builder->where('DATE(sales.created_at) <=', $endDate);
        $builder->groupBy('products.category_id');
        $builder->orderBy('total_revenue', 'DESC');
        $categoryPerformance = $builder->get()->getResultArray();

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'top_products' => $topProducts,
            'low_stock_products' => $lowStockProducts,
            'category_performance' => $categoryPerformance,
            'title' => 'Product Report'
        ];

        return $this->render('reports/products', $data);
    }

    public function customerReport()
    {
        $this->requireAuth();

        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        // Top customers
        $builder = $this->saleModel->builder();
        $builder->select('customers.name, customers.email, COUNT(sales.id) as total_purchases, SUM(sales.total) as total_spent');
        $builder->join('customers', 'customers.id = sales.customer_id');
        $builder->where('DATE(sales.created_at) >=', $startDate);
        $builder->where('DATE(sales.created_at) <=', $endDate);
        $builder->groupBy('sales.customer_id');
        $builder->orderBy('total_spent', 'DESC');
        $builder->limit(20);
        $topCustomers = $builder->get()->getResultArray();

        // New customers
        $newCustomers = $this->customerModel->where('DATE(created_at) >=', $startDate)
                                          ->where('DATE(created_at) <=', $endDate)
                                          ->findAll();

        // Customer summary
        $totalCustomers = $this->customerModel->countAllResults();
        $activeCustomers = $this->saleModel->select('DISTINCT customer_id')
                                          ->where('DATE(created_at) >=', $startDate)
                                          ->where('DATE(created_at) <=', $endDate)
                                          ->countAllResults();

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'top_customers' => $topCustomers,
            'new_customers' => $newCustomers,
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'title' => 'Customer Report'
        ];

        return $this->render('reports/customers', $data);
    }

    public function inventoryReport()
    {
        $this->requireAuth();

        // Low stock products
        $lowStockProducts = $this->productModel->where('stock_quantity <=', 'min_stock', false)->findAll();

        // Out of stock products
        $outOfStockProducts = $this->productModel->where('stock_quantity', 0)->findAll();

        // High stock products
        $highStockProducts = $this->productModel->where('stock_quantity >', 100)->findAll();

        // Total inventory value
        $builder = $this->productModel->builder();
        $builder->select('SUM(stock_quantity * cost) as total_cost_value, SUM(stock_quantity * price) as total_retail_value');
        $inventoryValue = $builder->get()->getRowArray();

        $data = [
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'high_stock_products' => $highStockProducts,
            'inventory_value' => $inventoryValue,
            'title' => 'Inventory Report'
        ];

        return $this->render('reports/inventory', $data);
    }

    public function exportSalesReport()
    {
        $this->requireAuth();

        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        $builder = $this->saleModel->builder();
        $builder->select('sales.*, customers.name as customer_name');
        $builder->join('customers', 'customers.id = sales.customer_id', 'left');
        $builder->where('DATE(sales.created_at) >=', $startDate);
        $builder->where('DATE(sales.created_at) <=', $endDate);
        $builder->orderBy('sales.created_at', 'DESC');

        $sales = $builder->get()->getResultArray();

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['Sale ID', 'Date', 'Customer', 'Subtotal', 'Tax', 'Discount', 'Total', 'Payment Method']);
        
        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale['id'],
                $sale['created_at'],
                $sale['customer_name'] ?: 'Walk-in Customer',
                $sale['subtotal'],
                $sale['tax'],
                $sale['discount'],
                $sale['total'],
                $sale['payment_method']
            ]);
        }
        
        fclose($output);
        exit;
    }
}