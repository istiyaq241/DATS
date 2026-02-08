/* Template Name: DATS Admin Console */

global $wpdb;

// --- 1. DESIGN LOGIC: GET BACKGROUND IMAGE ---
// This grabs the "Featured Image" you set in the WordPress Page Editor
$bg_image = get_the_post_thumbnail_url();
$bg_style = $bg_image 
    ? "background-image: url('" . esc_url($bg_image) . "'); background-size: cover; background-position: center;" 
    : "background: #2c3e50;"; // Fallback dark color if no image
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Command // Nexus</title>
</head>
<body style="background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; margin: 0;">

<style>
    /* Menu Button */
    .dats-menu-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: #2c3e50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    /* Sidebar Container */
    .dats-sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1001;
        top: 0;
        left: 0;
        background-color: #2c3e50;
        overflow-x: hidden;
        transition: 0.3s;
        padding-top: 60px;
        box-shadow: 2px 0 15px rgba(0,0,0,0.3);
    }

    /* Sidebar Links */
    .dats-sidebar a {
        padding: 15px 30px;
        text-decoration: none;
        font-size: 1.1rem;
        color: #ecf0f1;
        display: block;
        transition: 0.2s;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .dats-sidebar a:hover {
        background-color: #34495e;
        color: #00d2ff; /* Cyan for Admin */
        padding-left: 40px; /* Slide effect */
    }

    /* Close Button */
    .dats-sidebar .closebtn {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 2rem;
        margin-left: 50px;
        border-bottom: none;
    }
</style>

<button class="dats-menu-btn" onclick="toggleNav()">☰ MENU</button>

<div id="datsSidebar" class="dats-sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
    
    <a href="http://localhost/dats/">🏠 Home</a>
    <a href="http://localhost/dats/bus-schedule/">🚍 Student Board</a>
    <a href="http://localhost/dats/fleet-management-console/">🔐 Admin Console</a>
</div>

<script>
    function toggleNav() {
        var sidebar = document.getElementById("datsSidebar");
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0";
        } else {
            sidebar.style.width = "250px";
        }
    }
