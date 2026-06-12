-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin'
);

INSERT INTO users (username, password, role)
VALUES
('reem', '$2y$10$k/OYrzpuRcVq/NWf5zzayOYUXs2xiDrFzEwNiyn9GUfIzxSXLPWiC', 'admin');


-- =========================
-- PRODUCTS
-- =========================
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    product_type VARCHAR(50) NOT NULL CHECK (product_type IN ('Laptop','Accessory')),
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    stock_quantity INT DEFAULT 10,
    image_url VARCHAR(255) DEFAULT 'default.jpg'
);

INSERT INTO products (name, brand, product_type, description, price, stock_quantity, image_url)
VALUES
('Apple MacBook Pro 16 Inch (M3 Max)','Apple','Laptop','Apple M3 Max chip with 16-core CPU and 40-core GPU, 48GB Unified Memory, 1TB SSD. Liquid Retina XDR display.',3499.00,5,'macbook_pro_16.png'),
('ASUS ROG Strix Scar 17','ASUS','Laptop','AMD Ryzen 9 7945HX, 32GB DDR5 RAM, 2TB NVMe SSD, RTX 4090.',2899.00,3,'rog_scar_17.png'),
('Lenovo Legion Pro 7i','Lenovo','Laptop','Intel Core i9-13900HX, 32GB RAM, RTX 4080.',2199.00,7,'legion_pro_7i.png'),
('MSI Stealth 16 Studio','MSI','Laptop','Intel i7, RTX 4070, 1TB SSD.',1749.00,4,'msi_stealth.png'),
('Razer Blade 14 AMD','Razer','Laptop','Ryzen 9, RTX 4070, compact gaming laptop.',2399.00,5,'razer_blade_14.png'),
('Logitech MX Mechanical Keyboard','Logitech','Accessory','Mechanical keyboard for productivity.',169.00,15,'mx_mechanical.png'),
('Sony WH-1000XM5','Sony','Accessory','Noise cancelling headphones.',398.00,8,'sony_xm5.png'),
('Razer Basilisk V3 Pro','Razer','Accessory','Gaming mouse with RGB.',159.00,20,'basilisk_v3.png'),
('Dell UltraSharp 32 4K','Dell','Accessory','Professional 4K monitor.',749.00,5,'dell_32_4k.png'),
('Anker Power Bank','Anker','Accessory','20000mAh fast charging power bank.',129.00,25,'anker_prime.png');


-- =========================
-- ORDERS
-- =========================
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    delivery_address TEXT NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Mobile Money',
    total_amount NUMERIC(10,2) NOT NULL,
    order_status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO orders (customer_name, customer_email, customer_phone, delivery_address, payment_method, total_amount, order_status)
VALUES
('reem','reem@gmail.com','0700000000','kk 1222','Airtel Money',9596.00,'Pending');


-- =========================
-- ORDER ITEMS
-- =========================
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INT REFERENCES orders(id) ON DELETE CASCADE,
    product_id INT REFERENCES products(id),
    quantity INT NOT NULL,
    price NUMERIC(10,2) NOT NULL
);

INSERT INTO order_items (order_id, product_id, quantity, price)
VALUES
(1, 31, 3, 2399.00),
(1, 11, 1, 2399.00);