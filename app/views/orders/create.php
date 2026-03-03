<style>
        .order-create-container {
            padding: 20px;
        }
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .item-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .item-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transform: translateY(-3px);
        }
        .item-card.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }
        .item-image {
            width: 80px;
            height: 80px;
            margin: 0 auto 8px;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f5f5f5;
            position: relative;
        }
        .item-image:empty::before {
            content: 'Tidak Ada\\AFoto';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            color: #999;
            text-align: center;
            white-space: pre;
            font-weight: normal;
        }
        .item-image:empty::after {
            content: '\\f03e';
            font-family: 'FontAwesome';
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            color: #ccc;
        }
        .order-summary {
            position: sticky;
            top: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .cart-item {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 10px;
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .qty-controls button {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: none;
            background: #667eea;
            color: white;
            cursor: pointer;
        }
    </style>
<br>
<div class="order-create-container">
    <div class="order-header">
        <?php if(!empty($panel)){echo $panel;}?>
        <p class="mb-0">Pilih item dan atur pesanan customer</p>
    </div>

    <div class="row">
        <!-- Left: Items Selection -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-utensils"></i> Pilih Menu</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchItems" placeholder="Cari menu...">
                    </div>
                    <div id="itemsGrid" class="item-grid">
                        <!-- Items will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="col-md-5">
            <div class="order-summary">
                <h5 class="mb-3">Pesanan</h5>
                
                <!-- Order Type -->
                <div class="form-group">
                    <label><strong>Jenis Pesanan:</strong></label>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <label class="btn btn-outline-primary active" onclick="selectOrderType('DINE_IN')">
                            <input type="radio" name="order_type" value="DINE_IN" checked> Dine In
                        </label>
                        <label class="btn btn-outline-success" onclick="selectOrderType('TAKEAWAY')">
                            <input type="radio" name="order_type" value="TAKEAWAY"> Takeaway
                        </label>
                    </div>
                </div>

                <!-- Table Selection (for Dine In) -->
                <div class="form-group" id="tableSelection">
                    <label><strong>Pilih Meja:</strong></label>
                    <select class="form-control" id="tableId">
                        <option value="">-- Pilih Meja --</option>
                        <?php foreach($tables as $table): ?>
                            <option value="<?php echo $table->id_table; ?>">
                                Meja <?php echo $table->no_meja; ?> (Kapasitas: <?php echo $table->kapasitas; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Customer Info -->
                <div class="form-group">
                    <label>Nama Customer (Opsional):</label>
                    <input type="text" class="form-control" id="customerName" placeholder="Nama customer...">
                </div>

                <div class="form-group">
                    <label>No. HP (Opsional):</label>
                    <input type="text" class="form-control" id="customerPhone" placeholder="08xxxxxxxxxx">
                </div>

                <hr>

                <!-- Cart Items -->
                <h6>Items:</h6>
                <div id="cartItems" style="max-height: 250px; overflow-y: auto;">
                    <p class="text-muted text-center">Belum ada item dipilih</p>
                </div>

                <hr>

                <!-- Total -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total:</h5>
                    <h4 class="mb-0 text-primary" id="totalAmount">Rp 0</h4>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label>Catatan:</label>
                    <textarea class="form-control" id="orderNotes" rows="2" placeholder="Catatan khusus..."></textarea>
                </div>

                <!-- Actions -->
                <button class="btn btn-success btn-block btn-lg" onclick="submitOrder()">
                    <i class="fa fa-check"></i> Buat Pesanan
                </button>
                <a href="<?php echo site_url('orders'); ?>" class="btn btn-secondary btn-block">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

