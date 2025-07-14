<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $this->requireAuth();

        $data = [
            'customers' => $this->customerModel->findAll(),
            'title' => 'Customers Management'
        ];

        return $this->render('customers/index', $data);
    }

    public function add()
    {
        $this->requireAuth();

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'zip_code' => $this->request->getPost('zip_code'),
                'country' => $this->request->getPost('country'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->customerModel->insert($data)) {
                session()->setFlashdata('success', 'Customer added successfully');
            } else {
                session()->setFlashdata('error', 'Failed to add customer');
            }

            return redirect()->to('/customers');
        }

        $data = [
            'title' => 'Add Customer'
        ];

        return $this->render('customers/add', $data);
    }

    public function edit($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/customers');
        }

        $customer = $this->customerModel->find($id);
        if (!$customer) {
            session()->setFlashdata('error', 'Customer not found');
            return redirect()->to('/customers');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'zip_code' => $this->request->getPost('zip_code'),
                'country' => $this->request->getPost('country'),
                'status' => $this->request->getPost('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->customerModel->update($id, $data)) {
                session()->setFlashdata('success', 'Customer updated successfully');
            } else {
                session()->setFlashdata('error', 'Failed to update customer');
            }

            return redirect()->to('/customers');
        }

        $data = [
            'customer' => $customer,
            'title' => 'Edit Customer'
        ];

        return $this->render('customers/edit', $data);
    }

    public function delete($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/customers');
        }

        // Check if customer has sales
        $saleModel = new \App\Models\SaleModel();
        $salesCount = $saleModel->where('customer_id', $id)->countAllResults();

        if ($salesCount > 0) {
            session()->setFlashdata('error', 'Cannot delete customer. They have ' . $salesCount . ' sales associated with them.');
            return redirect()->to('/customers');
        }

        if ($this->customerModel->delete($id)) {
            session()->setFlashdata('success', 'Customer deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete customer');
        }

        return redirect()->to('/customers');
    }

    public function view($id = null)
    {
        $this->requireAuth();

        if (!$id) {
            return redirect()->to('/customers');
        }

        $customer = $this->customerModel->find($id);
        if (!$customer) {
            session()->setFlashdata('error', 'Customer not found');
            return redirect()->to('/customers');
        }

        // Get customer's sales history
        $saleModel = new \App\Models\SaleModel();
        $sales = $saleModel->where('customer_id', $id)->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'customer' => $customer,
            'sales' => $sales,
            'title' => 'Customer Details'
        ];

        return $this->render('customers/view', $data);
    }
}