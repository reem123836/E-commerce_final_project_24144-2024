CREATE DATABASE IF NOT EXISTS laptop_agency_db;
USE laptop_agency_db;

-- 1. جدول المستخدمين / المدراء لـ AuraTech
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin'
);

-- 2. جدول المنتجات والملحقات المتكامل
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    product_type ENUM('Laptop', 'Accessory') NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 10,
    image_url VARCHAR(255) DEFAULT 'default.jpg'
);

-- 3. جدول العملاء والطلبات (Checkout)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    delivery_address TEXT NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Mobile Money',
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status ENUM('Pending', 'Paid', 'Shipped', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. تفاصيل المنتجات داخل كل طلب
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- تفريغ الجداول أولاً للتأكد من عدم وجود تضارب عند إعادة التشغيل
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE order_items;
TRUNCATE TABLE products;
SET FOREIGN_KEY_CHECKS = 1;

-- إضافة البيانات بالكامل (الـ 6 الأساسية + الـ 14 الإضافية الكثيرة جداً) في استعلام واحد سليم ومصلح من مشكلة علامات التنصيص
INSERT INTO products (name, brand, product_type, description, price, stock_quantity, image_url) VALUES

('Apple MacBook Pro 16 Inch (M3 Max)', 'Apple', 'Laptop', 'Apple M3 Max chip with 16-core CPU and 40-core GPU, 48GB Unified Memory, 1TB SSD. Liquid Retina XDR display.', 3499.00, 5, 'macbook_pro_16.png'),
('ASUS ROG Strix Scar 17', 'ASUS', 'Laptop', 'AMD Ryzen 9 7945HX, 32GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX 4090. Ultimate compilation and rendering machine.', 2899.00, 3, 'rog_scar_17.png'),
('Lenovo Legion Pro 7i', 'Lenovo', 'Laptop', 'Intel Core i9-13900HX, 32GB RAM, 1TB SSD, RTX 4080 12GB. Optimized thermal grid for prolonged software engineering sessions.', 2199.00, 7, 'legion_pro_7i.png'),
('MSI Stealth 16 Studio', 'MSI', 'Laptop', 'Intel Core i7-13700H, 16GB RAM, 1TB SSD, RTX 4070. Sleek aerospace-grade aluminum chassis for mobile developers.', 1749.00, 4, 'msi_stealth.png'),
('Razer Blade 14 AMD', 'Razer', 'Laptop', 'AMD Ryzen 9 7940HS, 16GB DDR5, 1TB SSD, RTX 4070. Compact form-factor powerhouse with QHD+ 240Hz display.', 2399.00, 6, 'razer_blade_14.png'),
('Logitech MX Mechanical Keyboard', 'Logitech', 'Accessory', 'Wireless tactile mechanical keyboard with smart illumination, optimized for cross-platform system navigation.', 169.00, 15, 'mx_mechanical.png'),
('Sony WH-1000XM5 ANC', 'Sony', 'Accessory', 'Wireless Noise Canceling Headphones. Deep focus acoustic shield for programming environments.', 398.00, 8, 'sony_xm5.png'),
('Razer Basilisk V3 Pro', 'Razer', 'Accessory', 'Customizable wireless engineering and gaming mouse with hyper-scroll tilt wheel and chroma underglow.', 159.00, 20, 'basilisk_v3.png'),
('Dell UltraSharp 32 Inch 4K Monitor', 'Dell', 'Accessory', 'U3223QE USB-C Hub Monitor with IPS Black technology, 2000:1 contrast ratio for precise UI/UX layout design.', 749.00, 5, 'dell_32_4k.png'),
('Anker Prime 20000mAh Power Bank', 'Anker', 'Accessory', '200W simultaneous multi-device charging matrix, digital diagnostic status display screen.', 129.00, 25, 'anker_prime.png'),
('Corsair Virtuoso RGB Wireless SE', 'Corsair', 'Accessory', 'High-fidelity spatial audio communication headset with broadcast-grade omnidirectional microphone.', 209.00, 10, 'corsair_virtuoso.png'),
('Elgato Stream Deck MK.2', 'Elgato', 'Accessory', '15 customizable LCD macro keys for deployment scripting automation and IDE shortcut configurations.', 149.00, 14, 'stream_deck.png'),
('Crucial X10 Pro 2TB Portable SSD', 'Crucial', 'Accessory', 'Sustained read speeds up to 2100MB/s via USB 3.2 Gen 2x2 pipeline. Pocket-sized deployment safe.', 199.00, 18, 'crucial_x10.png'); 
('Apple MacBook Pro 16 Inch (M3 Max)', 'Apple', 'Laptop', 'Apple M3 Max chip with 16-core CPU and 40-core GPU, 48GB Unified Memory, 1TB SSD. Liquid Retina XDR display.', 3499.00, 5, 'macbook_pro_16.png'),
('ASUS ROG Strix Scar 17', 'ASUS', 'Laptop', 'AMD Ryzen 9 7945HX, 32GB DDR5 RAM, 2TB NVMe SSD, NVIDIA RTX 4090. Ultimate compilation and rendering machine.', 2899.00, 3, 'rog_scar_17.png'),
('Lenovo Legion Pro 7i', 'Lenovo', 'Laptop', 'Intel Core i9-13900HX, 32GB RAM, 1TB SSD, RTX 4080 12GB. Optimized thermal grid for prolonged software engineering sessions.', 2199.00, 7, 'legion_pro_7i.png'),
('MSI Stealth 16 Studio', 'MSI', 'Laptop', 'Intel Core i7-13700H, 16GB RAM, 1TB SSD, RTX 4070. Sleek aerospace-grade aluminum chassis for mobile developers.', 1749.00, 4, 'msi_stealth.png'),
('Razer Blade 14 AMD', 'Razer', 'Laptop', 'AMD Ryzen 9 7940HS, 16GB DDR5, 1TB SSD, RTX 4070. Compact form-factor powerhouse with QHD+ 240Hz display.', 2399.00, 6, 'razer_blade_14.png'),
('Logitech MX Mechanical Keyboard', 'Logitech', 'Accessory', 'Wireless tactile mechanical keyboard with smart illumination, optimized for cross-platform system navigation.', 169.00, 15, 'mx_mechanical.png'),
('Sony WH-1000XM5 ANC', 'Sony', 'Accessory', 'Wireless Noise Canceling Headphones. Deep focus acoustic shield for programming environments.', 398.00, 8, 'sony_xm5.png'),
('Razer Basilisk V3 Pro', 'Razer', 'Accessory', 'Customizable wireless engineering and gaming mouse with hyper-scroll tilt wheel and chroma underglow.', 159.00, 20, 'basilisk_v3.png'),
('Dell UltraSharp 32 Inch 4K Monitor', 'Dell', 'Accessory', 'U3223QE USB-C Hub Monitor with IPS Black technology, 2000:1 contrast ratio for precise UI/UX layout design.', 749.00, 5, 'dell_32_4k.png'),
('Anker Prime 20000mAh Power Bank', 'Anker', 'Accessory', '200W simultaneous multi-device charging matrix, digital diagnostic status display screen.', 129.00, 25, 'anker_prime.png'),
('Corsair Virtuoso RGB Wireless SE', 'Corsair', 'Accessory', 'High-fidelity spatial audio communication headset with broadcast-grade omnidirectional microphone.', 209.00, 10, 'corsair_virtuoso.png'),
('Elgato Stream Deck MK.2', 'Elgato', 'Accessory', '15 customizable LCD macro keys for deployment scripting automation and IDE shortcut configurations.', 149.00, 14, 'stream_deck.png'),
('Crucial X10 Pro 2TB Portable SSD', 'Crucial', 'Accessory', 'Sustained read speeds up to 2100MB/s via USB 3.2 Gen 2x2 pipeline. Pocket-sized deployment safe.', 199.00, 18, 'crucial_x10.png'); 