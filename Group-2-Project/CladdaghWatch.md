Requirements Document - MVP

Claddagh Watch Patrol – Patrol
Rostering System (Web Application)

Date:22/01/2026

Version: DRAFT VERSION 0.1

Author: Gerry Guinane

# 1

Introduction

**Purpose of this Document:** To provide an overview the requirements for  of a web application to be used by a Galway
based voluntary group (Claddaghwatch). Claddaghwatch provides river/waterway
patrols with the aim of suicide prevention and water safety in Galway city.   The primary
purpose of the web application is to provide an effective way to organise
patrol rosters.

**Scope of the Application:** The scope of the application is to provide a means of creating a
roster schedule for patrol teams.

**Development Goal:** The primary goal of this web application development is to create a
simple, effective rostering tool to manage and organise Claddaghwatch patrols.

**Development Philosophy:** The final implementation should be built from widely used
components. All components must be easily deployed and work within the existing
backend framework (XAMPP) . Design choices should prioritise simplicity over
complexity – Simplicity-first. Design should also ensure ongoing ease of
maintenance and a high standard of website and data security.

**Minimum Viable Product (MVP):** The requirements contained in this document represent the Minimum
Viable Product (MVP) requirements that will need to be completed before any
production (live) deployment can be undertaken. Significant additional
functionality may be defined for future follow on projexts.

**Definitions, Acronyms, and Abbreviations**

·
Patrol : The activity
undertaken by a Patrol Team – ie to patrol key areas of waterways with the
objective of identifying people in need of assistance.

·
Patrol Team: A team of people
(usually 6-8) who are rostered to Patrol.

·
Patrol Volunteers:  Regular participants in Patrols.

·
Patrol Supervisor (SUPER) : The
person who is responsible for directing the Patrol Team and engaging with
emergency servises.

·
Schedule (Roster Schedule): The
set of dates on which Patrols are scheduled.

·
Roster: The names of
volunteers  and SUPER name who are
assigned to a particular Patrol.

**References**

Claddaghwatch website: [claddaghwatch.ie](http://claddaghwatch.ie)

# 2

Stakeholders & Users

**Key Stakeholders**

The primary stakeholder is the
Claddaghwatch organisation which consists of Patrol volunteers, Patrol
Supervisors, Technical/Web Administrator, Operations manager.

**User Roles**

·
Patrol volunteers : The
individual members who make themselves available for patrol. They will use the
web application to view future patrol roster dates, select dates on which they
will be available to join a roster and to view rosters that have been
released/finalised.

·
Patrol Supervisors : The
individuals who supervise Patrol Teams. SUPERS will use the web application to
view future patrol roster dates, select dates on which they will be available
to join a roster as the patrol supervisor.

·
Operations Manager.: The
operations manager role is to schedule future patrol dates and to release
rosters that have sufficient volunteer resources to proceed. The operations
manager will use the web application to create a schedule of future patrol
dates,  to release the future schedule
dates, view the resourcing of future rosters, release rosters when they are
resources. A roster may be ‘released’ or ‘not released’ . A released roster is
one that the OperationsManager deems to be sufficiently resourced (volunteers and
SUPER) . A released roster is viisible to all users. An unreleased roster is
not visible to regular Patrol volunteers.

·
Technical/Web Administrator : The
individual(s) responsible for managing the hosting services, website, email
etc.

·
General public/non members:
Will not have access to this web application.

# 3

Functional Requirements

## 3.1

User Access & Roles

### 3.1.1

User Authentication

·
The system shall require all
users to authenticate before accessing the application.

·
Only registered users with an
assigned role may access system functionality.

### 3.1.2

Role-Based Access Control

 The
system shall enforce role-based access to features and data.The following roles
shall be supported:

·
Patrol Volunteer

·
Patrol Supervisor (SUPER)

·
Operations Manager

·
Technical/Web Administrator

### 3.1.3

Public Access Restriction

The system shall not allow access to any
functionality or data to general public or non-members.

## 3.2

User Management

### 3.2.1

User Account Management

The system shall allow the Technical/Web
Administrator to:

a)
Create user accounts

