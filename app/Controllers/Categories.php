<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $this->requireAuth();

        $data = [
            'categories' => $this->categoryModel->findAll(),
            'title' => 'Categories Management'
        ];

        return $this->render('categories/index', $data);
    }

    public function add()
    {
        $this->requireAuth();

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->categoryModel->insert($data)) {
                session()->setFlashdata('success', 'Category added successfully');
            } else {
                session()->setFlashdata('error', 'Failed to add category');
            }

            return redirect()->to('/categories');
        }

        $data = [
            'title' => 'Add Category'
        ];

        return $this->render('categories/add', $data);
    }

    public function edit($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/categories');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found');
            return redirect()->to('/categories');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->categoryModel->update($id, $data)) {
                session()->setFlashdata('success', 'Category updated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to update category');
            }

            return redirect()->to('/categories');
        }

        $data = [
            'category' => $category,
            'title' => 'Edit Category'
        ];

        return $this->render('categories/edit', $data);
    }

    public function delete($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/categories');
        }

        // Check if category has products
        $productModel = new \App\Models\ProductModel();
        $productsCount = $productModel->where('category_id', $id)->countAllResults();

        if ($productsCount > 0) {
            session()->setFlashdata('error', 'Cannot delete category. It has ' . $productsCount . ' products associated with it.');
            return redirect()->to('/categories');
        }

        if ($this->categoryModel->delete($id)) {
            session()->setFlashdata('success', 'Category deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete category');
        }

        return redirect()->to('/categories');
    }
}