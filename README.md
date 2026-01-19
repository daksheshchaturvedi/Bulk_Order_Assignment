# Bulk Order Assignment System

A backend service for assigning thousands of orders daily to couriers in bulk using PHP and MySQL.

This system is designed for high-volume order processing with transaction safety, courier capacity handling, and race-condition protection.

---

## Tech Stack

- PHP (REST APIs)
- MySQL
- PHP Built-in Server (No Apache/XAMPP required)
- Postman for API testing

---

## Features

- Create orders (single & bulk)
- Fetch unassigned orders
- Fetch available couriers by location
- Bulk assign orders to couriers
- Courier capacity management
- Transaction-safe assignment
- Partial assignment handling
- Duplicate assignment prevention

---

## Database Schema

### Orders
- order_id (PK)
- order_date
- delivery_location
- order_value
- status (NEW, ASSIGNED, UNASSIGNED)

### Couriers
- id (PK)
- name
- daily_capacity
- current_assigned_count

### Courier Locations
- courier_id (FK)
- location

### Order Assignments
- assignment_id (PK)
- order_id (FK, UNIQUE)
- courier_id (FK)
- assignment_date

---

## Setup Instructions

### 1. Clone Repository
```bash
git clone https://github.com/daksheshchaturvedi/Bulk_Order_Assignment.git
cd Bulk_Order_Assignment
```
### 2. Create Database
```sql
CREATE DATABASE order_system;

```

### 3. Configure Database
```
config/db.php
```
### 4. Run Server

```bash
php -S localhost:8000
```
server will start at
```
http://localhost:8000
```


###Fatch Unassigned Orders(USE POSTMAN)
```
GET /api/get_unassigned_orders.php
```

###Fatch Available Couriers
```
GET /api/get_available_couriers.php?location=Delhi
```
###Create Order
```
GET /api/get_available_couriers.php?location=Delhi
```
in body Enter
```
(
  "delivery_location": "XYZ",
  "order_value": 123
)
```
###Bulk Assign Orders
```
POST /api/bulk_assign_orders.php
```
Body
```
{
  "order_ids: [1,2,3,4,5]
}