b)
Assign user roles

c)
Activate or deactivate user
accounts

Profile Viewing

d)
The system shall allow users to
view their own profile details.

e)
Users shall not be able to
modify their role.

## 3.3

Patrol Schedule Management

### 3.3.1

 Create Patrol Schedule

a)
The system shall allow the
Operations Manager to create a Schedule consisting of future Patrol dates.

b)
Each Patrol date shall be
uniquely identified by a patrol number and have an associated description, date
and time.

### 3.3.2

Publish Schedule Dates

a)
The system shall allow the
Operations Manager to release (publish) future Patrol dates so that volunteers
and SUPERS can view them.

### 3.3.3

 Edit or Cancel Patrol Dates

a)
The system shall allow the
Operations Manager to modify or cancel future Patrol dates that have not yet
occurred.

b)
Changes shall be reflected
immediately to all users.

## 3.4

Availability Management

### 3.4.1

 View Future Patrol Dates

a)
The system shall allow Patrol
Volunteers and SUPERS to view released future Patrol dates.

### 3.4.2

 Volunteer Availability Submission

a)
The system shall allow Patrol
Volunteers to indicate availability for one or more Patrol dates.

b)
The system shall allow
volunteers to withdraw availability prior to roster release.

### 3.4.3

 Supervisor Availability Submission

a)
The system shall allow Patrol
Supervisors to indicate availability for Patrol dates as SUPER.

b)
The system shall allow SUPERS
to withdraw availability prior to roster release.

## 3.5

Roster Creation &
Management

### 3.5.1

 Roster Assembly

The system shall associate volunteer
availability and supervisor availability with each Patrol date.

A Patrol Team shall consist of:

·
One Patrol Supervisor (SUPER)

·
A configurable number of Patrol
Volunteers (typically 6–8)

### 3.5.2

 View Roster Resourcing Status

The system shall allow the Operations
Manager to view:

·
Number of volunteers available
per Patrol date

·
Whether a SUPER is available

·
Whether the Patrol is
sufficiently resourced

## 3.6

Roster Release Control

### 3.6.1

 Roster Release

The system shall allow the Operations
Manager to mark a roster as Released” or “Not Released” (default).

Only rosters marked as “Released” shall be
visible to Patrol Volunteers.

### 3.6.2

 Roster Visibility Rules

Released rosters shall be visible to:

·
Operations Manager

·
Patrol Volunteers

·
Patrol Supervisors

·
Technical/Web Administrator

Unreleased rosters shall be visible only
to:

·
Patrol Supervisors

·
Technical/Web Administrator

·
Operations Manager

### 3.6.3

Roster Locking

Once a roster is released, the system shall
prevent further changes to volunteer assignments unless the roster by all users
except  the Operations Manager.

The Operations Manager may continue to
add/change personnel to rosters that have been released with updates becoming
visible to all users.

## 3.7

7. Roster Viewing

### 3.7.1

 Volunteer Roster Viewing

The system shall allow Patrol Volunteers to
view:

·
Released rosters only

·
Filter select and view their
own future Patrol dates for both released and unreleased rosters.

·
Names of other volunteers but
not the assigned SUPER

### 3.7.2

Supervisor Roster Viewing

·
The system shall allow Patrol
Supervisors to view:

·
All rosters - Released and
unreleased

·
Names of assigned Patrol
Volunteers and supervisors along with mobile phone/contact details.

## 3.8

Notifications (To be further
developed)

Released rosters and any roster updatesare
required to  become visible to users by
refreshing the roster view in the web applicaiton.

Consideration will be given to implementing
an automatic messaging system integrated to an SMS/WhatsApp messaging provider
to facilitate live updates. .

In those circumstances the following
features may be required. This should be included in design considerations.

### 3.8.1

Roster Release Notification

The system shall notify assigned volunteers
and the SUPER when a roster is released.

### 3.8.2

Schedule Change Notification

The system shall notify affected users when
a Patrol date is changed or cancelled.

# 4

NonFunctional Requirements

## 4.1

Performance (response times,
load)/Scalability

