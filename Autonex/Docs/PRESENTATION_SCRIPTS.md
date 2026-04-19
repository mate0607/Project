## Speaker 1 – Project Overview, Architecture & Core Modules (User, Cars, Issues)

Hello everyone, my name is [Name], and today we're going to present **AutoNex** – a full-stack car service management web application built with Laravel.

### What is AutoNex?

AutoNex is designed for an auto repair shop that wants to manage its customers, their vehicles, appointments, service workflows, and even sell used cars through a built-in marketplace. The application has two roles: regular **users** who are customers of the workshop, and an **admin** who manages everything from the back office.

### Technology Stack

The backend runs on **PHP 8.2** with **Laravel 12**, which is one of the most popular PHP frameworks. For the database, we use **MySQL**. The frontend is built with **Blade templates** – Laravel's built-in templating engine – styled with a combination of **Bootstrap 5**, **Tailwind CSS 4**, and custom **SASS** stylesheets. The build system uses **Vite** for fast asset compilation. We also use **Laravel Sanctum** for API token management, although it's currently prepared for future mobile app integration rather than actively used.

### Architecture

The application follows the **MVC pattern** – Model-View-Controller. When a request comes in, it first goes through the **routing layer** defined in `web.php`, then passes through **middleware** for authentication and authorization checks. The request reaches a **controller**, which interacts with **Eloquent models** to query the database, and finally returns a **Blade view** to the user. We also have **Policies** for fine-grained authorization – for example, a user can only edit their own car, not someone else's.

### User Management

Let me walk you through the first core module: **user management**. A new customer can register by providing their name, email, phone number, and password. After registration, the system sends a **welcome email** and a **verification link**. The user must verify their email before accessing the application – this is handled by Laravel's built-in email verification system. We have two roles stored in the `users` table: `user` and `admin`. The admin account is seeded automatically with the email `admin@admin.com`.

Users can also edit their **profile** – updating their name, email, or phone number through a dedicated profile page.

### Car Management

The second module is **vehicle management**. Each user can register their own cars in the system. A car record includes the **make and model**, **VIN number**, **license plate**, and **year of manufacture**. The car listing page shows all vehicles belonging to the logged-in user. They can add new cars, edit existing ones, or delete them. We use **soft deletes** here, meaning deleted records aren't permanently removed from the database – they can be restored if needed. Authorization is enforced through a **CarPolicy**, so users can only access their own vehicles.

### Issue Management

The third module is the **issue tracking system**. Users can report problems with their vehicles by creating a **ticket**. Each issue is linked to a specific car and includes a **category**, a **description**, and an **urgency level** – which can be low, medium, or high. This helps the workshop prioritize repairs. Issues can be edited or deleted by the car's owner, and the admin has full access to view all issues. Again, the **IssuePolicy** ensures proper authorization.

### Dashboard

Finally, each user has a **personal dashboard** that shows key statistics at a glance: how many upcoming appointments they have, how many cars are currently in service, total vehicles registered, and completed services. It also shows the latest appointments and any unread notifications – which my colleague will explain in more detail.

That covers the foundation of the application. Now let me hand it over to [Speaker 2], who will talk about the appointment system and the admin panel.

---

## Speaker 2 – Appointment System & Admin Panel

Thank you, [Speaker 1]. My name is [Name], and I'll be presenting the **appointment booking system** and the **admin management panel**.

### Appointment Booking

One of the most important features for a car service application is **appointment scheduling**. Our users can book a service appointment by selecting a **date**, a **time slot**, one of their **registered cars**, the **type of service** they need, and an optional **description** of the problem.

When a booking is submitted, the system performs an **automatic collision check** – it verifies that no other confirmed appointment exists for the same date and time. If there's a conflict, the user gets an error message. If the slot is free, the appointment is created with a **unique work order number** in the format `MNK-XXXXXX`, which is randomly generated and guaranteed to be unique.

After successful booking, the system sends a **confirmation email** to the user containing all the details: the date, time, service type, car information, and the work order number. The email is sent through Laravel's **Mail system** using SMTP. If the email fails to send for any reason, the error is logged but the application continues to work normally – the appointment is still created.

Users can also **cancel** their pending or confirmed appointments, or **reschedule** them by providing a new date and time. The reschedule feature also performs collision detection to prevent double-bookings.

### Admin Dashboard

Now let's look at the **admin side**. The admin has a dedicated dashboard at `/admin-dashboard` that provides a comprehensive overview of the workshop's operations. It displays the **number of cars currently in service**, the **details of today's appointments**, and **recently completed vehicles**.

The centerpiece of the admin dashboard is a **monthly calendar view** that shows all appointments visually – so the admin can see at a glance how busy each day is. Below that, there's a **statistics section** with charts showing trends over the **last 6 months** – things like how many appointments were completed, revenue generated, and workload distribution.

### Admin Appointment Management

The admin has full control over appointments through the `/admin/appointments` interface. They can **filter** appointments by customer name, car, license plate, or date range. The admin can also **create appointments manually** – for example, when a customer calls by phone. In this case, the admin enters the customer's name, phone number, and vehicle details directly.

