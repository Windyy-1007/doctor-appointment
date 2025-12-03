# Service Layer Implementation - Doctor Appointment System

## Project Structure Overview

### What is This Project?
The Doctor Appointment System is a web-based platform that connects patients with medical offices and doctors for appointment booking. It manages the entire appointment lifecycle including registration, scheduling, rescheduling, cancellation, and complaint resolution.

### Architecture Pattern: MVC (Model-View-Controller)

The project uses the Model-View-Controller architectural pattern with an additional Service Layer:

```
REQUEST
  ↓
ROUTER
  ↓
CONTROLLER (HTTP Handling) ← SERVICE LAYER (Business Logic) → MODEL (Data Access)
  ↓
VIEW (Presentation)
```

### Project Structure Components

1. **Controllers** (`/app/controllers/`)
   - Handle HTTP requests and responses
   - Extract user input from forms and URL parameters
   - Delegate business logic to services
   - Render views with processed data
   - Manage session and redirects

2. **Models** (`/app/models/`)
   - Represent database tables as PHP classes
   - Handle direct database operations (CRUD)
   - Execute SQL queries through PDO
   - Return raw data from database
   - Manage table relationships and queries

3. **Services** (`/app/services/`) - NEW LAYER
   - Encapsulate business logic
   - Perform data validation
   - Coordinate between multiple models
   - Handle complex workflows
   - Ensure consistency of business rules
   - Provide reusable operations

4. **Views** (`/app/views/`)
   - Display HTML content to users
   - Receive data from controllers
   - Render dynamic content using PHP
   - Handle form submissions
   - Present information clearly

5. **Core Classes** (`/app/core/`)
   - `Router.php` - URL routing and controller dispatch
   - `Controller.php` - Base controller with render and redirect methods
   - `Model.php` - Base model with database connection
   - `Database.php` - PDO connection management
   - `Auth.php` - Authentication and authorization utilities

### Why We Need the Service Layer?

**Without Service Layer (Traditional MVC):**
- Business logic mixed with HTTP handling in controllers
- Code duplication across multiple controllers
- Difficult to test business logic without HTTP context
- Hard to maintain consistency across the application
- Same validation rules scattered in multiple places

**With Service Layer (This Approach):**
- Clear separation between HTTP and business logic
- Single source of truth for business rules
- Easy to test services independently
- Controllers remain thin and focused
- Code reusability across multiple endpoints

### Project Folder Structure Tree

```
doctor-appointment/
│
├── app/                             # Application code
│   │
│   ├── controllers/                 # HTTP handlers
│   │   ├── AuthController.php
│   │   ├── AdminController.php
│   │   ├── OfficeController.php
│   │   ├── PatientController.php
│   │   └── StaffController.php
|   |
│   ├── services/                    # Business logic
│   │   ├── AuthenticationModel.php
│   │   ├── AppointmentModel.php
│   │   ├── DoctorManagementModel.php
│   │   ├── OfficeManagementModel.php
│   │   ├── ScheduleManagementModel.php
│   │   ├── ReportManagementModel.php
│   │   └── NotificationModel.php
│   │
│   └── views/                       # HTML templates
│       ├── layouts/
│       ├── admin/
│       ├── office/
│       ├── patient/
└─      └── staff/

```

---

## Key Advantages of This Structure

### 1. **Separation of Concerns**
Each layer has a single, well-defined responsibility:
- **Controller**: HTTP request/response handling
- **Service**: Business logic and validation
- **Model**: Database access
- **View**: Presentation

### 2. **Code Reusability**
The same service can be used by multiple controllers without duplication. For example, the `AppointmentService` can be used by:
- Patient appointment booking
- Patient appointment rescheduling
- Admin appointment management
- Office schedule coordination

### 3. **Testability**
Services can be tested independently:
- Test business logic without database (mock models)
- Test controller behavior separately
- Test database queries in isolation
- Write unit tests for each component