</script>
<style>
    /* --- FIX: HIDE DEFAULT THEME ELEMENTS --- */
    /* This forces the theme's default Header, Footer, and Titles to disappear */
    .entry-title, .page-title, h1.wp-block-post-title, 
    .post-thumbnail, .wp-block-post-featured-image, .featured-image, 
    header, footer, .wp-site-blocks > header, .wp-site-blocks > footer { 
        display: none !important; 
    }

    /* --- ADMIN BANNER (TECH STYLE) --- */
    .dats-admin-banner {
        <?php echo $bg_style; ?>
        height: 220px; /* Tall banner */
        border-radius: 12px;
        margin-bottom: 25px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end; /* Pushes text to bottom */
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        overflow: hidden;
        margin-top: 60px; /* Space for menu button */
    }

    /* Dark Gradient Overlay for text readability */
    .dats-admin-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 10%, rgba(0,0,0,0.2) 100%);
    }

    /* Content Wrapper */
    .dats-banner-content {
        position: relative; /* Sits on top of overlay */
        z-index: 2;
        color: white;
        font-family: 'Courier New', monospace; /* Tech/Hacker font */
    }

    /* COOL TITLE H2 */
    .dats-cool-title {
        margin: 0 0 15px 0;
        font-size: 2.5rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #fff;
        text-shadow: 0 0 15px rgba(0, 210, 255, 0.6);
        border-left: 6px solid #00d2ff; /* Cyan Accent Bar */
        padding-left: 20px;
        line-height: 1;
    }

    /* OPERATOR INFO ROW */
    .dats-operator-row {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        font-size: 0.9rem;
        background: rgba(0, 0, 0, 0.6);
        padding: 8px 15px;
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        margin-left: 26px; /* Aligns with text */
        backdrop-filter: blur(4px);
    }

    .dats-label { color: #aaa; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
    .dats-username { color: #00d2ff; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    
    /* DISCONNECT BUTTON */
    .dats-logout-btn {
        color: #ff4757;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.75rem;
        border: 1px solid #ff4757;
        padding: 3px 10px;
        border-radius: 3px;
        transition: 0.3s;
        text-transform: uppercase;
    }
    .dats-logout-btn:hover { background: #ff4757; color: white; }

</style>

<?php
// --- 2. SECURITY & FRONTEND LOGIN ---
if (!is_user_logged_in()) {
    ?>
    <div style="font-family: 'Segoe UI', sans-serif; min-height: 600px; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; width: 100%; max-width: 400px; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 10px;">🚍</div>
            <h2 style="color: #2c3e50; margin: 0 0 5px 0;">Fleet Command</h2>
            <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 30px;">Authorized Personnel Only</p>
            <div class="dats-login-form"><?php wp_login_form(array('redirect' => get_permalink())); ?></div>
        </div>
    </div>
    <style>
        .dats-login-form p { margin-bottom: 15px; text-align: left; }
        .dats-login-form label { display: block; font-weight: 600; font-size: 0.85rem; color: #34495e; margin-bottom: 5px; }
        .dats-login-form input[type="text"], .dats-login-form input[type="password"] { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; box-sizing: border-box; }
        .dats-login-form input[type="submit"] { background: #0073aa; color: white; border: none; padding: 12px 0; width: 100%; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 1rem; margin-top: 10px; }
    </style>
    <?php return; 
}

$current_user = wp_get_current_user();
$message = "";

// --- 3. BACKEND LOGIC (WITH CONFLICT DETECTION) ---
if (isset($_POST['dats_action'])) {
    
    // ACTION: DELETE
    if ($_POST['dats_action'] == 'delete_assignment') {
        $wpdb->delete('dats_assignments', array('assignment_id' => intval($_POST['assign_id'])));
        $message = '<div style="background:#fff3cd; color:#856404; padding:15px; border-radius:8px; margin-bottom:20px;">🗑️ Assignment Revoked.</div>';
    }
    
    // ACTION: RENEW (+7 DAYS)
    elseif ($_POST['dats_action'] == 'extend_assignment') {
        $id = intval($_POST['assign_id']);
        $current_to = sanitize_text_field($_POST['current_valid_to']);
        $new_to = date('Y-m-d', strtotime($current_to . ' + 7 days'));
        
        $wpdb->update('dats_assignments', array('valid_to' => $new_to), array('assignment_id' => $id));
        $message = '<div style="background:#d1ecf1; color:#0c5460; padding:15px; border-radius:8px; margin-bottom:20px;">🔄 Validity Extended to <strong>'.$new_to.'</strong>. <small>(Click Edit to adjust details)</small></div>';
    }
    
    // ACTION: ASSIGN or UPDATE
    elseif ($_POST['dats_action'] == 'assign' || $_POST['dats_action'] == 'update_assignment') {
        
        // Gather Inputs
        $bus_id         = intval($_POST['bus_id']);
        $driver_id      = intval($_POST['driver_id']);
        $dep_time       = sanitize_text_field($_POST['departure_time']);
        $valid_from     = sanitize_text_field($_POST['valid_from']);
        $valid_to       = sanitize_text_field($_POST['valid_to']);
        $assign_id      = isset($_POST['assign_id']) ? intval($_POST['assign_id']) : 0;
        
        // 🛑 CONFLICT DETECTION QUERY
        $conflict_query = "
            SELECT COUNT(*) FROM dats_assignments 
            WHERE departure_time = '$dep_time' 
            AND assignment_id != $assign_id
            AND (
                ('$valid_from' BETWEEN valid_from AND valid_to) OR 
                ('$valid_to' BETWEEN valid_from AND valid_to) OR
                (valid_from BETWEEN '$valid_from' AND '$valid_to')
            )
            AND (bus_id = $bus_id OR driver_id = $driver_id)
        ";
        
        if ($wpdb->get_var($conflict_query) > 0) {
            // BLOCK ACTION
            $message = '<div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #f5c6cb;">
                <strong>⛔ ACTION BLOCKED: Conflict Detected!</strong><br>
                The selected <strong>Bus</strong> or <strong>Driver</strong> is already scheduled at <strong>'.$dep_time.'</strong> during these dates.
            </div>';
        } else {
            // PROCEED
            $data = array(
                'bus_id'         => $bus_id,
                'driver_id'      => $driver_id,
                'route_id'       => intval($_POST['route_id']),
                'start_loc'      => sanitize_text_field($_POST['start_loc']),
                'end_loc'        => sanitize_text_field($_POST['end_loc']),
                'departure_time' => $dep_time,
                'valid_from'     => $valid_from,
                'valid_to'       => $valid_to,
                'assigned_by'    => $current_user->ID
            );

            if ($_POST['dats_action'] == 'assign') {
                $wpdb->insert('dats_assignments', $data);
                $message = '<div style="background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px;">✅ Mission Assigned Successfully.</div>';
            } else {
                $wpdb->update('dats_assignments', $data, array('assignment_id' => $assign_id));
                $message = '<div style="background:#cce5ff; color:#004085; padding:15px; border-radius:8px; margin-bottom:20px;">🔄 Schedule Updated.</div>';
            }
        }
    }
}

// --- 4. FETCH DATA ---
$edit_data = null;
if (isset($_GET['edit_assign'])) {
    $edit_data = $wpdb->get_row("SELECT * FROM dats_assignments WHERE assignment_id = ".intval($_GET['edit_assign']));
}

$buses   = $wpdb->get_results("SELECT * FROM dats_buses WHERE status='active'");
$drivers = $wpdb->get_results("SELECT * FROM dats_drivers");
$routes  = $wpdb->get_results("SELECT * FROM dats_routes WHERE route_name NOT LIKE '%Campus Return%'"); 

// FETCH ALL ASSIGNMENTS FOR JS LOGIC
$all_active_assignments = $wpdb->get_results("SELECT bus_id, driver_id, departure_time, valid_from, valid_to FROM dats_assignments");

// FETCH ACTIVE LIST WITH LIVE FILTER
$raw_assignments = $wpdb->get_results("
    SELECT a.*, b.bus_number, b.vendor_name, d.name as driver_name, d.phone as driver_phone, r.route_name, u.display_name as admin_name
    FROM dats_assignments a
    JOIN dats_buses b ON a.bus_id = b.bus_id
    JOIN dats_drivers d ON a.driver_id = d.driver_id
    JOIN dats_routes r ON a.route_id = r.route_id
    LEFT JOIN wp_users u ON a.assigned_by = u.ID
    WHERE STR_TO_DATE(a.departure_time, '%h:%i %p') > CURTIME()  /* Live Filter */
    ORDER BY r.route_name ASC, STR_TO_DATE(a.departure_time, '%h:%i %p') ASC
");

// Grouping Logic
$grouped_schedule = [];
foreach ($raw_assignments as $item) {
    if (strpos(strtolower($item->end_loc), 'campus') !== false || strpos(strtolower($item->end_loc), 'dsc') !== false) {
        $direction = 'inbound';
    } else {
        $direction = 'outbound';
    }
    $grouped_schedule[$item->route_name][$direction][] = $item;
}

// --- SAFETY CHECK FOR HELPER FUNCTION ---
if (!function_exists('dats_sel')) {
    function dats_sel($current, $val) { if($current == $val) echo 'selected'; }
}
?>

<div style="font-family: 'Segoe UI', sans-serif; max-width: 1200px; margin: 0 auto; background: #f4f6f9; padding: 20px; border-radius: 10px;">
    
    <?php echo $message; ?>

    <div class="dats-admin-banner">
        <div class="dats-banner-content">
            <h2 class="dats-cool-title">FLEET COMMAND // NEXUS</h2>
            
            <div class="dats-operator-row">
                <span class="dats-label">OPERATOR:</span>
                <span class="dats-username"><?php echo $current_user->display_name; ?></span>
                <a href="<?php echo wp_logout_url(get_permalink()); ?>" class="dats-logout-btn">[ DISCONNECT ]</a>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid <?php echo ($edit_data) ? '#007bff' : '#28a745'; ?>; margin-bottom: 40px;">
        <h3 style="margin-top:0; color:#555; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:20px;"><?php echo ($edit_data) ? '✏️ Modify Schedule' : '➕ Assign New Mission'; ?></h3>
        
        <form method="post" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <input type="hidden" name="dats_action" value="<?php echo ($edit_data) ? 'update_assignment' : 'assign'; ?>">
            <?php if($edit_data): ?><input type="hidden" name="assign_id" value="<?php echo $edit_data->assignment_id; ?>"><?php endif; ?>

            <div style="background:#e3f2fd; padding:15px; border-radius:8px; border:1px solid #bbdefb;">
                <div style="font-weight:bold; font-size:0.75rem; color:#0d47a1; letter-spacing:1px; margin-bottom:10px;">1. TIME & DATE</div>
                
                <label style="font-size:0.85rem; font-weight:bold; color:#0d47a1;">Select Time</label>
                <select id="dats_time" name="departure_time" style="width:100%; padding:10px; margin-top:5px; border:2px solid #0073aa; border-radius:5px; font-weight:bold;">
                    <?php $cur = ($edit_data) ? $edit_data->departure_time : ''; ?>
                    <option value="">-- Choose Time --</option>
                    <option <?php dats_sel($cur, '07:00 AM'); ?>>07:00 AM</option>
                    <option <?php dats_sel($cur, '08:30 AM'); ?>>08:30 AM</option>
                    <option <?php dats_sel($cur, '01:00 PM'); ?>>01:00 PM</option>
                    <option <?php dats_sel($cur, '01:30 PM'); ?>>01:30 PM</option>
                    <option <?php dats_sel($cur, '04:20 PM'); ?>>04:20 PM</option>
                    <option <?php dats_sel($cur, '06:10 PM'); ?>>06:10 PM</option>
                    <option <?php dats_sel($cur, '09:35 PM'); ?>>09:35 PM</option>
                </select>

                <div style="display:flex; gap:10px; margin-top:15px;">
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">From</label>
                        <input type="date" id="dats_date_from" name="valid_from" required value="<?php echo ($edit_data) ? $edit_data->valid_from : date('Y-m-d'); ?>" style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">To</label>
                        <input type="date" id="dats_date_to" name="valid_to" required value="<?php echo ($edit_data) ? $edit_data->valid_to : date('Y-m-d', strtotime('+7 days')); ?>" style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px;">
                    </div>
                </div>
            </div>

            <div style="background:#f9f9f9; padding:15px; border-radius:8px; border:1px solid #eee;">
                <div style="font-weight:bold; font-size:0.75rem; color:#aaa; letter-spacing:1px; margin-bottom:10px;">2. RESOURCES</div>
                <label style="font-size:0.85rem; font-weight:bold;">Available Buses</label>
                <select id="dats_bus" name="bus_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin-bottom:10px;">
                    <option value="">Select Time First...</option>
                    <?php foreach($buses as $b) { $sel = ($edit_data && $edit_data->bus_id == $b->bus_id) ? 'selected' : ''; echo "<option value='{$b->bus_id}' $sel>{$b->bus_number} ({$b->vendor_name})</option>"; } ?>
                </select>
                
                <label style="font-size:0.85rem; font-weight:bold;">Available Drivers</label>
                <select id="dats_driver" name="driver_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <option value="">Select Time First...</option>
                    <?php foreach($drivers as $d) { $sel = ($edit_data && $edit_data->driver_id == $d->driver_id) ? 'selected' : ''; echo "<option value='{$d->driver_id}' $sel>{$d->name}</option>"; } ?>
                </select>
                <div id="availability_msg" style="font-size:0.8rem; color:#d63031; margin-top:5px; height:20px;"></div>
            </div>

            <div style="background:#fff8e1; padding:15px; border-radius:8px; border:1px solid #ffe0b2;">
                <div style="font-weight:bold; font-size:0.75rem; color:#d68910; letter-spacing:1px; margin-bottom:10px;">3. ROUTE & PATH</div>
                <label style="font-size:0.85rem; font-weight:bold; color:#d68910;">Corridor</label>
                <select name="route_id" required style="width:100%; padding:8px; border:1px solid #f5c6cb; border-radius:4px; margin-bottom:10px;">
                    <?php foreach($routes as $r) { $sel = ($edit_data && $edit_data->route_id == $r->route_id) ? 'selected' : ''; echo "<option value='{$r->route_id}' $sel>{$r->route_name}</option>"; } ?>
                </select>

                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">Start</label>
                        <input type="text" name="start_loc" required list="loc_list" value="<?php echo ($edit_data) ? $edit_data->start_loc : ''; ?>" placeholder="Origin" style="width:100%; padding:8px; border:1px solid #f5c6cb; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">End</label>
                        <input type="text" name="end_loc" required list="loc_list" value="<?php echo ($edit_data) ? $edit_data->end_loc : ''; ?>" placeholder="Dest." style="width:100%; padding:8px; border:1px solid #f5c6cb; border-radius:4px;">
                    </div>
                </div>
                <datalist id="loc_list">
                    <option value="DIU Campus"><option value="Mirpur 10"><option value="Uttara"><option value="Dhanmondi"><option value="Tongi">
                </datalist>
            </div>

            <button type="submit" style="grid-column: span 3; background: <?php echo ($edit_data) ? '#007bff' : '#28a745'; ?>; color: white; border: none; padding: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 1rem;">
                <?php echo ($edit_data) ? '💾 Save Changes' : '✅ Assign Mission'; ?>
            </button>
        </form>
    </div>

    <h3 style="color: #2c3e50; margin-bottom: 20px; font-size:1.2rem;">🗂️ Active Transport Schedule</h3>

    <?php if (empty($grouped_schedule)): ?>
        <div style="text-align:center; padding:40px; background:white; border-radius:8px; color:#999;">No active schedules found.</div>
    <?php else: ?>
        <?php foreach ($grouped_schedule as $route_name => $directions): ?>
            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden;">
                <div style="background: #34495e; color: white; padding: 12px 20px;">
                    <h3 style="margin:0; font-size: 1.1rem;">🚌 <?php echo $route_name; ?></h3>
                </div>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px; border-right: 1px solid #eee;">
                        <div style="background: #e3f2fd; color: #0d47a1; padding: 8px; font-weight: bold; text-align: center; border-bottom: 1px solid #bbdefb;">⬇️ Inbound</div>
                        <?php if (isset($directions['inbound'])) foreach($directions['inbound'] as $a) dats_render_row($a, $edit_data); else echo '<div style="padding:20px; text-align:center; color:#ccc;">-</div>'; ?>
                    </div>
                    <div style="flex: 1; min-width: 300px;">
                        <div style="background: #e8f5e9; color: #1b5e20; padding: 8px; font-weight: bold; text-align: center; border-bottom: 1px solid #c8e6c9;">⬆️ Outbound</div>
                        <?php if (isset($directions['outbound'])) foreach($directions['outbound'] as $a) dats_render_row($a, $edit_data); else echo '<div style="padding:20px; text-align:center; color:#ccc;">-</div>'; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// --- JAVASCRIPT LOGIC: SMART SELECTION ---
var assignments = <?php echo json_encode($all_active_assignments); ?>;
document.getElementById('dats_time').addEventListener('change', function() {
    var selectedTime = this.value;
    var busSelect = document.getElementById('dats_bus');
    var drvSelect = document.getElementById('dats_driver');
    var msgDiv = document.getElementById('availability_msg');

    for(var i=0; i<busSelect.options.length; i++) { busSelect.options[i].disabled = false; busSelect.options[i].text = busSelect.options[i].text.replace(' ⛔ (BUSY)', ''); }
    for(var i=0; i<drvSelect.options.length; i++) { drvSelect.options[i].disabled = false; drvSelect.options[i].text = drvSelect.options[i].text.replace(' ⛔ (BUSY)', ''); }
    msgDiv.innerHTML = '';

    if(!selectedTime) return;

    var busyCount = 0;
    assignments.forEach(function(a) {
        if(a.departure_time === selectedTime) {
            for(var i=0; i<busSelect.options.length; i++) {
                if(busSelect.options[i].value == a.bus_id) {
                    busSelect.options[i].disabled = true;
                    busSelect.options[i].text += ' ⛔ (BUSY)';
                    busyCount++;
                }
            }
            for(var i=0; i<drvSelect.options.length; i++) {
                if(drvSelect.options[i].value == a.driver_id) {
                    drvSelect.options[i].disabled = true;
                    drvSelect.options[i].text += ' ⛔ (BUSY)';
                }
            }
        }
    });

    if(busyCount > 0) {
        msgDiv.innerHTML = '⚠️ Note: '+busyCount+' buses are currently busy at ' + selectedTime;
    }
});
</script>

<?php
// --- SAFETY CHECK FOR ROW RENDER FUNCTION ---
if (!function_exists('dats_render_row')) {
    function dats_render_row($a, $edit_data) {
        $bg = ($edit_data && $edit_data->assignment_id == $a->assignment_id) ? '#fff3cd' : 'white';
        ?>
        <div style="padding: 15px; border-bottom: 1px solid #f0f0f0; background: <?php echo $bg; ?>; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.8rem; color: #888; text-transform: uppercase;"><?php echo $a->start_loc; ?> ➝ <?php echo $a->end_loc; ?></div>
                <div style="font-size: 1.1rem; color: #333; font-weight: bold;">⏰ <?php echo $a->departure_time; ?></div>
                <div style="font-size: 0.9rem; color: #555;">🚌 <?php echo $a->bus_number; ?> (<?php echo $a->vendor_name; ?>)</div>
                <div style="font-size: 0.85rem; color: #555; margin-top: 4px;">
                    👤 <?php echo $a->driver_name; ?> 
                    <span style="cursor:pointer; margin-left:5px;" title="View Driver Info" 
                          onclick="alert('👤 DRIVER CARD\n\nName: <?php echo esc_js($a->driver_name); ?>\n📱 Phone: <?php echo esc_js($a->driver_phone); ?>')">
                        ℹ️
                    </span>
                </div>
                <div style="font-size: 0.8rem; color: #888; margin-top:2px;">Valid Until: <strong><?php echo $a->valid_to; ?></strong></div>
            </div>
            <div style="text-align: right;">
                <form method="post" style="display:inline-block; margin-bottom:5px;">
                    <input type="hidden" name="dats_action" value="extend_assignment">
                    <input type="hidden" name="assign_id" value="<?php echo $a->assignment_id; ?>">
                    <input type="hidden" name="current_valid_to" value="<?php echo $a->valid_to; ?>">
                    <button title="Extend for 1 Week" style="background:#e7f5ff; border:1px solid #007bff; color:#007bff; border-radius:4px; cursor:pointer; font-size:0.8rem; padding:4px 8px; font-weight:bold;">🔄 +7 Days</button>
                </form>
                <br>
                <a href="?edit_assign=<?php echo $a->assignment_id; ?>" style="color: #007bff; text-decoration: none; font-size: 0.85rem; margin-right: 10px;">Edit</a>
                <form method="post" onsubmit="return confirm('Revoke this schedule?');" style="display:inline;">
                    <input type="hidden" name="dats_action" value="delete_assignment">
                    <input type="hidden" name="assign_id" value="<?php echo $a->assignment_id; ?>">
                    <button style="background: none; border: none; color: #d63031; font-size: 0.85rem; cursor: pointer;">Revoke</button>
                </form>
            </div>
        </div>
        <?php
    }
}
?>
</body>
</html>