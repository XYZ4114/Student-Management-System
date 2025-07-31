# 🎓 Student Management System

A PHP-based web application designed to streamline student data management. Includes modules for both **admin** and **student** access, enabling CRUD operations on student data, attendance and marks tracking, and PDF report generation via **TCPDF**.

---

## 🚀 Features

### 👨‍🎓 Student Panel

* View profile with picture
* Check attendance and marks
* Download performance report (PDF)
* Send update requests to admin

### 🛠️ Admin Panel

* Secure admin login system
* Add, edit, delete student records
* Handle student update requests
* View student database with photos
* Generate and view student reports
* Manage attendance and marks

### 📄 PDF Generation

* Uses **TCPDF** to generate downloadable student performance reports

---

## 🧰 Tech Stack

* **Backend:** PHP (Vanilla)
* **Frontend:** HTML, CSS, Bootstrap (minimal)
* **Database:** MySQL (`sms.sql`)
* **PDF Library:** TCPDF
* **Version Control:** Git

---

## ⚙️ Local Setup

### 1. **Clone the repository**

```bash
git clone https://github.com/yourusername/Student-Management-System.git
```

### 2. **Move project to XAMPP**

Example:

```
C:\xampp\htdocs\Student-Management-System
```

### 3. **Start Apache and MySQL via XAMPP**

### 4. **Import the database**

* Open `phpMyAdmin`
* Create a new database (e.g., `sms`)
* Import the SQL file:

  ```
  database/sms.sql
  ```

### 5. **Configure database**

Edit `includes/db.php`:

```php
$host = 'localhost';
$db   = 'sms';
$user = 'root';
$pass = '';
```

### 6. **Run the project**

Visit:

```
http://localhost/Student-Management-System/login.php
```

---

## 📦 TCPDF Integration

* TCPDF library is used in `student/download-report.php` to export academic reports in PDF format.
* Pre-installed in the `/TCPDF` directory – no additional composer setup needed.

---

## ⚖️ License

This project is licensed under the MIT License.

---

## 🙌 Credits

Developed by [XYZ4114](https://github.com/XYZ4114). Contributions are welcome!
