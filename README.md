Here is the comprehensive README.txt file summarizing all features and system architecture added to your DATS project up to this point.

You can save this file in your project folder or GitHub repository.

🚍 DATS: Daffodil Smart Transport System
Version: 2.5 (Spring 2026 Edition) Platform: WordPress / PHP / MySQL Module: Dynamic Fleet Management & Live Scheduling

📖 Project Overview
DATS is a database-driven transport management system designed to replace static Excel sheets with a dynamic, real-time application. It allows the Transport Office to assign buses, manage drivers, and define custom routes, while providing students with a live, auto-sorted noticeboard.

🌟 Key Features Implemented
1. 🕹️ Admin Mission Control (Backend)
Secure Login Portal: A custom "Fleet Command" login screen that hides the default WordPress dashboard, ensuring a professional interface for management.

Dynamic Assignment: Admins can assign resources (Bus + Driver) to a specific Route + Timeframe.

Manual Path Control: Unlike static routes, Admins can now manually type the Origin (start_loc) and Destination (end_loc).

Example: A bus can be assigned from "Mirpur 10" to "DIU Campus" OR "DIU Campus" to "Uttara".

Excel-Style Reporting: The active schedule log mimics the official Transport Excel sheet. It automatically groups assignments by Corridor (Route) and splits them into Inbound and Outbound columns.

Audit Trail: Records which Admin user created an assignment.

Time Sorting: Automatically sorts schedules chronologically (e.g., 7:00 AM appears before 1:00 PM regardless of entry order).

2. 📱 Student Live Noticeboard (Frontend)
Route-Centric View: Buses are grouped by "Corridor" (e.g., Mirpur Route, Uttara Route) so students can find their specific bus line immediately.

Smart Direction Sorting: The system intelligently analyzes the destination:

If destination contains "Campus" or "DSC" -> Listed under ⬇️ To Campus (Inbound).

All other destinations -> Listed under ⬆️ From Campus (Outbound).

Privacy Protected: Driver names and phone numbers are hidden from the public view to ensure safety.

Real-Time Validity: The board only displays buses valid for Today. Expired schedules are automatically hidden.

🗄️ Database Architecture
The system relies on 4 relational tables.

1. dats_buses
Stores physical bus inventory.

bus_id (PK), bus_number, vendor_name, status.

2. dats_drivers
Stores personnel data.

driver_id (PK), name, phone.

3. dats_routes
Stores the main "Corridors" used for grouping (e.g., Mirpur, Dhanmondi).

route_id (PK), route_name.

4. dats_assignments (The Schedule)
The core transactional table linking all resources.

assignment_id (PK)

bus_id (FK)

driver_id (FK)

route_id (FK - Used for Corridor Grouping)

start_loc (Text - Manual Origin)

end_loc (Text - Manual Destination)

departure_time (Text)

valid_from (Date)

valid_to (Date)

assigned_by (WP User ID)

⚙️ How to Deploy
1. Database Import
Ensure the dats_ tables are imported into your MySQL database using the provided SQL file.

2. PHP Snippets (WPCode / Functions.php)
Snippet 39 (Admin Console): Contains the Logic for the Secure Login, Assignment Form, and Admin Reporting.

Snippet 16 (Student Board): Contains the Logic for fetching today's data, sorting by Inbound/Outbound, and rendering the public UI.

🧠 Logic Flow
Admin logs in via the Custom Portal.

Admin selects a Corridor (e.g., "Mirpur") and types the specific path (e.g., "From: Mirpur 10", "To: Campus").

System saves this with a validity date range (e.g., Feb 1 to Feb 28).

Student visits the site.

System checks today's date.

System detects the destination is "Campus" and places the bus in the Inbound column under the "Mirpur" section.