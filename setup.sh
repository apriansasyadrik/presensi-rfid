#!/bin/bash

# Sistem Presensi RFID - Quick Setup Script
# This script helps set up the application quickly

echo "================================================"
echo "  SISTEM PRESENSI RFID - QUICK SETUP"
echo "================================================"
echo ""

# Check if MySQL is running
echo "Checking MySQL service..."
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL not found. Please install MySQL first."
    exit 1
fi
echo "✅ MySQL found"
echo ""

# Set permissions
echo "Setting directory permissions..."
chmod -R 777 application/cache
chmod -R 777 application/logs
chmod -R 777 uploads
echo "✅ Permissions set"
echo ""

# Database setup
echo "================================================"
echo "  DATABASE SETUP"
echo "================================================"
read -p "Enter MySQL username [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "Enter MySQL password (press Enter if none): " DB_PASS
echo ""

read -p "Enter database name [presensi_rfid]: " DB_NAME
DB_NAME=${DB_NAME:-presensi_rfid}

echo ""
echo "Creating database and importing schema..."

# Create database
mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# Import schema
mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database_schema.sql

if [ $? -eq 0 ]; then
    echo "✅ Database created and schema imported successfully"
else
    echo "❌ Failed to import database schema"
    exit 1
fi

# Update database config
echo ""
echo "Updating database configuration..."
sed -i "s/'username' => 'root',/'username' => '$DB_USER',/" application/config/database.php
sed -i "s/'password' => '',/'password' => '$DB_PASS',/" application/config/database.php
sed -i "s/'database' => 'presensi_rfid',/'database' => '$DB_NAME',/" application/config/database.php

echo "✅ Database configuration updated"
echo ""

# Display success message
echo "================================================"
echo "  SETUP COMPLETE!"
echo "================================================"
echo ""
echo "🎉 Application is ready to use!"
echo ""
echo "📌 Access the application:"
echo "   Main: http://localhost/presensi-rfid"
echo "   RFID Scanner: http://localhost/presensi-rfid/rfid-scanner"
echo ""
echo "🔐 Default Admin Login:"
echo "   Username: admin"
echo "   Password: admin123"
echo ""
echo "⚠️  IMPORTANT:"
echo "   1. Change the default admin password immediately"
echo "   2. Update the encryption key in application/config/config.php"
echo "   3. Configure your base URL if needed"
echo ""
echo "📖 Read README.md for more information"
echo "📊 Check IMPLEMENTATION_STATUS.md for development progress"
echo ""
echo "================================================"
