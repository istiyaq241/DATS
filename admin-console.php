global $wpdb;
$wpdb->show_errors(); 

// --- 1. SECURITY & FRONTEND LOGIN ---
if (!is_user_logged_in()) {
    ?>
    <div style="font-family: 'Segoe UI', sans-serif; background: #f0f2f5; min-height: 600px; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; width: 100%; max-width: 400px; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 10px;">🚍</div>
            <h2 style="color: #2c3e50; margin: 0 0 5px 0;">Fleet Command</h2>
            <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 30px;">Authorized Personnel Only</p>
            <style>
                .dats-login-form p { margin-bottom: 15px; text-align: left; }
                .dats-login-form label { display: block; font-weight: 600; font-size: 0.85rem; color: #34495e; margin-bottom: 5px; }
                .dats-login-form input[type="text"], .dats-login-form input[type="password"] { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; box-sizing: border-box; }
                .dats-login-form input[type="text"]:focus, .dats-login-form input[type="password"]:focus { border-color: #0073aa; outline: none; }
                .dats-login-form input[type="submit"] { background: #0073aa; color: white; border: none; padding: 12px 0; width: 100%; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 1rem; margin-top: 10px; }
                .dats-login-form input[type="submit"]:hover { background: #005a87; }
            </style>
            <div class="dats-login-form"><?php wp_login_form(array('redirect' => get_permalink())); ?></div>
        </div>
    </div>
    <?php return; 
}

$current_user = wp_get_current_user();
$message = "";

// --- 2. LOGIC: ASSIGN, UPDATE, DELETE ---
if (isset($_POST['dats_action'])) {
    
    // CASE 1: DELETE (Check this first!)
    if ($_POST['dats_action'] == 'delete_assignment') {
        $wpdb->delete('dats_assignments', array('assignment_id' => intval($_POST['assign_id'])));
        $message = '<div style="background:#fff3cd; color:#856404; padding:15px; border-radius:8px; margin-bottom:20px;">🗑️ Assignment Revoked.</div>';
    }
    
    // CASE 2: ASSIGN or UPDATE (Only gather data here)
    elseif ($_POST['dats_action'] == 'assign' || $_POST['dats_action'] == 'update_assignment') {
        
        $data = array(
            'bus_id'         => intval($_POST['bus_id']),
            'driver_id'      => intval($_POST['driver_id']),
            'route_id'       => intval($_POST['route_id']),
            'start_loc'      => sanitize_text_field($_POST['start_loc']),
            'end_loc'        => sanitize_text_field($_POST['end_loc']),
            'departure_time' => sanitize_text_field($_POST['departure_time']),
            'valid_from'     => sanitize_text_field($_POST['valid_from']),
            'valid_to'       => sanitize_text_field($_POST['valid_to']),
            'assigned_by'    => $current_user->ID
        );

        if ($_POST['dats_action'] == 'assign') {
            $wpdb->insert('dats_assignments', $data);
            $message = '<div style="background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px;">✅ Mission Assigned.</div>';
        }
        
        if ($_POST['dats_action'] == 'update_assignment') {
            $wpdb->update('dats_assignments', $data, array('assignment_id' => intval($_POST['assign_id'])));
            $message = '<div style="background:#cce5ff; color:#004085; padding:15px; border-radius:8px; margin-bottom:20px;">🔄 Schedule Updated.</div>';
        }
    }
}

// --- 3. FETCH DATA ---
$edit_data = null;
if (isset($_GET['edit_assign'])) {
    $edit_data = $wpdb->get_row("SELECT * FROM dats_assignments WHERE assignment_id = ".intval($_GET['edit_assign']));
}

$buses   = $wpdb->get_results("SELECT * FROM dats_buses WHERE status='active'");
$drivers = $wpdb->get_results("SELECT * FROM dats_drivers");

// FILTER: HIDE 'Campus Return'
$routes  = $wpdb->get_results("SELECT * FROM dats_routes WHERE route_name NOT LIKE '%Campus Return%'"); 

// --- 4. DATA FETCHING (WITH TIME SORT) ---
$raw_assignments = $wpdb->get_results("
    SELECT a.*, b.bus_number, b.vendor_name, d.name as driver_name, d.phone as driver_phone, r.route_name, u.display_name as admin_name
    FROM dats_assignments a
    JOIN dats_buses b ON a.bus_id = b.bus_id
    JOIN dats_drivers d ON a.driver_id = d.driver_id
    JOIN dats_routes r ON a.route_id = r.route_id
    LEFT JOIN wp_users u ON a.assigned_by = u.ID
    ORDER BY r.route_name ASC, STR_TO_DATE(a.departure_time, '%h:%i %p') ASC
");

// GROUPING ALGORITHM
$grouped_schedule = [];
foreach ($raw_assignments as $item) {
    if (strpos(strtolower($item->end_loc), 'campus') !== false || strpos(strtolower($item->end_loc), 'dsc') !== false) {
        $direction = 'inbound';
    } else {
        $direction = 'outbound';
    }
    $grouped_schedule[$item->route_name][$direction][] = $item;
}

function dats_sel($current, $val) { if($current == $val) echo 'selected'; }
?>

<div style="font-family: 'Segoe UI', sans-serif; max-width: 1200px; margin: 0 auto; background: #f4f6f9; padding: 20px; border-radius: 10px;">
    
    <?php echo $message; ?>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; background:white; padding:15px 20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
        <div><h2 style="margin:0; color:#2c3e50; font-size:1.4rem;"><?php echo ($edit_data) ? '✏️ Modify Schedule' : '🕹️ Mission Control'; ?></h2></div>
        <div style="text-align:right; font-size:0.9em; color:#666;">
            <div>Logged in as: <strong style="color:#333;"><?php echo $current_user->display_name; ?></strong></div>
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" style="color:#d63031; text-decoration:none; font-weight:bold;">🛑 Log Out</a>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid <?php echo ($edit_data) ? '#007bff' : '#28a745'; ?>; margin-bottom: 40px;">
        <form method="post" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <input type="hidden" name="dats_action" value="<?php echo ($edit_data) ? 'update_assignment' : 'assign'; ?>">
            <?php if($edit_data): ?><input type="hidden" name="assign_id" value="<?php echo $edit_data->assignment_id; ?>"><?php endif; ?>

            <div style="background:#f9f9f9; padding:15px; border-radius:8px; border:1px solid #eee;">
                <div style="font-weight:bold; font-size:0.75rem; color:#aaa; letter-spacing:1px; margin-bottom:10px;">RESOURCES</div>
                <label style="font-size:0.85rem; font-weight:bold;">Bus</label>
                <select name="bus_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin-bottom:10px;">
                    <?php foreach($buses as $b) { $sel = ($edit_data && $edit_data->bus_id == $b->bus_id) ? 'selected' : ''; echo "<option value='{$b->bus_id}' $sel>{$b->bus_number} ({$b->vendor_name})</option>"; } ?>
                </select>
                <label style="font-size:0.85rem; font-weight:bold;">Driver</label>
                <select name="driver_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <?php foreach($drivers as $d) { $sel = ($edit_data && $edit_data->driver_id == $d->driver_id) ? 'selected' : ''; echo "<option value='{$d->driver_id}' $sel>{$d->name}</option>"; } ?>
                </select>
            </div>

            <div style="background:#e3f2fd; padding:15px; border-radius:8px; border:1px solid #bbdefb;">
                <div style="font-weight:bold; font-size:0.75rem; color:#0d47a1; letter-spacing:1px; margin-bottom:10px;">ROUTE & PATH</div>
                
                <label style="font-size:0.85rem; font-weight:bold; color:#0d47a1;">Corridor (Group)</label>
                <select name="route_id" required style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px; margin-bottom:10px;">
                    <?php foreach($routes as $r) { $sel = ($edit_data && $edit_data->route_id == $r->route_id) ? 'selected' : ''; echo "<option value='{$r->route_id}' $sel>{$r->route_name}</option>"; } ?>
                </select>

                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">From</label>
                        <input type="text" name="start_loc" required list="loc_list" value="<?php echo ($edit_data) ? $edit_data->start_loc : ''; ?>" placeholder="Origin" style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="font-size:0.85rem; font-weight:bold;">To</label>
                        <input type="text" name="end_loc" required list="loc_list" value="<?php echo ($edit_data) ? $edit_data->end_loc : ''; ?>" placeholder="Destination" style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px;">
                    </div>
                </div>
                <datalist id="loc_list">
                    <option value="DIU Campus">
                    <option value="Mirpur 10">
                    <option value="Uttara">
                    <option value="Dhanmondi">
                    <option value="Tongi">
                </datalist>

                <label style="font-size:0.85rem; font-weight:bold; margin-top:10px; display:block;">Time</label>
                <select name="departure_time" style="width:100%; padding:8px; border:1px solid #90caf9; border-radius:4px;">
                    <?php $cur = ($edit_data) ? $edit_data->departure_time : ''; ?>
                    <option <?php dats_sel($cur, '07:00 AM'); ?>>07:00 AM</option>
                    <option <?php dats_sel($cur, '08:30 AM'); ?>>08:30 AM</option>
                    <option <?php dats_sel($cur, '01:00 PM'); ?>>01:00 PM</option>
                    <option <?php dats_sel($cur, '01:30 PM'); ?>>01:30 PM</option>
                    <option <?php dats_sel($cur, '04:20 PM'); ?>>04:20 PM</option>
                    <option <?php dats_sel($cur, '06:10 PM'); ?>>06:10 PM</option>
                </select>
            </div>

            <div style="background:#fff8e1; padding:15px; border-radius:8px; border:1px solid #ffe0b2;">
                <div style="font-weight:bold; font-size:0.75rem; color:#d68910; letter-spacing:1px; margin-bottom:10px;">VALIDITY</div>
                <input type="date" name="valid_from" required value="<?php echo ($edit_data) ? $edit_data->valid_from : date('Y-m-d'); ?>" style="width:100%; padding:9px; border:1px solid #f5c6cb; border-radius:5px;">
                <input type="date" name="valid_to" required value="<?php echo ($edit_data) ? $edit_data->valid_to : date('Y-m-d', strtotime('+1 month')); ?>" style="width:100%; padding:9px; margin-top:10px; border:1px solid #f5c6cb; border-radius:5px;">
            </div>

            <button type="submit" style="grid-column: span 3; background: <?php echo ($edit_data) ? '#007bff' : '#28a745'; ?>; color: white; border: none; padding: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 1rem;">
                <?php echo ($edit_data) ? '💾 Save Changes' : '✅ Assign Mission'; ?>
            </button>
        </form>
    </div>

    <h3 style="color: #2c3e50; margin-bottom: 20px; font-size:1.2rem;">🗂️ Active Transport Schedule (Spring-2026 Style)</h3>

    <?php if (empty($grouped_schedule)): ?>
        <div style="text-align:center; padding:40px; background:white; border-radius:8px; color:#999;">No active schedules found.</div>
    <?php else: ?>

        <?php foreach ($grouped_schedule as $route_name => $directions): ?>
            
            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden;">
                <div style="background: #34495e; color: white; padding: 12px 20px;">
                    <h3 style="margin:0; font-size: 1.1rem;">🚌 Corridor: <?php echo $route_name; ?></h3>
                </div>

                <div style="display: flex; flex-wrap: wrap;">
                    
                    <div style="flex: 1; min-width: 300px; border-right: 1px solid #eee;">
                        <div style="background: #e3f2fd; color: #0d47a1; padding: 8px 15px; font-weight: bold; font-size: 0.9rem; text-align: center; border-bottom: 1px solid #bbdefb;">
                            ⬇️ Inbound (To Campus/DSC)
                        </div>
                        <?php 
                        if (isset($directions['inbound'])) {
                            foreach($directions['inbound'] as $a) { dats_render_row($a, $edit_data); }
                        } else {
                            echo '<div style="padding:20px; text-align:center; color:#aaa; font-style:italic;">-</div>';
                        }
                        ?>
                    </div>

                    <div style="flex: 1; min-width: 300px;">
                        <div style="background: #e8f5e9; color: #1b5e20; padding: 8px 15px; font-weight: bold; font-size: 0.9rem; text-align: center; border-bottom: 1px solid #c8e6c9;">
                            ⬆️ Outbound / Other
                        </div>
                        <?php 
                        if (isset($directions['outbound'])) {
                            foreach($directions['outbound'] as $a) { dats_render_row($a, $edit_data); }
                        } else {
                            echo '<div style="padding:20px; text-align:center; color:#aaa; font-style:italic;">-</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
function dats_render_row($a, $edit_data) {
    $bg = ($edit_data && $edit_data->assignment_id == $a->assignment_id) ? '#fff3cd' : 'white';
    ?>
    <div style="padding: 15px; border-bottom: 1px solid #f0f0f0; background: <?php echo $bg; ?>; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px;">
                <?php echo $a->start_loc; ?> ➝ <?php echo $a->end_loc; ?>
            </div>
            <div style="font-size: 1.1rem; color: #333; font-weight: bold; margin: 2px 0;">
                ⏰ <?php echo $a->departure_time; ?>
            </div>
            <div style="font-size: 0.9rem; color: #555;">
                <span style="font-weight: bold; background: #eee; padding: 2px 6px; border-radius: 4px;"><?php echo $a->bus_number; ?></span> 
                <?php echo $a->vendor_name; ?>
            </div>
            <div style="font-size: 0.8rem; color: #888; margin-top: 5px;">
                👤 <?php echo $a->driver_name; ?> (<?php echo $a->driver_phone; ?>)
            </div>
        </div>
        <div style="text-align: right;">
            <a href="?edit_assign=<?php echo $a->assignment_id; ?>" style="color: #007bff; text-decoration: none; font-size: 0.85rem; font-weight: bold; margin-right: 10px;">Edit</a>
            <form method="post" onsubmit="return confirm('Revoke this schedule?');" style="display:inline;">
                <input type="hidden" name="dats_action" value="delete_assignment">
                <input type="hidden" name="assign_id" value="<?php echo $a->assignment_id; ?>">
                <button style="background: none; border: none; color: #d63031; font-size: 0.85rem; cursor: pointer;">✕</button>
            </form>
        </div>
    </div>
    <?php
}
?>