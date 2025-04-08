-- Create database
CREATE DATABASE IF NOT EXISTS dealer_motor;
USE dealer_motor;

-- Create motors table
CREATE TABLE motors (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    merk VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    tahun INT(4) NOT NULL,
    warna VARCHAR(50) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    stok INT(11) DEFAULT 0,
    deskripsi TEXT,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create spareparts table
CREATE TABLE spareparts (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(50) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    stok INT(11) DEFAULT 0,
    deskripsi TEXT,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create customers table
CREATE TABLE customers (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT,
    identity_type ENUM('ktp', 'sim', 'passport') DEFAULT 'ktp',
    identity_number VARCHAR(50) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create sales table
CREATE TABLE sales (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT(11) UNSIGNED NOT NULL,
    total_amount DECIMAL(15,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create sales_items table
CREATE TABLE sales_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id INT(11) UNSIGNED NOT NULL,
    item_type ENUM('motor', 'sparepart') NOT NULL,
    item_id INT(11) UNSIGNED NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    created_at DATETIME,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create services table
CREATE TABLE services (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT(11) UNSIGNED NOT NULL,
    vehicle_brand VARCHAR(100) NOT NULL,
    vehicle_model VARCHAR(100) NOT NULL,
    vehicle_year INT(4) NOT NULL,
    vehicle_number VARCHAR(20) NOT NULL,
    complaints TEXT NOT NULL,
    diagnosis TEXT,
    service_cost DECIMAL(15,2) DEFAULT 0,
    parts_cost DECIMAL(15,2) DEFAULT 0,
    total_cost DECIMAL(15,2) DEFAULT 0,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    mechanic_notes TEXT,
    completed_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create service_items table
CREATE TABLE service_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT(11) UNSIGNED NOT NULL,
    sparepart_id INT(11) UNSIGNED NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    created_at DATETIME,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (sparepart_id) REFERENCES spareparts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create categories table
CREATE TABLE categories (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add some default categories
INSERT INTO categories (name, description, created_at) VALUES
('Mesin', 'Sparepart bagian mesin', NOW()),
('Body', 'Sparepart body motor', NOW()),
('Kelistrikan', 'Sparepart sistem kelistrikan', NOW()),
('Aksesoris', 'Aksesoris tambahan', NOW());

-- Insert dummy data for motors
INSERT INTO motors (merk, model, tahun, warna, harga, stok, deskripsi, created_at, updated_at) VALUES
('Honda', 'CBR 150R', 2023, 'Merah', 35000000.00, 5, 'Motor sport dengan performa tinggi', NOW(), NOW()),
('Yamaha', 'NMAX', 2023, 'Hitam', 32000000.00, 8, 'Skutik premium dengan fitur lengkap', NOW(), NOW()),
('Kawasaki', 'Ninja 250', 2023, 'Hijau', 75000000.00, 3, 'Motor sport dengan mesin 250cc', NOW(), NOW()),
('Honda', 'Beat', 2023, 'Putih', 18000000.00, 15, 'Motor matik ekonomis', NOW(), NOW()),
('Yamaha', 'R15', 2023, 'Biru', 39000000.00, 4, 'Motor sport dengan teknologi VVA', NOW(), NOW());

-- Insert dummy data for spareparts
INSERT INTO spareparts (kode, nama, kategori, harga, stok, deskripsi, created_at, updated_at) VALUES
('OLI-001', 'Oli Mesin Shell', 'Mesin', 85000.00, 50, 'Oli mesin kualitas tinggi', NOW(), NOW()),
('FLT-001', 'Filter Udara', 'Mesin', 45000.00, 30, 'Filter udara original', NOW(), NOW()),
('BRK-001', 'Kampas Rem Depan', 'Mesin', 75000.00, 25, 'Kampas rem berkualitas', NOW(), NOW()),
('LMP-001', 'Lampu LED Depan', 'Kelistrikan', 125000.00, 15, 'Lampu LED bright white', NOW(), NOW()),
('BDY-001', 'Cover Body Samping', 'Body', 250000.00, 10, 'Cover body original', NOW(), NOW()),
('ACC-001', 'Phone Holder', 'Aksesoris', 85000.00, 20, 'Holder HP anti getar', NOW(), NOW());

-- Insert dummy data for customers
INSERT INTO customers (name, phone, email, address, identity_type, identity_number, created_at, updated_at) VALUES
('Budi Santoso', '081234567890', 'budi@email.com', 'Jl. Merdeka No. 123, Jakarta', 'ktp', '3171234567890001', NOW(), NOW()),
('Ani Wijaya', '082345678901', 'ani@email.com', 'Jl. Sudirman No. 45, Jakarta', 'ktp', '3171234567890002', NOW(), NOW()),
('Citra Dewi', '083456789012', 'citra@email.com', 'Jl. Gatot Subroto No. 67, Jakarta', 'sim', '92345678901234', NOW(), NOW()),
('David Pratama', '084567890123', 'david@email.com', 'Jl. Thamrin No. 89, Jakarta', 'ktp', '3171234567890003', NOW(), NOW()),
('Eva Susanti', '085678901234', 'eva@email.com', 'Jl. Asia Afrika No. 12, Jakarta', 'passport', 'A12345678', NOW(), NOW());

-- Insert dummy data for sales
INSERT INTO sales (invoice_number, customer_id, total_amount, payment_method, status, notes, created_at, updated_at) VALUES
('INV20230401001', 1, 35000000.00, 'transfer', 'completed', 'Pembelian CBR 150R', NOW(), NOW()),
('INV20230402001', 2, 18000000.00, 'cash', 'completed', 'Pembelian Beat', NOW(), NOW()),
('INV20230403001', 3, 32000000.00, 'credit_card', 'completed', 'Pembelian NMAX', NOW(), NOW()),
('INV20230404001', 4, 75000000.00, 'transfer', 'pending', 'Pembelian Ninja 250', NOW(), NOW()),
('INV20230405001', 5, 39000000.00, 'cash', 'completed', 'Pembelian R15', NOW(), NOW());

-- Insert dummy data for sales_items
INSERT INTO sales_items (sale_id, item_type, item_id, quantity, price, subtotal, created_at) VALUES
(1, 'motor', 1, 1, 35000000.00, 35000000.00, NOW()),
(2, 'motor', 4, 1, 18000000.00, 18000000.00, NOW()),
(3, 'motor', 2, 1, 32000000.00, 32000000.00, NOW()),
(4, 'motor', 3, 1, 75000000.00, 75000000.00, NOW()),
(5, 'motor', 5, 1, 39000000.00, 39000000.00, NOW());

-- Insert dummy data for services
INSERT INTO services (service_number, customer_id, vehicle_brand, vehicle_model, vehicle_year, vehicle_number, complaints, diagnosis, service_cost, parts_cost, total_cost, status, mechanic_notes, completed_at, created_at, updated_at) VALUES
('SRV20230401001', 1, 'Honda', 'CBR 150R', 2023, 'B1234ABC', 'Bunyi kasar pada mesin', 'Rantai perlu disetel dan pelumas', 50000.00, 85000.00, 135000.00, 'completed', 'Sudah disetel dan dilumasi', NOW(), NOW(), NOW()),
('SRV20230402001', 2, 'Honda', 'Beat', 2023, 'B5678DEF', 'Rem kurang pakem', 'Kampas rem aus', 30000.00, 75000.00, 105000.00, 'completed', 'Kampas rem sudah diganti', NOW(), NOW(), NOW()),
('SRV20230403001', 3, 'Yamaha', 'NMAX', 2023, 'B9012GHI', 'Service rutin', 'Perlu ganti oli', 50000.00, 85000.00, 135000.00, 'in_progress', 'Proses penggantian oli', NULL, NOW(), NOW()),
('SRV20230404001', 4, 'Kawasaki', 'Ninja 250', 2023, 'B3456JKL', 'Lampu depan redup', 'Lampu perlu diganti', 25000.00, 125000.00, 150000.00, 'pending', NULL, NULL, NOW(), NOW()),
('SRV20230405001', 5, 'Yamaha', 'R15', 2023, 'B7890MNO', 'Filter udara kotor', 'Filter udara perlu diganti', 25000.00, 45000.00, 70000.00, 'completed', 'Filter udara sudah diganti', NOW(), NOW(), NOW());

-- Insert dummy data for service_items
INSERT INTO service_items (service_id, sparepart_id, quantity, price, subtotal, created_at) VALUES
(1, 1, 1, 85000.00, 85000.00, NOW()),
(2, 3, 1, 75000.00, 75000.00, NOW()),
(3, 1, 1, 85000.00, 85000.00, NOW()),
(4, 4, 1, 125000.00, 125000.00, NOW()),
(5, 2, 1, 45000.00, 45000.00, NOW());

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 
