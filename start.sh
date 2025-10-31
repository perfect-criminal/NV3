#!/bin/bash

echo "================================"
echo "  NV3 Vegan Database Platform"
echo "================================"
echo ""

# Check if database is set up
echo "Checking database connection..."
php yii migrate --interactive=0 2>&1 | grep -q "No new migrations found" && DB_OK=true || DB_OK=false

if [ "$DB_OK" = false ]; then
    echo ""
    echo "⚠ Database not set up!"
    echo ""
    echo "Please follow these steps:"
    echo ""
    echo "1. Create MySQL database:"
    echo "   mysql -u root -p -e \"CREATE DATABASE nv3_vegan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
    echo ""
    echo "2. Run migrations:"
    echo "   php yii migrate"
    echo ""
    echo "3. Create admin user:"
    echo "   php yii admin/setup"
    echo ""
    echo "4. Run this script again: ./start.sh"
    echo ""
    exit 1
fi

echo "✓ Database OK"

# Check if admin user exists
php yii admin/list-users 2>&1 | grep -q "admin" && ADMIN_OK=true || ADMIN_OK=false

if [ "$ADMIN_OK" = false ]; then
    echo ""
    echo "⚠ No admin user found!"
    echo ""
    echo "Creating default admin user..."
    php yii admin/setup
    echo ""
fi

echo "✓ Admin user exists"
echo ""
echo "================================"
echo "  Starting Servers"
echo "================================"
echo ""
echo "Backend (Admin): http://localhost:8080"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "Frontend (Public): http://localhost:8081"
echo ""
echo "Press Ctrl+C to stop both servers"
echo ""

# Kill any existing servers on these ports
lsof -ti:8080 | xargs kill -9 2>/dev/null
lsof -ti:8081 | xargs kill -9 2>/dev/null

# Start backend in background
php yii serve --docroot=backend/web --port=8080 > /dev/null 2>&1 &
BACKEND_PID=$!

# Start frontend in background
php yii serve --docroot=frontend/web --port=8081 > /dev/null 2>&1 &
FRONTEND_PID=$!

# Wait a moment for servers to start
sleep 2

# Check if servers are running
if lsof -Pi :8080 -sTCP:LISTEN -t >/dev/null ; then
    echo "✓ Backend server started (PID: $BACKEND_PID)"
else
    echo "✗ Backend server failed to start"
fi

if lsof -Pi :8081 -sTCP:LISTEN -t >/dev/null ; then
    echo "✓ Frontend server started (PID: $FRONTEND_PID)"
else
    echo "✗ Frontend server failed to start"
fi

echo ""
echo "Press Ctrl+C to stop..."

# Wait for Ctrl+C
trap "echo ''; echo 'Stopping servers...'; kill $BACKEND_PID $FRONTEND_PID 2>/dev/null; echo 'Servers stopped.'; exit 0" INT TERM

# Keep script running
wait
