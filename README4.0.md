🚍 DATS: Daffodil Smart Transport System
Version: 3.5 (UI Overhaul & Navigation Update)

Platform: WordPress / PHP / MySQL

Module: Fleet Management, Live Scheduling & Central Hub

📖 Project Overview
DATS is a dynamic transport management system designed to replace manual Excel sheets. It features a central Home Hub, a Secure Mission Control for admins, and a Live Student Noticeboard that updates in real-time.

🚀 What's New in v3.5?
🏠 New Home Hub: A modern landing page that directs users to the correct dashboard (Student vs. Admin).

🎨 UI Overhaul:

Admin: "Fleet Command // NEXUS" – Dark, tech-inspired interface with operator data.

Student: "Campus Network // LIVE" – Bright, glass-morphism header with pulse animations.

☰ Sidebar Navigation: A custom slide-out menu on every page, completely replacing the default WordPress theme navigation.

⚡ Zero-Clutter Mode: Automatically hides default WordPress headers/footers for an "App-like" experience.

🕹️ Key Features
1. 🏠 The Central Hub (dats-home.php)
Hero Banner: Auto-adapts to the page's "Featured Image".

Role-Based Access: Clear call-to-action buttons for Students (View Schedule) and Staff (Login).

2. 🕹️ Admin Mission Control (admin-console.php)
Secure Portal: Hides the WordPress dashboard. Admins log in via a custom interface.

⛔ Conflict Detection (The Logic Shield):

Prevents double-booking.

Example: If Bus UNI-01 is assigned to Mirpur at 7:00 AM, the system blocks any attempt to assign it to Uttara at the same time.

🔄 Smart Renew: One-click [ 🔄 +7 Days ] button to extend schedules instantly.

🧠 Smart Dropdowns: Automatically grays out buses/drivers that are already busy at the selected time.

3. 📱 Student Live Board (student-noticeboard.php)
Real-Time Status: Auto-refreshes every 60 seconds.

Smart Filtering: Only shows future trips (hides buses that have already left).

Route Sorting: Groups buses by Corridor (e.g., "Mirpur Route") and Direction (Inbound ⬇️ / Outbound ⬆️).

Privacy: Hides sensitive driver data from the public view.

⚙️ Installation & Setup (Crucial Steps)
1. Database Setup
Ensure your MySQL database has the 4 core tables:

dats_buses: Inventory (Bus No, Vendor, Seats).

dats_drivers: Personnel (Name, Phone).

dats_routes: Corridors (Mirpur, Uttara, Dhanmondi).

dats_assignments: The Master Log (Links Bus + Driver + Route + Time).

2. WordPress Configuration
To make the Home Page work correctly as the root URL (http://localhost/dats/):

Go to WordPress Dashboard > Settings > Reading.

Under "Your homepage displays", select A static page.

For Homepage, select your created "Home" page.

Click Save Changes.

3. Page Templates
Ensure your pages are using the correct templates in the "Page Attributes" section:

Home Page ➝ Template: DATS Home Page

Admin Console ➝ Template: DATS Admin Console

Student Board ➝ Template: DATS Student Board

🛠️ Maintenance (The Golden Rule)
To prevent database corruption in XAMPP:

Always open XAMPP Control Panel.

Click Stop on MySQL.

Wait for the Port numbers to disappear.

Only then shut down your computer.

🔮 Future Roadmap
📱 Mobile App: Native Android/iOS wrapper for the student board.

🔔 SMS Alerts: Notify drivers automatically when a mission is assigned.

📍 GPS Tracking: Real-time location integration.

System Verified & Operational | Spring 2026