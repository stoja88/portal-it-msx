-- Base de datos para Portal IT
CREATE DATABASE IF NOT EXISTS portal_it;
USE portal_it;

-- Tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de tickets de soporte
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('hardware', 'software', 'network', 'access', 'other') DEFAULT 'other',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    assigned_to INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de solicitudes de acceso
CREATE TABLE access_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    system_name VARCHAR(100) NOT NULL,
    access_type ENUM('read', 'write', 'admin') DEFAULT 'read',
    justification TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de inventario
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('hardware', 'software', 'license') NOT NULL,
    description TEXT,
    serial_number VARCHAR(100),
    location VARCHAR(100),
    status ENUM('available', 'in_use', 'maintenance', 'retired') DEFAULT 'available',
    assigned_to INT NULL,
    purchase_date DATE,
    warranty_expires DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de notificaciones
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertar usuario administrador por defecto
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ivan - Super Admin', 'admin');

-- Insertar algunos datos de ejemplo
INSERT INTO users (username, email, password, full_name, role) VALUES 
('jsmith', 'john.smith@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Smith', 'user'),
('mgarcia', 'maria.garcia@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'user');

-- Insertar tickets de ejemplo
INSERT INTO tickets (user_id, title, description, category, priority, status) VALUES 
(2, 'Nuevo Ticket - Debug Panel', 'Nuevo ticket creado', 'software', 'medium', 'open'),
(2, 'No funciona Teams / Email y GTAC', 'El sistema de Teams no está funcionando correctamente', 'software', 'medium', 'in_progress'),
(3, 'Test - Actualizaciones Recientes', 'Nuevo ticket creado', 'other', 'high', 'open');

-- Insertar elementos de inventario de ejemplo
INSERT INTO inventory (name, category, description, status, location) VALUES 
('Laptop Dell Latitude 5520', 'hardware', 'Laptop para desarrollo', 'available', 'Almacén TI'),
('Microsoft Office 365', 'software', 'Suite de oficina', 'in_use', 'Licencias'),
('Monitor Samsung 24"', 'hardware', 'Monitor secundario', 'available', 'Almacén TI'); 