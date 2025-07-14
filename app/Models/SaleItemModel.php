<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'sale_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'total_price',
        'discount',
        'tax',
        'subtotal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'sale_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|decimal',
        'unit_price' => 'required|decimal',
        'total_price' => 'required|decimal'
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
     * Get sale items with product details
     */
    public function getSaleItemsWithProduct($saleId)
    {
        return $this->select('sale_items.*, products.name as product_name, products.code as product_code')
                    ->join('products', 'products.id = sale_items.product_id', 'left')
                    ->where('sale_items.sale_id', $saleId)
                    ->findAll();
    }

    /**
     * Get items by sale ID
     */
    public function getBySaleId($saleId)
    {
        return $this->where('sale_id', $saleId)->findAll();
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts($limit = 10)
    {
        return $this->select('product_id, product_name, SUM(quantity) as total_sold')
                    ->groupBy('product_id')
                    ->orderBy('total_sold', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}