This system is not reqauired to support a
significant number of end users. – hundreds as opposed to thousands.  Response times/load times are not expected to drive
design decisions.

## 4.2

Availability & Reliability

The system will be hosted by Blacknight
(service provider) which will be responsible for maintaining
uptime/availability.

## 4.3

Security Requirements

### 4.3.1

General Security Requirements

The website shall comply with the following
European regulations and standards:

·
General Data Protection
Regulation (GDPR – EU 2016/679)

·
Recognised best practices
including:

o
ISO/IEC 27001 principles

o
OWASP Top 10

### 4.3.2

Access Control &
Authentication

User Authentication

·
The system shall require
authenticated access for all users.

·
Passwords shall meet minimum
strength requirements and never be stored in plaintext.

### 4.3.3

Secure Credential Storage

Passwords shall be hashed using a strong,
industry-accepted algorithm (e.g. bcrypt, Argon2, or equivalent).

### 4.3.4

Role-Based Access Control

The system shall enforce least-privilege
access based on user roles.

·
Users shall only access data
and functions necessary for their role.

### 4.3.5

Session Management

·
User sessions shall expire
after a defined period of inactivity.

·
Session identifiers shall be
protected against reuse.

### 4.3.6

Data Security (Confidentiality
& Integrity)

Encryption in Transit -All data transmitted
between users and the website shall be encrypted using TLS (HTTPS).

Encryption of Personal Data in Database -Personal
and sensitive data stored in databases and backups shall be encrypted at rest
where feasible.

### 4.3.7

Secure Development &
Maintenance

Secure Development Practices

·
The system shall be developed
following secure coding practices.

·
Known common vulnerabilities
(e.g. OWASP Top 10) shall be addressed.

Dependency Management

·
Third-party libraries and
frameworks shall be kept up to date with security patches.

Vulnerability Management

The system shall undergo security testing
(e.g. vulnerability scans or penetration testing) as part of its development
process.

### 4.3.8

Hosting & Infrastructure
Security

