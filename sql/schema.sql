CREATE TABLE orders (
    order_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    order_date DATETIME,
    delivery_location VARCHAR(100),
    order_value DECIMAL(10,2),
    status ENUM('NEW','ASSIGNED','UNASSIGNED') DEFAULT 'UNASSIGNED'
);

CREATE TABLE couriers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    daily_capacity INT,
    current_assigned_count INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE courier_locations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    courier_id BIGINT,
    location VARCHAR(100),
    FOREIGN KEY (courier_id) REFERENCES couriers(id)
);

CREATE TABLE order_assignments (
    assignment_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNIQUE,
    courier_id BIGINT,
    assignment_date DATETIME,
    status ENUM('SUCCESS','FAILED') DEFAULT 'SUCCESS',
    error_message TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (courier_id) REFERENCES couriers(id)
);
