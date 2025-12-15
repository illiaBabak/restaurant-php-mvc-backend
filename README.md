# 🍽 Restaurant PHP MVC Backend

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB-4EA94B?style=for-the-badge&logo=mongodb&logoColor=white)
![Twilio](https://img.shields.io/badge/Twilio-F22F46?style=for-the-badge&logo=twilio&logoColor=white)
![Mailgun](https://img.shields.io/badge/Mailgun-EB0000?style=for-the-badge&logo=mailgun&logoColor=white)

**Backend pet project for learning PHP, the MVC pattern, MongoDB, and integrations with Twilio and Mailgun.**

</div>

---

## 📖 About

**Goal of the project** – to practice on a real example:

- **Plain PHP** without frameworks
- The **MVC pattern** (controllers, models, core layer)
- **MongoDB** and the official PHP driver
- Integration with external services: **Twilio** (SMS) and **Mailgun** (email)

The app implements a simple restaurant backend: managing waiters, dishes, and bills, plus helper integrations for SMS/email.

---

## ✨ Main Features

- **Waiter management**:
  - Create, update, delete
  - Pagination and search by first name, last name, email, phone
- **Dish management**:
  - CRUD operations for menu items
- **Bills**:
  - Create / update / delete a bill
  - Prepare data for export (for example, sending via email)
- **MongoDB**:
  - Work with collections (`waiters`, `dishes`, `bills`)
  - Pagination, sorting by creation date
- **Twilio**:
  - Basic integration for sending SMS (notifications, statuses, etc.)
- **Mailgun**:
  - Sending emails (receipts, notifications, etc.)

---

## 🛠 Tech Stack (Short)

- **Backend**: PHP 8.3 (CLI dev server)
- **Architecture**: custom **MVC** (controllers / models / core)
- **Database**: MongoDB
- **Integrations**: Twilio (SMS), Mailgun (email)
- **Dependencies**: Composer (`mongodb/mongodb`, `vlucas/phpdotenv`, etc.)
- **Infrastructure**: Docker (`php:8.3-cli`)

---

## 📁 Project Structure (Short)

```text
public/
  index.php          # entry point, CORS, router, Mongo connection

core/
  Router.php         # simple router (URL → controller@method)
  Mongo.php          # MongoDB connection (Client, Database)
  Helper.php         # utilities
  Response.php       # building HTTP responses
  Twilio.php         # Twilio API integration
  MailgunClient.php  # Mailgun API integration

app/
  Controllers/
    WaiterController.php
    DishController.php
    BillController.php
  Models/
    Waiter.php       # work with waiters collection
    Dish.php         # work with dishes collection
    Bill.php         # work with bills collection

vendor/              # Composer dependencies
Dockerfile           # container with PHP + ext-mongodb
README.md
```

---

## ⚙️ Environment Configuration

Create a `.env` file in the project root (next to `composer.json`):

```env
MONGO_URI=mongodb+srv://username:password@cluster.example.mongodb.net/?retryWrites=true&w=majority
MONGO_DB=restaurant_db

TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=+10000000000

MAILGUN_DOMAIN=your.mailgun.domain
MAILGUN_API_KEY=your_mailgun_api_key
MAILGUN_FROM="Restaurant <noreply@yourdomain.com>"
```