### 4. **Maintainability**
Changes are localized and easy to find:
- Fix business logic in one place (service)
- Update validation rules centrally
- Modify database queries in models only
- Change presentation without touching logic

### 5. **Scalability**
The structure supports growth:
- Add new services for new features
- Extend existing services with new methods
- Add new controllers without duplicating logic
- Scale database operations independently

### 6. **Consistency**
Business rules applied uniformly:
- Validation happens the same way everywhere
- Error messages standardized
- Authorization checks consistent
- Status transitions enforced globally

### 7. **Security**
Multiple validation layers:
- Input validation in services
- Authorization checks in controllers
- Database access controlled through models
- Password hashing centralized in authentication service

### 8. **Debugging**
Easier to trace issues:
- Identify which layer has the problem
- Clear data flow through layers
- Consistent response structures
- Easy to add logging at each layer

---

## Overview
The service layer acts as a bridge between controllers and models, encapsulating business logic, validation, and complex operations. It provides a clean separation of concerns and makes the application more maintainable and testable.

---

## 1. Authentication Service

### Purpose
Manages user registration, login, and account management for different user roles (patients, office admins).

### Key Responsibilities
- Register new patient accounts with validation
- Register new office accounts with admin approval status
- Authenticate users with email/password credentials
- Validate login credentials against stored hashes
- Check account activation status
- Handle password hashing using bcrypt algorithm
- Provide consistent error messages for failed attempts
- Ensure email uniqueness across system

### Core Methods
- `registerPatient()` - Create patient account with validation
- `registerOffice()` - Create office account with pending approval
- `authenticate()` - Validate credentials and return user data
- `validateRegistration()` - Check name, email, and password requirements

---

## 2. Appointment Service

### Purpose
Manages the complete appointment lifecycle - booking, rescheduling, and cancellation with validation at each step.

### Key Responsibilities
- Validate appointment requests before booking
- Check doctor and office association
- Verify slot availability before confirming
- Handle reschedule requests with conflict detection
- Manage appointment cancellations with time restrictions
- Validate appointment datetime against working hours
- Prevent past appointments from being modified
- Ensure proper status transitions (booked → cancelled, etc.)

### Core Methods
- `bookAppointment()` - Create new appointment with full validation
- `rescheduleAppointment()` - Move appointment to different time
- `cancelAppointment()` - Cancel scheduled appointment
- `validateAppointmentDatetime()` - Check time is within working hours and future

---

## 3. Doctor Management Service

### Purpose
Handles all doctor-related operations including registration, profile updates, and specialization assignments.

### Key Responsibilities
- Create new doctor records with validation
- Update doctor information and credentials
- Assign specialty to doctors
- Validate medical license numbers
- Ensure license uniqueness across system
- Verify office association with doctors
- Manage doctor qualifications and certifications
- Validate input data before database operations

### Core Methods
- `createDoctor()` - Register new doctor with office and specialty
- `updateDoctor()` - Modify doctor information
- `validateDoctorData()` - Check name, license, and qualifications
- `getOfficeDooctors()` - Retrieve all doctors for an office

---

## 4. Office Management Service

### Purpose
Manages office registration workflow, approval process, and profile administration.

### Key Responsibilities
- Register new medical offices with pending status
- Manage office approval workflow by admin
- Handle office rejection with documentation
- Update office profile information
- Validate office registration details
- Verify unique registration numbers
- Activate/Deactivate office accounts
- Link offices to user accounts

### Core Methods
- `registerOffice()` - Create new office registration (pending approval)
- `approveOffice()` - Admin approval that activates the office
- `rejectOffice()` - Admin rejection with reason
- `updateOfficeProfile()` - Modify office details
- `getPendingOffices()` - List offices awaiting approval
- `getApprovedOffices()` - List active offices

---

## 5. Schedule Management Service

### Purpose
Manages doctor schedules, working slots, and availability blocking.

