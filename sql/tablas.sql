-- Creamos la tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(6,2) CHECK (precio >= 0 AND precio <= 9999.99),
    disponible ENUM('SI', 'NO') DEFAULT 'SI',
    imagen varchar(200)
);