Hosting (and hence hosting security)  will be provided for the foreseeable future by
Blacknight Solutions hosting service. (https://www.blacknight.com/)

## 4.4

Usability & Accessibility

## 4.5

Maintainability

## 4.6

Compatibility (Browsers,
Devices)

## 4.7

Localization /
Internationalization

# 5

User Experience (UX) & UI
Requirements

The user interface shall be:

·
Adaptive, responsive, and
mobile-friendly

·
Compatible with all modern
browsers

·
Fully functional on Android and
Apple devices

·
Built with standard frameworks
to ensure long-term maintainability

·
Simple, intuitive, and reliable
for users operating in real-world, time-sensitive contexts

## 5.1

General UX / UI Principles (All
Users)

### 5.1.1

  Simplicity & Clarity

The interface shall prioritise clarity over
visual complexity.

Screens shall present only information
relevant to the current user role.

Advanced or administrative functions shall
not be visible to non-authorised users.

### 5.1.2

 Consistent Layout

The application shall use a consistent
layout structure across all user roles:

·
Header with application name
and user menu

·
Main content area

·
Optional footer

Layout consistency shall be maintained
across desktop and mobile views.

### 5.1.3

Industry-Standard Frameworks

The user interface shall be implemented
using well-supported, industry-standard front-end frameworks such as:

·
Bootstrap (responsive grid,
forms, layout)

·
Vue.js (component-based UI,
state handling)

Custom UI code shall be minimised to
support long-term maintainability.

### 5.1.4

 Adaptive & Responsive Design

The user interface shall be fully adaptive
and responsive, automatically adjusting layout and interaction patterns based
on screen size and device capabilities.

The application shall be usable on:

·
Desktop and laptop browsers

·
Tablets

·
Mobile phones

Mobile usage by volunteer role users shall
be treated as a primary use case, not a secondary one.

Desktop/browser usage shall be treated as a
primary use case for all other users.

## 5.2

Cross-Browser &
Cross-Platform Compatibility

The application shall be compatible with
all modern, standards-compliant browsers, including:

·
Chrome

·
Firefox

·
Safari

·
Edge

The application shall function correctly
on:

·
Android-based mobile devices

·
Apple iOS devices (iPhone and
iPad)

No user functionality shall depend on a
browser- or platform-specific feature.

## 5.3

Touch-Friendly Interaction

Interactive elements (buttons, links, form
controls) shall be sized and spaced appropriately for touch interaction.

Common mobile gestures (tap, scroll) shall
be fully supported.

## 5.4

Dashboard-Centric Navigation

### 5.4.1

Each user shall be presented
with a role-specific dashboard immediately upon login.

·
Dashboards shall provide:

·
At-a-glance information

·
Clear primary actions

·
Simple navigation to all core
functions

·
Navigation shall adapt
appropriately between desktop (menus/sidebars) and mobile (collapsed menus or
navigation drawers).

## 5.5

Web Content Accessibility
Guidelines

The interface shall aim to meet WCAG 2.1 AA
accessibility standards. (https://www.w3.org/TR/WCAG21/)

Content shall remain readable and usable
across devices and screen sizes.

## 5.6

Patrol Volunteer UX / UI
Requirements

### 5.6.1

 Volunteer Dashboard

·
The dashboard shall be
optimised for both mobile and desktop viewing.

·
Key information (upcoming
Patrol dates, assignments) shall be immediately visible without excessive
scrolling.

### 5.6.2

Availability Selection

·
Availability selection shall be
achievable with a single tap or click per Patrol date.

·
Controls shall be
mobile-friendly and clearly indicate selected state.

### 5.6.3

Roster Viewing

Roster views shall adapt to smaller screens
by:

·
Stacking content vertically

·
Using expandable sections where
appropriate

·
Released rosters only shall be
visible.

### 5.6.4

Navigation

·
Navigation shall be simple and
consistent across devices.

·
On mobile devices, navigation
shall be accessible via a clearly identifiable menu icon.

## 5.7

 Patrol Supervisor (SUPER) UX / UI Requirements

### 5.7.1

 Supervisor Dashboard

·
The dashboard shall prioritise
clarity and rapid access on mobile devices.

·
Assigned Patrols and upcoming
responsibilities shall be clearly highlighted.

### 5.7.2

 Availability & Roster Viewing

Availability selection and roster viewing
shall use the same adaptive interaction patterns as Patrol Volunteers.

### 5.7.3

 Navigation

Navigation shall adapt automatically to
screen size while preserving feature discoverability.

## 5.8

 Operations Manager UX / UI Requirements

### 5.8.1

 Operations Dashboard

·
The dashboard shall support
both desktop and tablet use efficiently.

·
Summary views shall remain
readable on smaller screens.

### 5.8.2

 Schedule & Roster Management

·
Forms and tables shall be
responsive and usable on touch devices.

·
Complex tables shall degrade
gracefully on mobile (e.g. collapsible rows).

### 5.8.3

 Visibility & Confirmation

Confirmation dialogs and warnings shall be
clear and usable on both mobile and desktop interfaces.

## 5.9

 Technical / Web Administrator UX / UI
Requirements

### 5.9.1

 Administrator Interface

·
Administrative functions shall
be accessible on desktop and tablet devices.

·
Mobile access shall be
supported for monitoring and basic actions, where practical.

### 5.9.2

 User Management

User lists and forms shall be responsive
and usable across supported devices.

## 5.10

 Maintainability & Compatibility
Requirements

### 5.10.1

 Single Adaptive Codebase

The application shall use a single adaptive
UI codebase rather than separate desktop and mobile versions.

### 5.10.2

 Standards-Based Implementation

·
The UI shall rely on
standards-based HTML, CSS, and JavaScript.

·
Browser-specific workarounds
shall be avoided unless strictly necessary.

# 6

Data Requirements

## 6.1

Data Models / Entities

Below is an EER Diagram Draft representing
the required database.

![]()

## 6.2

Data Storage Requirements

Data will be stored in a MySQL (Maria DB )
database.

# 7

System Architecture &
Technical Requirements

To be developed.

# 8

Deployment & Release
Requirements

To be developed.

# 9

Maintenance & Support

To be developed.

# 10 Appendices

To be developed.
