# Furniture E-commerce Website

A professional furniture e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### Frontend Features
- **Responsive Design**: Mobile-friendly layout that works on all devices
- **Product Catalog**: Browse products with filtering and sorting options
- **Product Search**: Search products by name, description, or category
- **Shopping Cart**: Add/remove products, update quantities
- **User Authentication**: Registration, login, and user profiles
- **Product Reviews**: Rate and review products
- **Category Browsing**: Organized product categories

### Backend Features
- **Admin Dashboard**: Complete admin panel for managing the store
- **Product Management**: Add, edit, delete products
- **Category Management**: Manage product categories
- **Order Management**: Track and manage customer orders
- **User Management**: Manage customer accounts
- **Inventory Tracking**: Stock quantity management

### Security Features
- **Password Hashing**: Secure password storage using PHP password_hash()
- **SQL Injection Protection**: Prepared statements for all database queries
- **XSS Protection**: Input sanitization and output escaping
- **Session Management**: Secure user session handling

## Installation

### Prerequisites
- XAMPP (Apache, MySQL, PHP)
- Web browser

### Setup Instructions

1. **Start XAMPP Services**
   - Start Apache and MySQL from XAMPP Control Panel

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file to create the database and tables
   - Or run the SQL commands from `database.sql` manually

3. **File Permissions**
   - Ensure the `assets/images/products/` directory is writable for product image uploads

4. **Access the Website**
   - Frontend: http://localhost/furniture/
   - Admin Panel: http://localhost/furniture/admin/dashboard.php
   - Default Admin Login: admin@furniture.com / password

## File Structure

```
furniture/
├── admin/                  # Admin panel files
│   └── dashboard.php      # Admin dashboard
├── ajax/                  # AJAX endpoints
│   ├── add_to_cart.php   # Add to cart functionality
│   └── get_cart_count.php # Get cart count
├── assets/               # Static assets
│   ├── css/
│   │   └── style.css     # Main stylesheet
│   ├── js/
│   │   └── main.js       # JavaScript functionality
│   └── images/           # Image files
│       ├── products/     # Product images
│       └── placeholder.jpg # Placeholder image
├── config/               # Configuration files
│   └── database.php      # Database connection
├── includes/             # Reusable components
│   ├── header.php        # Header template
│   ├── footer.php        # Footer template
│   └── functions.php     # Utility functions
├── user/                 # User account pages
├── index.php             # Homepage
├── products.php          # Product listing
├── product.php           # Product details
├── cart.php              # Shopping cart
├── login.php             # User login
├── register.php          # User registration
├── search.php            # Search results
├── categories.php        # Category listing
├── logout.php            # Logout functionality
└── database.sql          # Database schema
```

## Database Schema

### Tables
- **users**: User accounts and authentication
- **categories**: Product categories
- **products**: Product information and inventory
- **cart**: Shopping cart items
- **orders**: Customer orders
- **order_items**: Order line items
- **reviews**: Product reviews and ratings

## Usage

### For Customers
1. Browse products by category or search
2. View detailed product information and reviews
3. Register an account or login
4. Add products to cart
5. Review cart and proceed to checkout
6. Leave product reviews

### For Administrators
1. Login to admin panel
2. Manage products (add, edit, delete)
3. Manage categories
4. View and process orders
5. Manage user accounts
6. Monitor reviews

## Customization

### Adding New Products
1. Login to admin panel
2. Navigate to Products section
3. Click "Add New Product"
4. Fill in product details and upload image
5. Set category, price, and stock quantity

### Styling
- Modify `assets/css/style.css` for design changes
- Colors, fonts, and layout can be customized
- Responsive breakpoints are included

### Functionality
- Add new features by creating PHP files
- Use existing functions in `includes/functions.php`
- AJAX endpoints can be added to `ajax/` directory

## Security Considerations

- Change default admin credentials
- Use HTTPS in production
- Regular database backups
- Keep PHP and MySQL updated
- Validate and sanitize all user inputs

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## License

This project is open source and available under the MIT License.