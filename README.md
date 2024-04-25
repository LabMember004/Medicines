CREATE TABLE `medicine`(
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP,
    `name` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `price` INT NOT NULL,
    `status` TEXT default 'available'
);

CREATE TABLE `worker`(
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `address` TEXT NOT NULL
);

CREATE TABLE `customer`(
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL
);

CREATE TABLE `order`(
    `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    `medicine_id` INT NOT NULL, 
    `customer_id` INT NOT NULL, 
    FOREIGN KEY (`medicine_id`) REFERENCES `medicine`(`id`) ON DELETE CASCADE, 
    FOREIGN KEY (`customer_id`) REFERENCES `customer`(`id`) ON DELETE CASCADE, `quantity` INT NOT NULL default 1 
);
