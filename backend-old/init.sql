-- SGAITI-UM Database Schema
-- Sistema de Gestão de Ativos de TI - Unidade Militar

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS sgaiti_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sgaiti_db;

-- Sectors table
CREATE TABLE IF NOT EXISTS sectors (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table (Military Personnel)
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    `rank` VARCHAR(100) NOT NULL,
    military_id VARCHAR(50) UNIQUE NOT NULL,
    sector_id VARCHAR(36),
    email VARCHAR(255),
    phone VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL,
    INDEX idx_military_id (military_id),
    INDEX idx_sector_id (sector_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assets table
CREATE TABLE IF NOT EXISTS assets (
    id VARCHAR(36) PRIMARY KEY,
    qr_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    category ENUM('Computação', 'Periféricos', 'Energia', 'Comunicações', 'Outros Ativos de TI') NOT NULL,
    subcategory VARCHAR(100),
    description TEXT,
    serial_number VARCHAR(255),
    patrimony_id VARCHAR(100),
    manufacturer VARCHAR(255),
    model VARCHAR(255),
    acquisition_date DATE,
    warranty_expiry DATE,
    purchase_price DECIMAL(10,2),
    status ENUM('Em Uso', 'Disponível', 'Manutenção', 'Baixado') DEFAULT 'Disponível',
    condition_rating INT CHECK (condition_rating BETWEEN 1 AND 5),
    sector_id VARCHAR(36),
    location VARCHAR(255),
    custodian_user_id VARCHAR(36),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Novas colunas para inventário geral
    conta VARCHAR(255),
    categoria_inventario VARCHAR(255),
    bmp VARCHAR(100),
    componente TEXT,
    situacao VARCHAR(100),
    qtd INT DEFAULT 1,
    valor_atualizado DECIMAL(10, 2),
    deprec_acumulada DECIMAL(10, 2),
    valor_liquido DECIMAL(10, 2),

    FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL,
    FOREIGN KEY (custodian_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_qr_code (qr_code),
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_sector_id (sector_id),
    INDEX idx_custodian_user_id (custodian_user_id),
    INDEX idx_patrimony_id (patrimony_id),
    INDEX idx_bmp (bmp) -- Adicionando índice para o novo campo bmp
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Asset Photos table
CREATE TABLE IF NOT EXISTS asset_photos (
    id VARCHAR(36) PRIMARY KEY,
    asset_id VARCHAR(36) NOT NULL,
    photo_data LONGBLOB NOT NULL,
    mime_type VARCHAR(50) NOT NULL,
    caption VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    INDEX idx_asset_id (asset_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Maintenance History table
CREATE TABLE IF NOT EXISTS maintenance_history (
    id VARCHAR(36) PRIMARY KEY,
    asset_id VARCHAR(36) NOT NULL,
    date DATE NOT NULL,
    description TEXT NOT NULL,
    performed_by VARCHAR(255),
    cost DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    INDEX idx_asset_id (asset_id),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Custody Logs (Cautelas) table
CREATE TABLE IF NOT EXISTS custody_logs (
    id VARCHAR(36) PRIMARY KEY,
    cautela_number VARCHAR(50) UNIQUE NOT NULL,
    user_id VARCHAR(36) NOT NULL,
    checkout_date DATE NOT NULL,
    checkin_date DATE,
    term_url VARCHAR(500),
    signed_term_url VARCHAR(500),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_cautela_number (cautela_number),
    INDEX idx_user_id (user_id),
    INDEX idx_checkout_date (checkout_date),
    INDEX idx_checkin_date (checkin_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Custody Assets (many-to-many relationship)
CREATE TABLE IF NOT EXISTS custody_assets (
    id VARCHAR(36) PRIMARY KEY,
    custody_log_id VARCHAR(36) NOT NULL,
    asset_id VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (custody_log_id) REFERENCES custody_logs(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    UNIQUE KEY unique_custody_asset (custody_log_id, asset_id),
    INDEX idx_custody_log_id (custody_log_id),
    INDEX idx_asset_id (asset_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory Records table
CREATE TABLE IF NOT EXISTS inventory_records (
    id VARCHAR(36) PRIMARY KEY,
    commission_number VARCHAR(100) UNIQUE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    sector_id VARCHAR(36),
    responsible_user_id VARCHAR(36),
    status ENUM('Em Andamento', 'Concluído', 'Reaberto') DEFAULT 'Em Andamento',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL,
    FOREIGN KEY (responsible_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_commission_number (commission_number),
    INDEX idx_status (status),
    INDEX idx_sector_id (sector_id),
    INDEX idx_start_date (start_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory Found Items table
CREATE TABLE IF NOT EXISTS inventory_found_items (
    id VARCHAR(36) PRIMARY KEY,
    inventory_id VARCHAR(36) NOT NULL,
    asset_id VARCHAR(36) NOT NULL,
    found_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observation TEXT,
    FOREIGN KEY (inventory_id) REFERENCES inventory_records(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inventory_asset (inventory_id, asset_id),
    INDEX idx_inventory_id (inventory_id),
    INDEX idx_asset_id (asset_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory Pending Items table
CREATE TABLE IF NOT EXISTS inventory_pending_items (
    id VARCHAR(36) PRIMARY KEY,
    inventory_id VARCHAR(36) NOT NULL,
    asset_id VARCHAR(36) NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventory_records(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inventory_pending (inventory_id, asset_id),
    INDEX idx_inventory_id (inventory_id),
    INDEX idx_asset_id (asset_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory Uncatalogued Items table
CREATE TABLE IF NOT EXISTS inventory_uncatalogued_items (
    id VARCHAR(36) PRIMARY KEY,
    inventory_id VARCHAR(36) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255),
    found_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventory_records(id) ON DELETE CASCADE,
    INDEX idx_inventory_id (inventory_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory Reopen History table
CREATE TABLE IF NOT EXISTS inventory_reopen_history (
    id VARCHAR(36) PRIMARY KEY,
    inventory_id VARCHAR(36) NOT NULL,
    reopened_by_user_id VARCHAR(36) NOT NULL,
    reopened_at TIMESTAMP NOT NULL,
    justification TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventory_records(id) ON DELETE CASCADE,
    FOREIGN KEY (reopened_by_user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_inventory_id (inventory_id),
    INDEX idx_reopened_by (reopened_by_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for better performance
CREATE INDEX idx_assets_updated_at ON assets(updated_at);
CREATE INDEX idx_custody_logs_updated_at ON custody_logs(updated_at);
CREATE INDEX idx_inventory_records_updated_at ON inventory_records(updated_at);

-- Create full-text search indexes
ALTER TABLE assets ADD FULLTEXT INDEX ft_assets_search (name, description, serial_number, patrimony_id, conta, categoria_inventario, bmp, componente);
ALTER TABLE users ADD FULLTEXT INDEX ft_users_search (name, military_id);
ALTER TABLE sectors ADD FULLTEXT INDEX ft_sectors_search (name, description);
