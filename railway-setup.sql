-- Script SQL para Railway
-- Este script se ejecutará automáticamente al conectar la base de datos

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tickets de soporte
CREATE TABLE IF NOT EXISTS tickets (
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
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de solicitudes de acceso
CREATE TABLE IF NOT EXISTS access_requests (
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
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_system_name (system_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de inventario
CREATE TABLE IF NOT EXISTS inventory (
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
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de notificaciones
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto (solo si no existe)
INSERT IGNORE INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ivan - Super Admin', 'admin');

-- Insertar algunos usuarios de ejemplo (solo si no existen)
INSERT IGNORE INTO users (username, email, password, full_name, role) VALUES 
('jsmith', 'john.smith@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Smith', 'user'),
('mgarcia', 'maria.garcia@msxinternational.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'user');

-- Insertar tickets de ejemplo (solo si no existen)
INSERT IGNORE INTO tickets (id, user_id, title, description, category, priority, status) VALUES 
(1, 2, 'Nuevo Ticket - Debug Panel', 'Nuevo ticket creado para pruebas del sistema', 'software', 'medium', 'open'),
(2, 2, 'No funciona Teams / Email y GTAC', 'El sistema de Teams no está funcionando correctamente y necesita revisión', 'software', 'medium', 'in_progress'),
(3, 3, 'Test - Actualizaciones Recientes', 'Ticket de prueba para validar el sistema de actualizaciones', 'other', 'high', 'open');

-- Insertar elementos de inventario de ejemplo (solo si no existen)
INSERT IGNORE INTO inventory (id, name, category, description, status, location) VALUES 
(1, 'Laptop Dell Latitude 5520', 'hardware', 'Laptop para desarrollo y trabajo remoto', 'available', 'Almacén TI'),
(2, 'Microsoft Office 365', 'software', 'Suite de oficina empresarial', 'in_use', 'Licencias'),
(3, 'Monitor Samsung 24"', 'hardware', 'Monitor secundario para workstations', 'available', 'Almacén TI');

-- Crear vistas útiles para reportes
CREATE OR REPLACE VIEW ticket_summary AS
SELECT 
    status,
    priority,
    category,
    COUNT(*) as count,
    AVG(TIMESTAMPDIFF(HOUR, created_at, COALESCE(updated_at, NOW()))) as avg_hours
FROM tickets 
GROUP BY status, priority, category;

CREATE OR REPLACE VIEW user_activity AS
SELECT 
    u.id,
    u.full_name,
    u.email,
    COUNT(DISTINCT t.id) as total_tickets,
    COUNT(DISTINCT ar.id) as total_requests,
    MAX(GREATEST(COALESCE(t.updated_at, '1970-01-01'), COALESCE(ar.updated_at, '1970-01-01'))) as last_activity
FROM users u
LEFT JOIN tickets t ON u.id = t.user_id
LEFT JOIN access_requests ar ON u.id = ar.user_id
WHERE u.active = 1
GROUP BY u.id, u.full_name, u.email; 