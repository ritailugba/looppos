<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'code',
        'price',
        'cost',
        'category_id',
        'supplier_id',
        'description',
        'image',
        'type',
        'quantity',
        'alert_quantity',
        'tax',
        'tax_method',
        'unit',
        'barcode_symbology',
        'details'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'code' => 'required|is_unique[products.code]|max_length[100]',
        'price' => 'required|decimal',
        'cost' => 'decimal'
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
     * Get all products with category information
     */
    public function getProductsWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->findAll();
    }

    /**
     * Get product by code
     */
    public function getByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    /**
     * Get products by category
     */
    public function getByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)->findAll();
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts()
    {
        return $this->where('quantity <=', 'alert_quantity', false)->findAll();
    }
}