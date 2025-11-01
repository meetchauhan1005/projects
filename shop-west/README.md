# Shop West - E-commerce Website

A complete PHP-based e-commerce website similar to Amazon, built with XAMPP and MySQL.

## Features

- **User Authentication**: Registration, login, logout
- **Product Catalog**: Browse products by category, search, and filter
- **Shopping Cart**: Add/remove items, update quantities
- **Checkout Process**: Place orders with shipping information
- **User Profile**: Manage profile and view order history
- **Responsive Design**: Mobile-friendly interface
- **Admin Features**: Product and category management

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Font Awesome
- **Backend**: PHP 8.x
- **Database**: MySQL
- **Server**: Apache (XAMPP)

## Installation Instructions

### Prerequisites
- XAMPP installed on your system
- Web browser

### Setup Steps

1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the database structure:
     - Click "Import" tab
     - Choose file: `database.sql`
     - Click "Go" to execute

3. **File Placement**
   - All files are already in the correct location: `c:\xampp\htdocs\online shoping\`

4. **Access the Website**
   - Open your web browser
   - Navigate to: `http://localhost/online shoping/`

## Default Configuration

- **Database Host**: localhost
- **Database Name**: shop_west
- **Database User**: root
- **Database Password**: (empty)

## Website Structure

```
online shoping/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── index.php (Homepage)
├── products.php (Product listing)
├── product.php (Product details)
├── cart.php (Shopping cart)
├── checkout.php (Checkout process)
├── login.php (User login)
├── register.php (User registration)
├── profile.php (User profile)
├── order_success.php (Order confirmation)
├── order_details.php (Order details)
├── add_to_cart.php (Cart API)
├── get_cart_count.php (Cart count API)
├── logout.php (Logout)
└── database.sql (Database structure)
```

## Key Features

### For Customers
- Browse products by categories
- Search and filter products
- Add items to shopping cart
- Secure checkout process
- Order tracking and history
- User profile management

### For Administrators
- Product management
- Category management
- Order management
- User management

## Sample Data

The database includes sample data:
- 5 product categories
- 8 sample products
- Product ratings and reviews

## Customization

### Adding New Products
1. Access phpMyAdmin
2. Navigate to `shop_west` database
3. Insert new records in `products` table

### Modifying Styles
- Edit `assets/css/style.css` for styling changes
- Colors, fonts, and layouts can be customized

### Adding Features
- Payment gateway integration
- Email notifications
- Advanced search filters
- Product reviews system

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention with prepared statements
- Session management for user authentication
- Input validation and sanitization

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL service is running in XAMPP
   - Check database credentials in `config/database.php`

2. **Page Not Found**
   - Verify Apache service is running
   - Check file paths and permissions

3. **Images Not Loading**
   - Placeholder images are used by default
   - Replace with actual product images in `assets/images/`

## Support

For issues or questions:
1. Check XAMPP error logs
2. Verify database connection
3. Ensure all files are in correct locations

## License

This project is for educational purposes. Feel free to modify and use as needed.

---

**Shop West** - Your Online Shopping Destination