
# 🏨 Old Rock Resort Hotel Booking System

A web-based hotel booking system for Old Rock Resort Hotel built with HTML, CSS, JavaScript, PHP, and MySQL.

## 📁 Project Structure

```
.
├── assets/
├── code/
│   ├── all-room.html
│   ├── booking_confirmation.html
│   ├── booking-form.html
│   ├── home.html
│   ├── login.html / login.php
│   ├── register.html / register.php
│   ├── db.php
│   ├── get-booking.php
│   ├── process-booking.php
│   ├── submit-booking.php
│   ├── update-booking.php
│   ├── update-booking-status.php
│   ├── upload-payment.php
├── styles/
│   └── styles.css
```

## 📊 Database Schema (MySQL)

### 🧾 `users` table

| Column       | Type          | Description                    |
|--------------|---------------|--------------------------------|
| `id`         | INT (PK, AI)  | Primary key                    |
| `first_name` | VARCHAR(50)   | User's first name              |
| `last_name`  | VARCHAR(50)   | User's last name               |
| `email`      | VARCHAR(100)  | Unique email address           |
| `phone`      | VARCHAR(20)   | Contact number (nullable)      |
| `password`   | VARCHAR(255)  | Hashed password                |
| `created_at` | TIMESTAMP     | Auto timestamp on registration |

> 📌 `email` has a unique index.

---

### 🏨 `bookings` table

| Column              | Type              | Description                                     |
|---------------------|-------------------|-------------------------------------------------|
| `id`                | INT (PK, AI)      | Booking ID (auto increment)                    |
| `booking_reference` | VARCHAR(20)       | Unique booking code                            |
| `first_name`        | VARCHAR(50)       | Guest's first name                             |
| `last_name`         | VARCHAR(50)       | Guest's last name                              |
| `email`             | VARCHAR(100)      | Guest's email                                  |
| `phone`             | VARCHAR(20)       | Guest's contact number                         |
| `room_name`         | VARCHAR(100)      | Room type name (e.g., Deluxe Suite)            |
| `room_price`        | DECIMAL(10,2)     | Price per night                                |
| `checkin_date`      | DATE              | Check-in date                                  |
| `checkout_date`     | DATE              | Check-out date                                 |
| `number_of_guests`  | INT               | Total number of guests                         |
| `number_of_nights`  | INT               | Duration of stay in nights                     |
| `total_amount`      | DECIMAL(10,2)     | Total cost for the booking                     |
| `arrival_time`      | VARCHAR(50)       | Expected arrival time                          |
| `special_requests`  | TEXT              | Optional requests by guest                     |
| `status`            | ENUM              | `pending`, `confirmed`, `cancelled`, `archived`|
| `payment_status`    | ENUM              | `pending`, `paid`, `partial`                   |
| `payment_proof`     | VARCHAR(255)      | Path to uploaded proof of payment              |
| `created_at`        | TIMESTAMP         | Auto-created timestamp                         |
| `updated_at`        | TIMESTAMP         | Auto-updated timestamp                         |

---

## ⚙️ Features

- 📝 User Registration and Login
- 🏘️ View All Available Rooms
- 📋 Room Booking with Summary
- 📤 Upload Payment Proof
- 🔁 Booking Confirmation and Status Update
- ✅ Admin Approval or Rejection of Bookings

## 💡 How to Run

1. Copy the project into `htdocs/` if using XAMPP.
2. Import the database (`oldrock_hotel.sql`) into phpMyAdmin.
3. Set your DB credentials in `db.php`.
4. Visit `http://localhost/code/home.html`.

---

**Developed by:** Justin Casul  
**Location:** Alaminos City, Pangasinan  
**School:** Pangasinan State University