### Key Responsibilities
- Block time slots for doctor unavailability
- Unblock previously blocked slots
- Track blocked slot reasons (leave, training, etc.)
- Retrieve available slots within date ranges
- Prevent double-booking of time slots
- Validate slot datetime format and working hours
- Ensure 30-minute interval alignment
- Handle future slot management only

### Core Methods
- `blockSlot()` - Mark time slot as unavailable
- `unblockSlot()` - Release blocked time slot
- `getAvailableSlots()` - Find open slots in date range
- `getBlockedSlots()` - List all blocked slots for doctor
- `validateSlotDatetime()` - Check slot validity

---

## 6. Report Management Service

### Purpose
Handles complaint and report submission, resolution workflow, and tracking.

### Key Responsibilities
- Accept complaint submissions from patients
- Categorize reports (quality, billing, hygiene, staff, etc.)
- Track report status lifecycle (open → resolved/dismissed)
- Record resolution details and actions taken
- List open reports for staff review
- Filter reports by office or status
- Validate complaint descriptions
- Ensure proper data collection for investigations

### Core Methods
- `submitReport()` - Create new complaint with category
- `resolveReport()` - Mark report as resolved with resolution
- `dismissReport()` - Close report without action
- `getOpenReports()` - List all active complaints
- `getOfficeReports()` - Filter by specific office

---

## 7. Notification Service

### Purpose
Manages email notifications for appointments and system events.

### Key Responsibilities
- Send appointment confirmation emails to patients
- Send reminder notifications 24 hours before appointment
- Send cancellation notices when appointments are cancelled
- Send welcome emails to new users
- Manage email templates and content
- Handle email delivery failures gracefully
- Log notification activity
- Support various notification types

### Core Methods
- `sendAppointmentConfirmation()` - Email after booking
- `sendAppointmentReminder()` - Pre-appointment reminder
- `sendCancellationNotice()` - Notification of cancellation
- `initializeMailer()` - Configure email service (PHPMailer/SMTP)

---

## Service Layer Architecture

### Design Patterns Used
- **Repository Pattern**: Services use models as data repositories
- **Validation Pattern**: Centralized input validation before operations
- **Response Standardization**: All methods return consistent status response
- **Dependency Injection**: Models injected into services
- **Error Handling**: Comprehensive validation and error messages

### Response Structure
All service methods follow a consistent response format:
- `success` (boolean) - Operation succeeded or failed
- `message` (string) - Human-readable status message
- `data` (optional) - Returned entity ID, updated object, or list

### Flow Pattern
1. Validate input parameters
2. Check authorization and permissions
3. Verify business logic constraints
4. Execute database operation via model
5. Return status response

---

## Benefits of Service Layer

### Organization
- Business logic centralized and separated from controllers
- Related functionality grouped by domain
- Clear responsibilities for each service

### Reusability
- Services can be called from multiple controllers
- Common operations not duplicated
- Consistent business rules applied everywhere

### Testability
- Services can be unit tested independently
- Mock models for testing without database
- Validate business logic in isolation

### Maintainability
- Changes to logic only in service layer
- Controllers remain thin and focused
- Easier to locate and fix bugs

### Security
- Input validation at service layer
- Business rule enforcement
- Authorization checks consistent across system

---

## Integration with Controllers

Services are instantiated in controller constructors and called to handle business operations. Controllers remain focused on:
- HTTP request handling
- Parameter extraction
- View rendering
- Session management

Services handle:
- Business logic
- Data validation
- Database coordination
- Complex calculations

---

## Summary

The service layer architecture provides a professional, scalable foundation for the Doctor Appointment System by:
- ✓ Separating business logic from HTTP concerns
- ✓ Providing reusable, testable components
- ✓ Implementing consistent validation and error handling
- ✓ Enabling future enhancements without code duplication
- ✓ Supporting role-based access controlz
- ✓ Making the codebase more maintainable and professional
