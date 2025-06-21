# Hotel Reservation System (Booking) - Software Requirements Specification
**Deployment URL:** [https://hotel-booking.duckdns.org/](https://hotel-booking.duckdns.org/)
**GitHub Repo:** [https://github.com/uni-dev-team-ase-01/hotel-reservation-app](https://github.com/uni-dev-team-ase-01/hotel-reservation-app)

## Table of Contents
1.  [Introduction](#1-introduction)
    *   [1.1 Purpose](#11-purpose)
    *   [1.2 Scope](#12-scope)
2.  [System Overview](#2-system-overview)
    *   [2.1 Key Terminology](#21-key-terminology)
    *   [2.2 User Roles and Characteristics](#22-user-roles-and-characteristics)
    *   [2.3 Assumptions and Dependencies](#23-assumptions-and-dependencies)
3.  [Requirements](#3-requirements)
    *   [3.1 Functional Requirements](#31-functional-requirements)
        *   [3.1.1 Report Requirements](#311-report-requirements)
    *   [3.2 Non-Functional Requirements](#32-non-functional-requirements)
    *   [3.3 Hidden and Implicit Requirements](#33-hidden-and-implicit-requirements)
4.  [Design](#4-design)
    *   [4.1 System Architecture](#41-system-architecture)
    *   [4.2 Database Design](#42-database-design)
    *   [4.3 Hardware and Software Specifications](#43-hardware-and-software-specifications)
5.  [Testing](#5-testing)
6.  [Group Member Contributions](#6-group-member-contributions)
7.  [References](#7-references)
8.  [Appendix](#8-appendix)
    *   [8.1 Document Information](#81-document-information)

---

## 1. Introduction
<a name="1-introduction"></a>

### 1.1 Purpose
<a name="11-purpose"></a>
The primary purpose of the **Booking (Hotel Reservation System)** is to streamline and automate hotel operations, including reservations, billing, guest management, and reporting. This document defines the software requirements, providing a detailed guide for developers, testers, and stakeholders to ensure the system meets its objectives of enhancing efficiency and user experience for both hotel staff and guests. The system aims to replace manual processes with an organized, up-to-date online platform.

*(Adapted from user's original SRS: "1.2 Purpose of the System" and "1.1 Introduction")*

### 1.2 Scope
<a name="12-scope"></a>
The system provides a comprehensive platform for managing hotel reservations. Key capabilities include:
*   Real-time room availability search and booking for customers and travel companies.
*   Dynamic pricing and automated billing for no-shows and extended stays.
*   Management of reservations, check-ins/outs, guest data, and payments by hotel staff.
*   Generation of operational and financial reports.
*   Role-based access control for different user types.
*   Integration with third-party services for payments (Stripe) and email notifications (Brevo).

Initially, the system is designed to support the operations of a **single hotel**. Future enhancements may include multi-hotel support. The system covers the entire lifecycle from a customer searching for a room to post-stay reporting and analytics for hotel management.

*(Summarized from user's original SRS "1.1 Introduction", "1.2 Purpose", FRs, and "1.7.3 Constraints")*

---

## 2. System Overview
<a name="2-system-overview"></a>

### 2.1 Key Terminology
<a name="21-key-terminology"></a>
*(Adapted from user's original SRS: Table 1: Key Terminology)*

| Term               | Definition                                                                                                                               |
|--------------------|------------------------------------------------------------------------------------------------------------------------------------------|
| **Admin**          | A user with elevated system privileges, encompassing roles like Reservation Clerk, Manager, and System Administrator.                        |
| **Reservation**    | The act of securing a room or suite for a specified duration, initiated either directly by a customer or through a travel agency.           |
| **Check-In**       | The formal arrival process where a guest is registered and assigned their accommodation.                                                     |
| **Check-Out**      | The procedure for finalizing a guest's stay, which includes settling the bill and vacating the room.                                       |
| **Customer**       | An individual booking accommodation for personal use, who may either register an account or book as a guest.                                 |
| **Travel Company** | An organization that arranges bookings for groups, typically involving three or more rooms, and often benefits from special rates.        |
| **Reservation Clerk**| Hotel front-desk personnel tasked with managing bookings, guest arrivals/departures, and payment processing.                               |
| **Manager**        | A leadership role focused on analyzing reports and hotel performance metrics to drive strategic, data-informed decisions.                    |
| **System Admin**   | The top-tier technical user responsible for system-wide configuration, user account management, and overall system oversight.              |
| **Residential Suite**| A premium accommodation option designed for extended stays (weekly or monthly), featuring distinct pricing and services.                   |
| **Optional Services**| Additional amenities that guests can add to their booking, such as laundry, room service, or restaurant access.                         |
| **No-Show**        | A scenario where a guest with a confirmed reservation fails to arrive, triggering an automatic charge based on hotel policy (e.g., at 7 PM). |
| **Dynamic Pricing**| A strategy where room rates are adjusted automatically based on factors like demand, day of the week, and length of stay.                  |
| **Automated Process**| A task executed by the system without manual intervention, such as canceling unconfirmed bookings or generating nightly reports.            |
| **Billing Record** | A detailed invoice issued to a customer or travel company, itemizing all charges, taxes, and applied discounts.                              |
| **Report**         | A system-generated summary that offers insights into key metrics like occupancy rates, revenue figures, and booking trends.                  |
| **SRS**            | Software Requirements Specification                                                                                                        |
| **UI/UX**          | User Interface / User Experience                                                                                                           |
| **API**            | Application Programming Interface                                                                                                          |
| **MVC**            | Model-View-Controller                                                                                                                    |
| **ERD**            | Entity Relationship Diagram                                                                                                                |
| **PCI-DSS**        | Payment Card Industry Data Security Standard                                                                                               |

### 2.2 User Roles and Characteristics
<a name="22-user-roles-and-characteristics"></a>
*(Adapted from user's original SRS: "1.3.3 User Roles and Characteristics")*

The system defines specific roles for different types of users to ensure appropriate access and functionality:

*   **Customer:** An individual guest who uses the public-facing website to search for rooms, make, modify, or cancel their own reservations.
*   **Travel Company:** A corporate partner that can make discounted bulk bookings (3+ rooms) for its clients and is billed directly by the hotel.
*   **Reservation Clerk:** A hotel staff member with access to the administrative backend. They can manage all reservations, perform check-ins and check-outs, process payments, and manage customer records.
*   **Manager:** A senior staff member with all the permissions of a Reservation Clerk, plus the ability to view financial and occupancy reports for strategic planning.
*   **System Administrator:** The top-tier technical user responsible for system-wide configuration, user account management, and overall system oversight.
*   **System (Cronjob):** A non-human actor that performs scheduled tasks, such as nightly cancellations of unconfirmed bookings and report generation (e.g., automated 7 PM processes).

### 2.3 Assumptions and Dependencies
<a name="23-assumptions-and-dependencies"></a>
*(Combined and summarized from user's original SRS sections "1.7.1 Assumptions" and "1.7.2 Dependencies")*

**Assumptions:**
*   Users have reliable internet access and modern web browsers.
*   Hotel staff receive adequate training on administrative interfaces.
*   A stable server environment supporting Laravel, PHP 8.3+, MySQL 8.0, and Nginx is available.
*   Third-party services (Stripe, Brevo) are configured and operational.
*   Business logic for rates, discounts, and automated tasks is correctly implemented.

**Dependencies:**
*   **Framework & Language:** Laravel PHP framework, PHP 8.3+.
*   **Database:** MySQL relational database.
*   **Email Services:** Brevo (or similar) for automated email notifications.
*   **Payment Processing:** Stripe for secure credit card transactions (PCI-DSS compliance).
*   **Server Environment:** Hosting that supports Laravel, scheduled jobs (cron), and necessary web server software (Nginx/Apache).
*   **Frontend Technologies:** Bootstrap 5, JavaScript (potentially Livewire/Ajax for dynamic UIs).
*   **Package Management:** Composer (PHP), NPM/Yarn (JS).

---

## 3. Requirements
<a name="3-requirements"></a>

This section outlines the specific requirements for the Booking system.

### 3.1 Functional Requirements
<a name="31-functional-requirements"></a>
*(Adapted from user's original SRS section "1.6.1 Functional Requirements (FR)")*

| ID    | Requirement                                                                                                                               |
|-------|-------------------------------------------------------------------------------------------------------------------------------------------|
| **FR1.1** | Customers must be able to search for room availability by date and room type.                                                               |
| **FR1.2** | Customers must be able to make a reservation online, providing personal details, number of occupants, and arrival/departure dates.        |
| **FR1.3** | The system must support two types of reservations: guaranteed (with credit card details) and non-guaranteed.                              |
| **FR1.4** | Customers must be able to change or cancel their reservations through the website.                                                          |
| **FR1.5** | Reservation Clerks must be able to create, view, modify, and cancel reservations on behalf of customers.                                  |
| **FR2.1** | Reservation Clerks must be able to check in a guest, both with a prior reservation and as a walk-in.                                     |
| **FR2.2** | During check-in, a specific room must be assigned to the customer, and a customer record must be created or updated.                      |
| **FR2.3** | Reservation Clerks must be able to check out a guest, which finalizes the bill.                                                           |
| **FR2.4** | The system must allow the clerk to change a customer's checkout date during their stay.                                                     |
| **FR3.1** | Upon checkout, the system must generate a detailed billing record for the customer.                                                         |
| **FR3.2** | The system must support payment processing by both cash and credit card (via Stripe).                                                       |
| **FR3.3** | The system must allow clerks to add optional charges (e.g., room service, laundry) to a customer's bill.                                  |
| **FR3.4** | For travel companies, bills for block bookings must be charged directly to the company account.                                             |
| **FR4.1** | Travel companies must be able to book three or more rooms at a discounted rate.                                                             |
| **FR4.2** | Customers must be able to reserve residential suites at specific weekly or monthly rates.                                                   |
| **FR5.1** | The system must automatically cancel all non-guaranteed reservations daily at 7 PM (server time).                                           |
| **FR5.2** | The system must automatically identify no-show customers and create a billing record for the reservation fee by 7 PM daily (server time). |
| **FR5.3** | The system must automatically charge for an additional night if a customer does not check out by the hotel's designated checkout time.    |
| **FR5.4** | Managers must be able to view reports on hotel occupancy and financial information. (See also [Report Requirements](#311-report-requirements)) |
| **FR5.5** | The system must automatically produce a daily report showing the total occupancy and revenue for the previous night.                          |
| **FR6.1** | Users (Customers) must be able to register using email and password.                                                                        |
| **FR6.2** | The system must provide login/logout functionalities with session management for all user roles.                                          |
| **FR6.3** | The system must include a password recovery mechanism.                                                                                      |
| **FR7.1** | Users must be able to filter hotel/room searches by criteria such as location (if multi-hotel), date, room type, and price range.         |
| **FR8.1** | Administrators (System Admin, Manager, Clerk as per role) must have CRUD operations for Hotels, Rooms, Services, Rates, Users, etc.      |
| **FR8.2** | Managers must be able to view booking statistics and export reports.                                                                        |

#### 3.1.1 Report Requirements
<a name="311-report-requirements"></a>
*(Adapted from user's original SRS section "1.7 Report Requirements (Summary)")*

The system must support the generation of the following essential reports:
*   **Daily Occupancy and Revenue Report:** Automatically generated daily summarizing room occupancy and total revenue from the previous night.
*   **No-Show Report:** Lists customers who failed to check in, with associated billing details.
*   **Hotel Occupancy Report (Manager View):** Displays current and historical room occupancy data for selected dates.
*   **Projected Occupancy Report (Manager View):** Forecasts future occupancy based on current and upcoming bookings.
*   **Financial/Revenue Report (Manager View):** Detailed breakdown of room revenue, ancillary services income, filterable by date range.
*   **Customer Checkout Statement:** Itemized bill generated at customer checkout.
*   **Travel Company Invoices:** Invoices for bookings made through travel agencies/partners.
*   **Reservation Overview Report:** Comprehensive summary for clerks/managers to monitor ongoing and past reservations.

### 3.2 Non-Functional Requirements
<a name="32-non-functional-requirements"></a>
*(Adapted from user's original SRS section "1.6.2 Non-Functional Requirements (NFR)")*

| ID      | Category        | Requirement                                                                                                                                  |
|---------|-----------------|----------------------------------------------------------------------------------------------------------------------------------------------|
| **NFR1**  | Security        | Safeguard customer personal data and payment information (encryption, HTTPS). Protect against common web threats (SQLi, XSS). Passwords hashed (bcrypt). Role-Based Access Control (RBAC) strictly enforced. |
| **NFR2**  | Performance     | Web pages load within 2–3 seconds under normal load. Critical operations (booking, payment) within 5 seconds. Support 100+ concurrent users. Reports generated within 30 seconds. |
| **NFR3**  | Availability    | Maintain 99.9% uptime, excluding scheduled off-peak maintenance.                                                                             |
| **NFR4**  | Scalability     | Architecture must support future growth (users, transactions, rooms, multiple hotels) without performance degradation.                       |
| **NFR5**  | Usability       | Intuitive, user-friendly interface across all roles. Responsive design for desktop, tablet, and mobile. Clear feedback for user actions.        |
| **NFR6**  | Maintainability | Follow Laravel MVC and coding best practices. Well-structured, commented code and comprehensive documentation for easy updates.              |
| **NFR7**  | Logging         | Log critical events, user/admin activities, and errors for auditing, security monitoring, and issue resolution.                            |
| **NFR8**  | Backup & Recovery| Automated daily backups of essential data. Reliable recovery plan to restore data swiftly in case of failures.                             |
| **NFR9**  | Concurrency     | Support multiple concurrent user sessions without race conditions or data inconsistencies, especially during booking and inventory updates.    |
| **NFR10** | Data Validation | Validate all user inputs (client-side and server-side) to ensure data integrity and prevent invalid entries.                                 |

### 3.3 Hidden and Implicit Requirements
<a name="33-hidden-and-implicit-requirements"></a>
*(Adapted from user's original SRS section "1.6.3 Hidden and Implicit Requirements (HIR1)")*

*   **HIR1: Real-time Room Availability:** Display real-time room availability during searches/bookings to prevent overbooking. Update inventory immediately on booking/cancellation.
*   **HIR2: Detailed Billing Logic:** Billing statements must clearly itemize charges (rooms, services, taxes, discounts, penalties) with accurate calculations.
*   **HIR3: User Permissions (RBAC):** Access to functions/data strictly role-based. (Also covered in NFR1 Security).
*   **HIR4: User Notifications:** Automated email notifications (Brevo) for key events (bookings, changes, cancellations, payments, check-outs).
*   **HIR5: Automated Scheduling:** Reliable scheduling (Laravel Scheduler/cron) for time-based tasks (e.g., 7 PM cancellations/no-shows, daily reports).
*   **HIR6: Tax & Discount Configuration:** Admins must be able to configure tax rates and discount structures in the admin panel.
*   **HIR7: Suite Booking Logic:** Accommodate unique pricing (weekly/monthly) and booking rules for residential suites.
*   **HIR8: Custom Reporting Filters:** Managers must be able to generate reports filtered by date range, room type, revenue stream, etc.
*   **HIR9: Data Validation:** All user inputs validated for data integrity and security. (Also covered in NFR10).

---

## 4. Design
<a name="4-design"></a>

This section provides an overview of the system design for the Booking (Hotel Reservation System).

### 4.1 System Architecture
<a name="41-system-architecture"></a>
*(Adapted from user's original SRS section "1.8.4 System architecture design" and other contextual information)*

The Booking system is built using the **Laravel PHP framework**, adhering to the **Model-View-Controller (MVC)** architectural pattern. This promotes a clean separation of concerns, making the system maintainable and scalable. The architecture can be described in multiple layers:

*   **Presentation Layer (User Interface):**
    *   **Customer-Facing Interface:** Developed using Laravel Blade templates, styled with Bootstrap 5, and enhanced with JavaScript. Dynamic features may utilize Livewire or similar AJAX-based techniques for real-time updates (e.g., room availability).
    *   **Administrative Interface:** A comprehensive admin panel built with Filament, allowing hotel staff (Reservation Clerks, Managers, System Admins) to manage the system's operational aspects.
*   **Application Layer (Business Logic):**
    *   This layer, implemented in Laravel, contains the core business logic, services, and controllers. It handles:
        *   User authentication and authorization (role-based access control).
        *   Reservation processing (search, booking, modification, cancellation).
        *   Room inventory and rate management.
        *   Billing logic and payment gateway integration (Stripe).
        *   Automated tasks via Laravel Scheduler (e.g., nightly no-show processing, report generation).
        *   Email notification dispatch (Brevo).
*   **Data Layer (Persistence):**
    *   **Database:** MySQL is used as the relational database to store all persistent data, including user profiles, hotel and room details, reservations, billing records, and system configurations.
    *   **External Services:** Integrates with Stripe for payment data processing and Brevo for email delivery.

(Refer to Figure 4: System Architecture Diagram in the original SRS document (page 25). Ideally, embed from `docs/images/system_architecture.png`)

An example of a scheduled task configuration in Laravel:
```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Example: Automated 7 PM tasks for no-shows and cancellations
    $schedule->command('reservations:process-no-shows')->dailyAt('19:00');
    $schedule->command('reservations:cancel-unconfirmed')->dailyAt('19:00');
    $schedule->command('reports:generate-daily-occupancy')->dailyAt('01:00'); // Or another suitable time
}
```

### 4.2 Database Design
<a name="42-database-design"></a>
*(Adapted from user's original SRS section "1.8.1 ERD of the system")*

The database schema is designed to support all functionalities of the hotel reservation system. Key entities include Users, Roles, Hotels, Rooms, RoomTypes, Reservations, Bills, Payments, Services, and TravelCompanies.

*   **Entity Relationship Diagram (ERD):** An ERD illustrating the database structure is detailed in Figure 1 (page 20) of the original SRS document. For this Markdown document, it would ideally be embedded (e.g., `![ERD Diagram](docs/images/erd.png)`).
*   **Use Case Diagram:** Illustrates interactions between user roles and system functionalities. Detailed in Figure 2 (page 22) of the original SRS document. (Ideally embedded from `docs/images/use_case_diagram.png`).
*   **Class Diagram:** Shows system structure in terms of classes, attributes, and relationships. Detailed in Figure 3 (page 24) of the original SRS document. (Ideally embedded from `docs/images/class_diagram.png`).

### 4.3 Hardware and Software Specifications
<a name="43-hardware-and-software-specifications"></a>
*(Adapted from user's original SRS sections "1.4 Hardware specification" and "1.5 Software specification")*

**Hardware Requirements:**
*   **Development Environment (Minimum):**
    *   CPU: Dual-core (Intel Core i3 4th Gen / AMD equivalent)
    *   RAM: 4 GB (8 GB recommended)
    *   Storage: 120 GB HDD/SSD (SSD preferred)
    *   Display: 1366x768 resolution
*   **Client/End-User Environment (Minimum):**
    *   CPU: Intel Pentium / Core i3 2nd Gen / AMD equivalent
    *   RAM: 2 GB (4 GB recommended)
    *   Storage: 60 GB available
    *   Display: 1280x720 resolution
    *   Input: Standard keyboard/mouse or touchpad
    *   Mobile: Compatible with Android/iOS devices (via web browser)

**Software Requirements:**
*   **Server-Side:**
    *   OS: Linux (recommended), Windows, macOS
    *   Web Server: Nginx (recommended) or Apache
    *   PHP: Version 8.3+
    *   Database: MySQL Version 8.0+
    *   Dependency Manager: Composer
    *   Version Control: Git
*   **Client-Side:**
    *   OS: Windows 10/11, macOS, Linux, Android, iOS
    *   Browser: Latest versions of Google Chrome, Mozilla Firefox, Microsoft Edge, Safari.
*   **Development Tools:**
    *   IDE: Visual Studio Code (recommended) with extensions like Prettier.
    *   Node.js & NPM/Yarn: For frontend asset compilation (Vite/Laravel Mix).
*   **Third-Party Services:**
    *   Stripe API for payment processing.
    *   Brevo API/SMTP for email notifications.

---

## 5. Testing
<a name="5-testing"></a>
*(Adapted from user's original SRS section "1.9 Testing" and "1.11 Individual conclusion" for Member 3)*

The system undergoes comprehensive testing to ensure functionality, reliability, and performance.

*   **Testing Levels:**
    *   **Unit Testing:** Developers are responsible for writing unit tests for individual classes and methods, particularly for backend logic using PHPUnit or Pest.
    *   **Integration Testing:** Focuses on testing the interaction between different modules, such as the reservation module with the payment gateway (Stripe) and notification service (Brevo).
    *   **System Testing:** Verifying the complete and integrated software meets all specified requirements.
    *   **User Acceptance Testing (UAT):** Conducted by stakeholders or representative end-users to ensure the system is fit for purpose.
*   **Test Cases:** Specific test cases are designed to cover various scenarios, including successful paths, error conditions, and boundary values.
    *(Refer to Table 2: Test cases in the original SRS document, page 29.)*
    <details>
    <summary><b>View Example Test Case Categories (Illustrative - Refer to full Table 2 from original SRS)</b></summary>

    | Test ID Prefix | Scenario Category             | Example Focus                                  |
    |----------------|-------------------------------|------------------------------------------------|
    | TC-LOGIN       | User Authentication           | Valid/invalid login, password recovery.        |
    | TC-BOOK        | Room Booking                  | Search, select, book, payment success/failure. |
    | TC-ADMIN-RES   | Admin Reservation Management  | Create, modify, cancel reservations.           |
    | TC-REPORT      | Report Generation             | Accuracy of daily occupancy/revenue reports.   |
    </details>

*   **Key Testing Outcomes/Fixes (from Member 3's original SRS conclusion):**
    *   Addressed issues with password recovery emails (SMTP configuration).
    *   Strengthened input validation for search functionalities.
    *   Fixed booking authentication issues (ensuring only logged-in users can book).

---

## 6. Group Member Contributions
<a name="6-group-member-contributions"></a>
*(Summarized from user's original SRS section "1.10 Group Member Contributions" and "1.8.5-1.8.7")*

The project was developed collaboratively with distinct responsibilities:

*   **Member 1 (Backend Implementation & Admin Dashboard):**
    *   Core backend systems: Hotel and Room management implementation (CRUD operations).
    *   System settings configuration (taxes, discounts, travel company details).
    *   Admin Dashboard development (Filament) for clerks, managers, and admins.
    *   User roles and permissions backend logic.
*   **Member 2 (Full Frontend UI & Functionality Setup, Customer-Facing Features, Documentation):**
    *   Customer Login page development.
    *   Travel Agent view and functions, Contact Form page.
    *   Reservation Find Hotel page (real-time search and booking functionality).
    *   Main website Index page and overall UI structure.
    *   Comprehensive technical documentation.
*   **Member 3 (Ramisha: Navbar, Authentication, About Us Page, Frontend Support):**
    *   Navbar component creation and integration.
    *   User Authentication features, including password recovery.
    *   "About Us" page content and design.
    *   UI defect fixes and general team support during integration and testing.
    *   Specific contributions: Customer Login, Travel Agent Request Form, Contact Form, Find Hotel Page Search, Reservation Integration with Authentication.

All team members actively participated in requirements analysis, design reviews, and testing phases to ensure a cohesive and functional system.

---

## 7. References
<a name="7-references"></a>
*(Adapted from user's original SRS section "2 References")*

*   **Laravel PHP Framework:** [https://laravel.com/docs](https://laravel.com/docs)
*   **Bootstrap 5:** [https://getbootstrap.com/docs/5.0/](https://getbootstrap.com/docs/5.0/)
*   **MySQL:** [https://dev.mysql.com/doc/](https://dev.mysql.com/doc/)
*   **Stripe (Payment Processing):** [https://stripe.com/docs](https://stripe.com/docs)
*   **Brevo (Email Notifications):** [https://www.brevo.com/docs](https://www.brevo.com/docs) (formerly Sendinblue)
*   **Filament (Admin Panel for Laravel):** [https://filamentphp.com/docs](https://filamentphp.com/docs)
*   **Livewire (Dynamic Interfaces for Laravel):** [https://livewire.laravel.com/docs](https://livewire.laravel.com/docs)
*   **PHP:** [https://www.php.net/manual/en/](https://www.php.net/manual/en/)
*   **Composer (PHP Dependency Manager):** [https://getcomposer.org/doc/](https://getcomposer.org/doc/)
*   **Nginx:** [https://nginx.org/en/docs/](https://nginx.org/en/docs/)
*   **W3C HTML5:** [https://www.w3.org/TR/html5/](https://www.w3.org/TR/html5/)
*   **MDN Web Docs (JavaScript, CSS, etc.):** [https://developer.mozilla.org/en-US/docs/Web](https://developer.mozilla.org/en-US/docs/Web)
*   **PCI Security Standards Council:** [https://www.pcisecuritystandards.org/](https://www.pcisecuritystandards.org/)

---

## 8. Appendix
<a name="8-appendix"></a>
*(Adapted from user's original SRS section "2.1 Appendices")*

This section is intended for supplementary materials that support the SRS. The original "Course Submission Cover Sheet" document contains several figures that would typically be included or referenced here. For this Markdown document, these images should ideally be stored in a repository folder (e.g., `/docs/images/`) and embedded where relevant or listed here as references.

*   **Figure 1: ERD of the system** (Page 20 of original SRS)
*   **Figure 2: Use case diagram** (Page 22 of original SRS)
*   **Figure 3: Class diagram of the system** (Page 24 of original SRS)
*   **Figure 4: System architecture design** (Page 25 of original SRS)
*   **Figure 5: Flow chart** (Page 26 of original SRS)
*   **Figure 6: Test Cases (Screenshot/example)** (Page 32 of original SRS)
*   **Figures 7-10: Evidence (Screenshots)** (Pages 35-38 of original SRS)

Additional appendices could include:
*   Detailed wireframes or UI mockups.
*   A more comprehensive database schema document.

### 8.1 Document Information
<a name="81-document-information"></a>
*   **Version:** 1.0
*   **Date:** (Current Date - to be filled upon generation)
*   **Authors:** Project Team (Ramisha Gimhama, Pasindu Lakmal, Jithma Perera - based on SRS content)
*   *(Note: The original "Course Submission Cover Sheet" included an Academic Integrity declaration. This declaration is part of the overall course submission and not typically reiterated within the SRS body unless specifically required by formatting guidelines for this document.)*
