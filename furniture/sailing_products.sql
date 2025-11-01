-- Add new categories for sailing-themed furniture
INSERT INTO categories (name, description, image) VALUES 
('Nautical Seating', 'Luxury sailing-themed sofas and chairs for your coastal home', 'sofa.jpg'),
('Maritime Tables', 'Elegant nautical-inspired tables and dining sets', 'table.jpg'),
('Seafarer''s Bedroom', 'Beautiful sailing-themed bedroom furniture', 'bedroom.jpg');

-- Get the IDs of the newly inserted categories
SET @nautical_seating_id = LAST_INSERT_ID();
SET @maritime_tables_id = @nautical_seating_id + 1;
SET @seafarer_bedroom_id = @maritime_tables_id + 1;

-- Insert sailing-themed sofas and chairs
INSERT INTO products (name, description, price, discount_price, category_id, stock_quantity, image, featured, status) VALUES 
('Captain''s Recliner', 'Luxurious leather recliner with brass studs and nautical details', 1499.99, 1299.99, @nautical_seating_id, 10, 'chair1.jpg', TRUE, 'active'),
('Maritime Deck Chair Set', 'Set of 2 teak deck chairs with navy blue cushions', 899.99, NULL, @nautical_seating_id, 15, 'sofa1.jpg', TRUE, 'active'),
('Sailor''s Lounge Sofa', 'Three-seater sofa with nautical stripe upholstery', 1899.99, 1699.99, @nautical_seating_id, 8, 'sofa1.jpg', TRUE, 'active'),
('Ship''s Wheel Accent Chair', 'Unique accent chair with ship wheel design on the back', 799.99, 699.99, @nautical_seating_id, 12, 'chair1.jpg', FALSE, 'active');

-- Insert sailing-themed tables
INSERT INTO products (name, description, price, discount_price, category_id, stock_quantity, image, featured, status) VALUES 
('Ship Wheel Coffee Table', 'Glass-top coffee table with authentic ship wheel base', 1299.99, 1099.99, @maritime_tables_id, 6, 'coffee_table.jpg', TRUE, 'active'),
('Nautical Dining Set', '6-seater teak dining set with rope accents', 2499.99, 2299.99, @maritime_tables_id, 4, 'table1.jpg', TRUE, 'active'),
('Compass Rose Side Table', 'Round side table with inlaid compass rose design', 699.99, NULL, @maritime_tables_id, 10, 'coffee_table.jpg', FALSE, 'active'),
('Captain''s Chart Table', 'Functional writing desk inspired by nautical chart tables', 999.99, 899.99, @maritime_tables_id, 8, 'table1.jpg', TRUE, 'active');

-- Insert sailing-themed bedroom furniture
INSERT INTO products (name, description, price, discount_price, category_id, stock_quantity, image, featured, status) VALUES 
('Ship Cabin King Bed', 'King-size bed with built-in storage, inspired by luxury yacht cabins', 2999.99, 2699.99, @seafarer_bedroom_id, 5, 'bed1.jpg', TRUE, 'active'),
('Nautical Dresser', 'Six-drawer dresser with rope handles and coastal finish', 1499.99, 1299.99, @seafarer_bedroom_id, 8, 'storage.jpg', TRUE, 'active'),
('Maritime Nightstand Set', 'Set of 2 nightstands with compass design and hidden storage', 899.99, 799.99, @seafarer_bedroom_id, 12, 'storage.jpg', FALSE, 'active'),
('Captain''s Wardrobe', 'Spacious wardrobe with ship lap design and brass hardware', 1999.99, 1799.99, @seafarer_bedroom_id, 6, 'storage.jpg', TRUE, 'active');