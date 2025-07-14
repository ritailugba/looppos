<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Product Selection -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Products</h5>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="product-search" placeholder="Search products...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="category-filter">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary" id="scan-barcode">
                                <i class="fas fa-barcode"></i> Scan
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row" id="products-grid">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-3 col-sm-4 col-6 mb-3 product-item" data-category="<?= $product['category_id'] ?>">
                                <div class="card h-100 product-card" data-product-id="<?= $product['id'] ?>" style="cursor: pointer;">
                                    <div class="card-body text-center p-2">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="<?= base_url('files/products/' . $product['image']) ?>" 
                                                 class="img-fluid mb-2" style="max-height: 60px;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center mb-2" 
                                                 style="height: 60px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <h6 class="card-title small"><?= $product['name'] ?></h6>
                                        <p class="card-text text-muted small mb-1"><?= $product['code'] ?></p>
                                        <p class="card-text fw-bold text-primary">$<?= number_format($product['price'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cart</h5>
                </div>
                <div class="card-body">
                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <label for="customer-select" class="form-label">Customer</label>
                        <select class="form-select" id="customer-select">
                            <option value="">Walk-in Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>"><?= $customer['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Cart Items -->
                    <div id="cart-items" class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <p>Cart is empty</p>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span id="cart-tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>Total:</span>
                            <span id="cart-total">$0.00</span>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment-method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment-method">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="check">Check</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" id="process-sale" disabled>
                                <i class="fas fa-credit-card me-2"></i> Process Sale
                            </button>
                            <button class="btn btn-outline-secondary" id="hold-sale" disabled>
                                <i class="fas fa-pause me-2"></i> Hold Sale
                            </button>
                            <button class="btn btn-outline-danger" id="clear-cart">
                                <i class="fas fa-trash me-2"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Today's Sales</h6>
                            <h4>$<?= number_format($daily_sales ?? 0, 2) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Products</h6>
                            <h4><?= count($products) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Customers</h6>
                            <h4><?= count($customers) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Low Stock</h6>
                            <h4><?= count($low_stock_products ?? []) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let cart = [];
let cartTotal = 0;

$(document).ready(function() {
    // Product search
    $('#product-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterProducts(searchTerm);
    });

    // Category filter
    $('#category-filter').on('change', function() {
        const categoryId = $(this).val();
        filterByCategory(categoryId);
    });

    // Add product to cart
    $('.product-card').on('click', function() {
        const productId = $(this).data('product-id');
        addToCart(productId);
    });

    // Clear cart
    $('#clear-cart').on('click', function() {
        clearCart();
    });

    // Process sale
    $('#process-sale').on('click', function() {
        processSale();
    });
});

function filterProducts(searchTerm) {
    $('.product-item').each(function() {
        const productName = $(this).find('.card-title').text().toLowerCase();
        const productCode = $(this).find('.text-muted').text().toLowerCase();
        
        if (productName.includes(searchTerm) || productCode.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function filterByCategory(categoryId) {
    if (categoryId === '') {
        $('.product-item').show();
    } else {
        $('.product-item').each(function() {
            if ($(this).data('category') == categoryId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
}

function addToCart(productId) {
    // Find product data
    const productCard = $(`.product-card[data-product-id="${productId}"]`);
    const productName = productCard.find('.card-title').text();
    const productCode = productCard.find('.text-muted').text();
    const productPrice = parseFloat(productCard.find('.text-primary').text().replace('$', ''));

    // Check if product already in cart
    const existingItem = cart.find(item => item.id == productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
        existingItem.total = existingItem.quantity * existingItem.price;
    } else {
        cart.push({
            id: productId,
            name: productName,
            code: productCode,
            price: productPrice,
            quantity: 1,
            total: productPrice
        });
    }

    updateCartDisplay();
}

function updateCartDisplay() {
    const cartContainer = $('#cart-items');
    cartContainer.empty();

    if (cart.length === 0) {
        cartContainer.html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <p>Cart is empty</p>
            </div>
        `);
        $('#process-sale, #hold-sale').prop('disabled', true);
    } else {
        cart.forEach((item, index) => {
            cartContainer.append(`
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">${item.code}</small>
                    </div>
                    <div class="text-end">
                        <div class="input-group input-group-sm mb-1" style="width: 80px;">
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, -1)">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, 1)">+</button>
                        </div>
                        <div class="fw-bold">$${item.total.toFixed(2)}</div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `);
        });
        $('#process-sale, #hold-sale').prop('disabled', false);
    }

    updateCartTotals();
}

function updateQuantity(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    } else {
        cart[index].total = cart[index].quantity * cart[index].price;
    }
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function updateCartTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
    const tax = subtotal * 0.1; // 10% tax
    const total = subtotal + tax;

    $('#cart-subtotal').text(`$${subtotal.toFixed(2)}`);
    $('#cart-tax').text(`$${tax.toFixed(2)}`);
    $('#cart-total').text(`$${total.toFixed(2)}`);
    
    cartTotal = total;
}

function clearCart() {
    cart = [];
    updateCartDisplay();
}

function processSale() {
    if (cart.length === 0) {
        alert('Cart is empty');
        return;
    }

    const customerId = $('#customer-select').val();
    const paymentMethod = $('#payment-method').val();

    // Here you would send the sale data to the server
    const saleData = {
        customer_id: customerId || null,
        payment_method: paymentMethod,
        items: cart,
        total: cartTotal
    };

    // For now, just show a success message
    alert('Sale processed successfully!');
    clearCart();
}
</script>
<?= $this->endSection() ?>