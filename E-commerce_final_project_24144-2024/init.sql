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
-- PRODUCTS (IMPORTANT FIX: IDs RE-ORDERED)
-- =========================
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    product_type VARCHAR(50) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    stock_quantity INT DEFAULT 10,
    image_url VARCHAR(255) DEFAULT 'default.jpg'
);

-- IMPORTANT: IDs will now start from 1 → matches order_items fix
INSERT INTO products (name, brand, product_type, description, price, stock_quantity, image_url)
VALUES
('Apple MacBook Pro 16 Inch (M3 Max)','Apple','Laptop','Apple M3 Max chip...',3499.00,5,'macbook_pro_16.png'),
('ASUS ROG Strix Scar 17','ASUS','Laptop','AMD Ryzen 9...',2899.00,3,'rog_scar_17.png'),
('Lenovo Legion Pro 7i','Lenovo','Laptop','Intel i9...',2199.00,7,'legion_pro_7i.png'),
('MSI Stealth 16 Studio','MSI','Laptop','Intel i7...',1749.00,4,'msi_stealth.png'),
('Razer Blade 14 AMD','Razer','Laptop','Ryzen 9...',2399.00,3,'razer_blade_14.png'),
('Logitech MX Mechanical Keyboard','Logitech','Accessory','Mechanical keyboard...',169.00,15,'mx_mechanical.png'),
('Sony WH-1000XM5 ANC','Sony','Accessory','Noise cancelling...',398.00,8,'sony_xm5.png'),
('Razer Basilisk V3 Pro','Razer','Accessory','Gaming mouse...',159.00,20,'basilisk_v3.png'),
('Dell UltraSharp 32 4K','Dell','Accessory','4K Monitor...',749.00,5,'dell_32_4k.png'),
('Anker Power Bank','Anker','Accessory','20000mAh...',129.00,25,'anker_prime.png');


-- =========================
-- ORDERS
-- =========================
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    customer_phone VARCHAR(50),
    delivery_address TEXT,
    payment_method VARCHAR(50),
    total_amount NUMERIC(10,2),
    order_status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO orders (customer_name, customer_email, customer_phone, delivery_address, payment_method, total_amount, order_status)
VALUES
('reem','reem@gmail.com','0700000000','kk 1222','Airtel Money',9596.00,'Pending');


-- =========================
-- ORDER ITEMS (FIXED FOREIGN KEYS)
-- =========================
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INT REFERENCES orders(id) ON DELETE CASCADE,
    product_id INT REFERENCES products(id) ON DELETE RESTRICT,
    quantity INT NOT NULL,
    price NUMERIC(10,2) NOT NULL
);

-- FIXED: products now start from 1..10 (NOT 31, 11 etc)
INSERT INTO order_items (order_id, product_id, quantity, price)
VALUES
(1, 1, 3, 3499.00),
(1, 5, 1, 2399.00);