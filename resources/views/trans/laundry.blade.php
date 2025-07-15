<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Laundry - POS</title>
    <link href="{{ asset('../../../assets/vendor/laundry/laundry.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🧺 Sistem Informasi Laundry</h1>
            <p class="subtitle">Point of Sales System - Kelola Transaksi Laundry dengan Mudah</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3 id="totalTransactions">0</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="stat-card">
                <h3 id="totalRevenue">Rp 0</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="stat-card">
                <h3 id="activeOrders">0</h3>
                <p>Pesanan Aktif</p>
            </div>
            <div class="stat-card">
                <h3 id="completedOrders">0</h3>
                <p>Pesanan Selesai</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Left Panel: New Transaction -->
            <div class="card">
                <h2>🛒 Transaksi Baru</h2>

                <form id="transactionForm">
                    <div class="form-group">
                        <label for="customerName">Nama Pelanggan</label>
                        <select name="id_customer" id="customerName" required>
                            <option value="">Choose One</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="">No. Telepon</label>
                            <input type="text" id="customerPhone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <input type="text" id="customerAddress" name="address">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pilih Layanan</label>
                        <div class="services-grid">
                            @foreach ($services as $service)
                            <button type="button" class="service-card" onclick="addService('{{ $service->id }}', {{ $service->price }})">
                                <h3>{{ $service->service_name }}</h3>
                                <div class="price">Rp {{ $service->price }}/kg</div>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="serviceWeight">Berat/Jumlah</label>
                            <input type="number" id="serviceWeight" step="0.1" min="0.1" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Layanan</label>
                            <select id="serviceType" required>
                                <option value="">Pilih Layanan</option>
                                @foreach ($services as $service)
                                <option data-price="{{ $service->price }}" data-service_name="{{ $service->service_name }}" value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" rows="3" placeholder="Catatan khusus untuk pesanan..."></textarea>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="addToCart()" style="width: 100%; margin-bottom: 10px;">
                        ➕ Tambah ke Keranjang
                    </button>
                </form>

                <!-- Cart -->
                <div id="cartSection" style="display: none;">
                    <h3>📋 Cart</h3>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Services</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                        </tbody>
                    </table>

                    <div class="total-section">
                        <h3>Total Pembayaran</h3>
                        <div class="total-amount" id="totalAmount">Rp 0</div>
                        <button class="btn btn-success" onclick="processTransaction()" style="width: 100%; margin-top: 15px;">
                            💳 Proses Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Transaction History -->
            <div class="card">
                <h2>📊 Riwayat Transaksi</h2>
                <div class="transaction-list" id="transactionHistory">
                    <div class="transaction-item">
                        <h4>TRX-001 - John Doe</h4>
                        <p>📞 0812-3456-7890</p>
                        <p>🛍️ Cuci Setrika - 2.5kg</p>
                        <p>💰 Rp 17.500</p>
                        <p>📅 13 Juli 2025, 14:30</p>
                        <span class="status-badge status-process">Proses</span>
                    </div>
                    <div class="transaction-item">
                        <h4>TRX-002 - Jane Smith</h4>
                        <p>📞 0813-7654-3210</p>
                        <p>🛍️ Cuci Kering - 3kg</p>
                        <p>💰 Rp 15.000</p>
                        <p>📅 13 Juli 2025, 13:15</p>
                        <span class="status-badge status-ready">Siap</span>
                    </div>
                </div>

                <button class="btn btn-warning" onclick="showAllTransactions()" style="width: 100%; margin-top: 15px;">
                    📋 Lihat Semua Transaksi
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn btn-primary" onclick="showReports()" style="margin: 0 10px;">
                📈 Laporan Penjualan
            </button>
            <button class="btn btn-warning" onclick="manageServices()" style="margin: 0 10px;">
                ⚙️ Kelola Layanan
            </button>
            <button class="btn btn-danger" onclick="clearCart()" style="margin: 0 10px;">
                🗑️ Bersihkan Keranjang
            </button>
        </div>
    </div>

    <!-- Modal for Transaction Details -->
    <div id="transactionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", (event) => {
  console.log("DOM fully loaded and parsed");
});


        const customerSelect = document.querySelector('#customerName');
            customerSelect.addEventListener('change', function () {
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const phoneInput = selectedOption.dataset.phone;
            const addressInput = selectedOption.dataset.address;
            document.querySelector('#customerPhone').value = phoneInput;
            document.querySelector('#customerAddress').value = addressInput;
        });



        let cart = [];
        let transactions = JSON.parse(localStorage.getItem('laundryTransactions')) || [];
        let transactionCounter = transactions.length + 1;
        function addService(serviceName, price) {
            document.getElementById('serviceType').value = serviceName;
            document.getElementById('serviceWeight').focus();
        }

        // function addToCart() {
        //     const serviceType = document.getElementById('serviceType').value;
        //     const weight = parseFloat(document.getElementById('serviceWeight').value);
        //     const notes = document.getElementById('notes').value;

        //     if (!serviceType || !weight || weight <= 0) {
        //         alert('Mohon lengkapi semua field yang diperlukan!');
        //         return;
        //     }


        //     const item = {
        //         id: Date.now(),
        //         service: serviceType,
        //         weight: weight,
        //         price: price,
        //         subtotal: subtotal,
        //         notes: notes
        //     };

        //     cart.push(item);
        //     updateCartDisplay();

        //     // Clear form
        //     document.getElementById('serviceType').value = '';
        //     document.getElementById('serviceWeight').value = '';
        //     document.getElementById('notes').value = '';
        // }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartSection = document.getElementById('cartSection');
            const totalAmount = document.getElementById('totalAmount');

            if (cart.length === 0) {
                cartSection.style.display = 'none';
                return;
            }

            cartSection.style.display = 'block';

            let html = '';
            let total = 0;




        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCartDisplay();
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
            document.getElementById('transactionForm').reset();
        }

        function processTransaction() {
            const customerSelect = document.getElementById('customerName');
            const customerId = customerSelect.value;
            const customerName = customerSelect.options[customerSelect.selectedIndex].text;
            const customerPhone = document.getElementById('customerPhone').value;
            const customerAddress = document.getElementById('customerAddress').value;

            if (!customerName || !customerPhone || cart.length === 0) {
                alert('Mohon lengkapi data pelanggan dan pastikan ada item di keranjang!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);

            const transaction = {
                id: `TRX-${transactionCounter.toString().padStart(3, '0')}`,
                customer: {
                    name: customerName,
                    phone: customerPhone,
                    address: customerAddress
                },
                items: [...cart],
                total: total,
                date: new Date().toISOString(),
                status: 'pending'
            };

            transactions.push(transaction);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            fetch('/trans', {
                    method : "POST",
                    headers : {
                        "Content-Type" : "application/json",
                        'X-CSRF-TOKEN': token,
                    },

                    body:JSON.stringify(transactions)
                })
                .then((response) => response.json())
                .then(function (result) {
                    console.log(result);
                })
                .catch((error) => {
                    console.log(error)
                })

            localStorage.setItem('laundryTransactions', JSON.stringify(transactions));

            transactionCounter++;

            // Show receipt
            showReceipt(transaction);

            // Clear form and cart
            clearCart();
            updateTransactionHistory();
            updateStats();
        }

        function showReceipt(transaction) {
            const receiptHtml = `
                <div class="receipt">
                    <div class="receipt-header">
                        <h2>🧺 LAUNDRY RECEIPT</h2>
                        <p>ID: ${transaction.id}</p>
                        <p>Tanggal: ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <strong>Pelanggan:</strong><br>
                        ${transaction.customer.name}<br>
                        ${transaction.customer.phone}<br>
                        ${transaction.customer.address}
                    </div>

                    <div style="margin-bottom: 20px;">
                        <strong>Detail Pesanan:</strong><br>
                        ${transaction.items.map(item => `
                            <div class="receipt-item">
                                <span>${item.service} (${item.weight} : 'kg'})</span>
                                <span>Rp ${item.subtotal.toLocaleString()}</span>
                            </div>
                        `).join('')}
                    </div>

                    <div class="receipt-total">
                        <div class="receipt-item">
                            <span>TOTAL:</span>
                            <span>Rp ${transaction.total.toLocaleString()}</span>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <p>Terima kasih atas kepercayaan Anda!</p>
                        <p>Barang akan siap dalam 1-2 hari kerja</p>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-primary" onclick="printReceipt()">🖨️ Cetak Struk</button>
                    <button class="btn btn-success" onclick="closeModal()">✅ Selesai</button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = receiptHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function printReceipt() {
            window.print();
        }

        function updateTransactionHistory() {
            const historyContainer = document.getElementById('transactionHistory');
            const recentTransactions = transactions.slice(-5).reverse();

            const html = recentTransactions.map(transaction => `
                <div class="transaction-item">
                    <h4>${transaction.id} - ${transaction.customer.name}</h4>
                    <p>📞 ${transaction.customer.phone}</p>
                    <p>🛍️ ${transaction.items.map(item => `${item.service} - ${item.weight} : 'kg'}`).join(', ')}</p>
                    <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                    <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                    <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span>
                </div>
            `).join('');

            historyContainer.innerHTML = html || '<p>Belum ada transaksi</p>';
        }

        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'process': 'Proses',
                'ready': 'Siap',
                'delivered': 'Selesai'
            };
            return statusMap[status] || status;
        }

        function updateStats() {
            const totalTransactions = transactions.length;
            const totalRevenue = transactions.reduce((sum, t) => sum + t.total, 0);
            const activeOrders = transactions.filter(t => t.status !== 'delivered').length;
            const completedOrders = transactions.filter(t => t.status === 'delivered').length;

            document.getElementById('totalTransactions').textContent = totalTransactions;
            document.getElementById('totalRevenue').textContent = `Rp ${totalRevenue.toLocaleString()}`;
            document.getElementById('activeOrders').textContent = activeOrders;
            document.getElementById('completedOrders').textContent = completedOrders;
        }

        function showAllTransactions() {
            const allTransactionsHtml = `
                <h2>📋 Semua Transaksi</h2>
                <div style="max-height: 400px; overflow-y: auto;">
                    ${transactions.map(transaction => `
                        <div class="transaction-item">
                            <h4>${transaction.id} - ${transaction.customer.name}</h4>
                            <p>📞 ${transaction.customer.phone}</p>
                            <p>🛍️ ${transaction.items.map(item => `${item.service} - ${item.weight} : 'kg'}`).join(', ')}</p>
                            <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                            <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                            <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span>
                            <button class="btn btn-primary" onclick="updateTransactionStatus('${transaction.id}')" style="margin-top: 10px; padding: 5px 15px; font-size: 12px;">
                                📝 Update Status
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;

            document.getElementById('modalContent').innerHTML = allTransactionsHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function showReports() {
            const today = new Date();
            const thisMonth = today.getMonth();
            const thisYear = today.getFullYear();

            const monthlyTransactions = transactions.filter(t => {
                const tDate = new Date(t.date);
                return tDate.getMonth() === thisMonth && tDate.getFullYear() === thisYear;
            });

            const monthlyRevenue = monthlyTransactions.reduce((sum, t) => sum + t.total, 0);

            const serviceStats = {};
            transactions.forEach(t => {
                t.items.forEach(item => {
                    if (!serviceStats[item.service]) {
                        serviceStats[item.service] = { count: 0, revenue: 0 };
                    }
                    serviceStats[item.service].count++;
                    serviceStats[item.service].revenue += item.subtotal;
                });
            });

            const reportsHtml = `
                <h2>📈 Laporan Penjualan</h2>

                <div class="stats-grid" style="margin-bottom: 20px;">
                    <div class="stat-card">
                        <h3>${transactions.length}</h3>
                        <p>Total Transaksi</p>
                    </div>
                    <div class="stat-card">
                        <h3>${monthlyTransactions.length}</h3>
                        <p>Transaksi Bulan Ini</p>
                    </div>
                    <div class="stat-card">
                        <h3>Rp ${monthlyRevenue.toLocaleString()}</h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                </div>

                <h3>📊 Statistik Layanan</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Jumlah Order</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.entries(serviceStats).map(([service, stats]) => `
                            <tr>
                                <td>${service}</td>
                                <td>${stats.count}</td>
                                <td>Rp ${stats.revenue.toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            document.getElementById('modalContent').innerHTML = reportsHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function manageServices() {
            const servicesHtml = `
                <h2>⚙️ Kelola Layanan</h2>
                <p>Fitur ini memungkinkan Anda mengelola jenis layanan dan harga.</p>

                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cuci Kering</td>
                            <td>Rp 5.000</td>
                            <td>per kg</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Cuci Setrika</td>
                            <td>Rp 7.000</td>
                            <td>per kg</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Setrika Saja</td>
                            <td>Rp 3.000</td>
                            <td>per kg</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Dry Clean</td>
                            <td>Rp 15.000</td>
                            <td>per kg</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Cuci Sepatu</td>
                            <td>Rp 25.000</td>
                            <td>per pasang</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Cuci Karpet</td>
                            <td>Rp 20.000</td>
                            <td>per m²</td>
                            <td><span class="status-badge status-ready">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-primary" onclick="alert('Fitur akan segera tersedia!')">
                        ➕ Tambah Layanan Baru
                    </button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = servicesHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function updateTransactionStatus(transactionId) {
            const transaction = transactions.find(t => t.id === transactionId);
            if (!transaction) return;

            const statusOptions = [
                { value: 'pending', text: 'Menunggu' },
                { value: 'process', text: 'Sedang Proses' },
                { value: 'ready', text: 'Siap Diambil' },
                { value: 'delivered', text: 'Selesai' }
            ];

            const statusHtml = `
                <h2>📝 Update Status Transaksi</h2>
                <h3>${transaction.id} - ${transaction.customer.name}</h3>
                <p>Status saat ini: <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span></p>

                <div class="form-group">
                    <label>Pilih Status Baru:</label>
                    <select id="newStatus" style="width: 100%; padding: 10px; margin: 10px 0;">
                        ${statusOptions.map(option => `
                            <option value="${option.value}" ${transaction.status === option.value ? 'selected' : ''}>
                                ${option.text}
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-success" onclick="saveStatusUpdate('${transactionId}')">
                        ✅ Simpan Update
                    </button>
                    <button class="btn btn-danger" onclick="closeModal()" style="margin-left: 10px;">
                        ❌ Batal
                    </button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = statusHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function saveStatusUpdate(transactionId) {
            const newStatus = document.getElementById('newStatus').value;
            const transactionIndex = transactions.findIndex(t => t.id === transactionId);

            if (transactionIndex !== -1) {
                transactions[transactionIndex].status = newStatus;
                localStorage.setItem('laundryTransactions', JSON.stringify(transactions));
                updateTransactionHistory();
                updateStats();
                closeModal();
                alert('Status berhasil diupdate!');
            }
        }

        function closeModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }

        function formatNumber(input) {
            // Replace comma with dot for decimal separator
            let value = input.value.replace(',', '.');

            // Ensure only valid decimal number
            if (!/^\d*\.?\d*$/.test(value)) {
                value = value.slice(0, -1);
            }

            // Update input value
            input.value = value;
        }

        function parseDecimal(value) {
            // Handle both comma and dot as decimal separator
            return parseFloat(value.toString().replace(',', '.')) || 0;
        }

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            updateTransactionHistory();
            updateStats();

            // Add event listener for weight input to handle decimal with comma
            const weightInput = document.getElementById('serviceWeight');
            weightInput.addEventListener('input', function() {
                formatNumber(this);
            });

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('transactionModal');
                if (event.target === modal) {
                    closeModal();
                }
            };
        });

        // Update addToCart function to handle decimal with comma
        function addToCart() {
            const selectService = document.getElementById('serviceType');
            const optionService = selectService.options[selectService.selectedIndex];
            const serviceName = optionService.getAttribute('data-service_name');
            const priceService = parseInt(optionService.dataset.price);
            const nameService = optionService.textContent;
            const weightValue = document.getElementById('serviceWeight').value;
            const weight = parseDecimal(weightValue);
            const notes = document.getElementById('notes').value;

            if (!selectService || !weightValue || weight <= 0) {
                alert('Mohon lengkapi semua field yang diperlukan!');
                return;
            }

            const prices = {
                @foreach ($services as $service)
                    '{{ $service->id }}': '{{ $service->price }}',
                @endforeach

            };

            const price = prices[serviceType];
            const subtotal = priceService * weight;

            const item = {
                id: Date.now(),
                service: serviceName,
                weight: weight,
                price: priceService,
                subtotal: subtotal,
                notes: notes
            };

            cart.push(item);
            updateCartDisplay();

            // Clear form
            document.getElementById('serviceType').value = '';
            document.getElementById('serviceWeight').value = '';
            document.getElementById('notes').value = '';
        }

        // Update cart display to show decimal properly
        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartSection = document.getElementById('cartSection');
            const totalAmount = document.getElementById('totalAmount');

            if (cart.length === 0) {
                cartSection.style.display = 'none';
                return;
            }

            cartSection.style.display = 'block';

            let html = '';
            let total = 0;
            console.log(cart);
            cart.forEach(item => {
                // const unit = item.service.includes('Sepatu') ? 'pasang' :
                //            item.service.includes('Karpet') ? 'm²' : 'kg';

                // Format weight to show decimal properly
                const formattedWeight = item.weight % 1 === 0 ?
                    item.weight.toString() :
                    item.weight.toFixed(1).replace('.', ',');

                html += `
                    <tr>
                        <td>${item.service}</td>
                        <td>${formattedWeight}</td>
                        <td>Rp ${item.price.toLocaleString()}</td>
                        <td>Rp ${item.subtotal.toLocaleString()}</td>
                        <td>
                            <button class="btn btn-danger" onclick="removeFromCart(${item.id})" style="padding: 5px 10px; font-size: 12px;">
                                🗑️
                            </button>
                        </td>
                    </tr>
                `;
                total += item.subtotal;
            });

            cartItems.innerHTML = html;
            totalAmount.textContent = `Rp ${total.toLocaleString()}`;
        }

        // Add some sample data for demonstration
        function addSampleData() {
            const sampleTransactions = [
                {
                    id: 'TRX-001',
                    customer: { name: 'John Doe', phone: '0812-3456-7890', address: 'Jl. Merdeka 123' },
                    items: [{ service: 'Cuci Setrika', weight: 2.5, price: 7000, subtotal: 17500 }],
                    total: 17500,
                    date: new Date().toISOString(),
                    status: 'process'
                },
                {
                    id: 'TRX-002',
                    customer: { name: 'Jane Smith', phone: '0813-7654-3210', address: 'Jl. Sudirman 456' },
                    items: [{ service: 'Cuci Kering', weight: 3, price: 5000, subtotal: 15000 }],
                    total: 15000,
                    date: new Date(Date.now() - 3600000).toISOString(),
                    status: 'ready'
                }
            ];

            if (transactions.length === 0) {
                transactions = sampleTransactions;
                localStorage.setItem('laundryTransactions', JSON.stringify(transactions));
                transactionCounter = transactions.length + 1;
            }
        }

        // Initialize with sample data
        addSampleData();

    </script>