The most powerful feature here is the **service workflow tracking**. When an admin edits an appointment, they can update:
- The **service stage** – tracking where the car is in the repair process
- The **mechanic's name** – selected from a pool of 20 predefined mechanics
- The **total cost** of the service
- A **service report** describing what was done
- **Issues found** during inspection
- A **critical warning** flag if something urgent was discovered
- And they can upload **service photos** documenting the work

The admin can also perform **quick status changes** – confirming, cancelling, or marking appointments as completed. When an appointment is marked as completed and the service stage is also set to "done", the system automatically creates a **notification** for the customer, letting them know their car is ready.

### Admin Notification System

The admin can send **targeted notifications** to specific users or **broadcast notifications** to all users at once. This is managed through the `/admin/notifications` interface, where the admin can compose a notification with a title and message, select the recipient, and send it. Notifications can also be filtered and deleted from this interface.

These notifications appear in the user's **navigation bar** as a bell icon with an unread count badge – but [Speaker 3] will explain that system in detail.

That's the appointment system and admin panel. Now I'll hand it over to [Speaker 3] for the marketplace and messaging system.

---

## Speaker 3 – Marketplace, Messaging System & Notifications

Thank you, [Speaker 2]. My name is [Name], and I'll present the **car marketplace**, the **messaging system**, and the **notification system**, as well as give a brief overview of the **database design** and **testing**.

### Car Marketplace

AutoNex includes a built-in **car marketplace** where the admin can list vehicles for sale. Each listing includes detailed information: **vehicle type**, **model**, **body type**, **engine displacement**, **fuel type**, **price**, **condition**, **mileage**, and whether **documents** and **technical inspection** are available. The admin can upload **multiple images** per listing, which are displayed in a **gallery with thumbnails**, navigation arrows, and even a **lightbox** for enlarged viewing.

Users can browse active listings with **pagination** – 10 per page – and view detailed information for each car. Only the admin can create, edit, or delete listings, enforced by the **SalePolicy**.

### Messaging System

This is one of the features we're most proud of. Instead of a traditional messaging page, we implemented an **inline AJAX-based chat** that appears directly on the **car detail page** and the **sale listing page**. When a user is viewing a car or a marketplace listing, they see a chat section at the bottom where they can type and send a message.

The key design decisions are:
- **All messages go to the admin** – when a user sends a message, it's automatically directed to the admin account. There's no need to select a recipient.
- **The admin replies back** – when the admin responds, the message goes to the user who last wrote about that car.
- **Messages are car-based**, not sale-based – so the conversation is tied to the vehicle, not the listing.
- **Everything is loaded via AJAX** – messages are fetched as **JSON** using the Fetch API and rendered dynamically in the browser. When you send a message, it's posted via AJAX too, so the page never reloads.

The **authorization logic** allows three types of users to participate in a conversation: the **car owner**, the **admin**, and anyone viewing an **active sale listing** for that car. This means a potential buyer can ask questions directly from the marketplace page.

On the admin side, there's a dedicated **message management page** at `/admin/messages` that lists all cars with conversations, showing the **unread message count** for each. The admin can click into any conversation and reply. There's also a **red badge** on the "Messages" menu item in the navigation bar, showing the total number of unread messages.

### Notification System

We have a **dual notification system**. First, there are **admin-created notifications** – these can be targeted to a specific user or broadcast to everyone. Second, **automatic notifications** are created whenever a new message is sent. So if the admin replies to a user's message, the user immediately sees a notification.

On the frontend, notifications appear as a **bell icon** in the navigation bar with a **badge** showing the unread count. Clicking the bell opens a **dropdown** with the latest 8 notifications, each showing the title, a preview of the message, and a relative timestamp like "2 minutes ago". Users can mark individual notifications as read by hovering over them, or click **"Mark all as read"** to clear everything at once.

### Database Design

Briefly on the database: we have **12 tables** in total. The core ones are `users`, `cars`, `appointments`, `issues`, `sales`, `messages`, `sale_images`, `service_photos`, and `admin_notifications`. All relationships are defined using Laravel's **Eloquent ORM** – for example, a User has many Cars, a Car has many Messages, a Sale has many Images. We use **foreign keys** for referential integrity and **soft deletes** on most tables so data is never permanently lost.

### Testing

For testing, we have a complete set of **database seeders** that generate realistic test data – an admin account, 10 random users, random cars, appointments, issues, sales listings, and messages. The test suite uses **PHPUnit** and is organized into Feature and Unit test directories.

### Summary

To summarize the entire project: AutoNex is a full-featured car service management platform covering everything from vehicle registration and appointment booking to a car marketplace with inline messaging. It's built on modern technologies – Laravel 12, MySQL, Vite – and follows best practices like MVC architecture, policy-based authorization, soft deletes, and AJAX-powered interfaces.

Thank you for your attention. We're happy to answer any questions.
