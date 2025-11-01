# Mahadev Electronic - Professional Electronics Store

A complete PHP-based electronics store website with admin panel, built for XAMPP environment.

## Features

### Frontend
- **Responsive Design**: Mobile-friendly Bootstrap 5 interface
- **Product Catalog**: Categorized product display with filtering
- **Contact System**: Contact form with database storage
- **Professional UI**: Modern design with Font Awesome icons

### Admin Panel
- **Secure Login**: Password-protected admin access
- **Dashboard**: Statistics and overview
- **Product Management**: Add, view, and delete products
- **Message Management**: View and respond to customer inquiries
- **Category Management**: Organize products by categories

## Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser

### Setup Steps

1. **Start XAMPP Services**
   - Start Apache and MySQL from XAMPP Control Panel

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file to create the database structure
   - Or run the SQL commands manually

3. **File Placement**
   - Copy all files to `C:\xampp\htdocs\electronic\`

4. **Access the Website**
   - Frontend: http://localhost/electronic/
   - Admin Panel: http://localhost/electronic/admin/login.php

## Default Admin Credentials
- **Username**: admin
- **Password**: admin123

## File Structure
```
electronic/
├── config/
│   └── database.php          # Database connection
├── admin/
│   ├── login.php            # Admin login
│   ├── dashboard.php        # Admin dashboard
│   ├── products.php         # Product management
│   ├── messages.php         # Message management
│   └── logout.php           # Logout functionality
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles
│   ├── images/              # Product images
│   └── js/                  # JavaScript files
├── index.php                # Homepage
├── products.php             # Products page
├── contact.php              # Contact page
├── database.sql             # Database structure
└── README.md               # This file
```

## Database Tables
- **admin_users**: Admin login credentials
- **categories**: Product categories
- **products**: Product information
- **contact_messages**: Customer inquiries

## Customization

### Adding Product Images
1. Upload images to `assets/images/` folder
2. Use the filename when adding products through admin panel

### Styling
- Modify `assets/css/style.css` for custom styling
- Bootstrap 5 classes available throughout

### Database Configuration
- Update `config/database.php` for different database settings

## Security Features
- Password hashing for admin accounts
- SQL injection protection with PDO prepared statements
- Session-based admin authentication
- Input sanitization and validation

## Support
For issues or customization requests, contact the development team.

---
**Mahadev Electronic** - Your trusted partner for premium electronics