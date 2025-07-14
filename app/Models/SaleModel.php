<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'reference_no',
        'customer_id',
        'user_id',
        'store_id',
        'total_items',
        'total_amount',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'payment_status',
        'payment_method',
        'sale_status',
        'note',
        'sale_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'reference_no' => 'required|is_unique[sales.reference_no]|max_length[100]',
        'customer_id' => 'required|integer',
        'user_id' => 'required|integer',
        'total_amount' => 'required|decimal',
        'grand_total' => 'required|decimal'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get sales with customer and user information
     */
    public function getSalesWithDetails()
    {
        return $this->select('sales.*, customers.name as customer_name, users.username as user_name')
                    ->join('customers', 'customers.id = sales.customer_id', 'left')
                    ->join('users', 'users.id = sales.user_id', 'left')
                    ->orderBy('sales.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get sales by date range
     */
    public function getSalesByDateRange($startDate, $endDate)
    {
        return $this->where('sale_date >=', $startDate)
                    ->where('sale_date <=', $endDate)
                    ->findAll();
    }

    /**
     * Get sales by customer
     */
    public function getSalesByCustomer($customerId)
    {
        return $this->where('customer_id', $customerId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get daily sales total
     */
    public function getDailySalesTotal($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        return $this->selectSum('grand_total')
                    ->where('DATE(sale_date)', $date)
                    ->where('sale_status', 'completed')
                    ->get()
                    ->getRow()
                    ->grand_total ?? 0;
    }

    /**
     * Generate reference number
     */
    public function generateReferenceNo()
    {
        $prefix = 'SALE-';
        $date = date('Ymd');
        $lastSale = $this->like('reference_no', $prefix . $date)
                         ->orderBy('id', 'DESC')
                         ->first();
        
        if ($lastSale) {
            $lastNumber = (int) substr($lastSale['reference_no'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}