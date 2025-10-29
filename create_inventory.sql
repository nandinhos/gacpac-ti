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
);
