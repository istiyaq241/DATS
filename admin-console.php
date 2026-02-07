global $wpdb;
$wpdb->show_errors(); 

// 1. SECURITY
$admin_pin = "1234"; 

if (!current_user_can('manage_options')) {
    return '<div style="color: red;">⛔ Access Denied.</div>';
}

$table_name = 'dats_routes';
$message = "";

// 2. LOGIC: Handle Actions
if (isset($_POST['dats_action'])) {
    if ($_POST['security_pin'] !== $admin_pin) {
        $message = '<div style="background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom:15px;">❌ Incorrect PIN!</div>';
    } else {
        // DELETE LOGIC (UPDATED FOR route_id)
        if ($_POST['dats_action'] == 'delete') {
            if (!empty($_POST['bus_id'])) {
                // CHANGE 1: We use 'route_id' here now
                $wpdb->delete($table_name, array('route_id' => intval($_POST['bus_id'])));
                $message = '<div style="background: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom:15px;">🗑️ Route Deleted.</div>';
            }
        }
        // ADD LOGIC
        if ($_POST['dats_action'] == 'add') {
            $wpdb->insert($table_name, array(
                'route_name' => sanitize_text_field($_POST['route_name']),
                'start_point' => sanitize_text_field($_POST['start_point']),
                'end_point' => sanitize_text_field($_POST['end_point']),
                'departure_time' => sanitize_text_field($_POST['departure_time']),
                'total_seats' => intval($_POST['total_seats'])
            ));
            $message = '<div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom:15px;">✅ New Route Added.</div>';
        }
    }
}

// 3. FETCH DATA
$all_buses = $wpdb->get_results("SELECT * FROM dats_routes ORDER BY departure_time ASC");

// 4. THE INTERFACE
?>
<div style="font-family: sans-serif; max-width: 950px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <?php echo $message; ?>

    <h2 style="margin-top:0; color: #2c3e50;">🚌 DIU Fleet Management</h2>
    
    <form method="post" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 30px;">
        <input type="hidden" name="dats_action" value="add">
        
        <div><label>Route Name:</label>
        <select name="route_name" style="width:100%; padding:8px;">
            <option value="Mirpur Express">Mirpur Express</option>
            <option value="Uttara Shuttle">Uttara Shuttle</option>
            <option value="Dhanmondi Rider">Dhanmondi Rider</option>
            <option value="Tongi Service">Tongi Service</option>
            <option value="Campus Return">Campus Return Service</option>
        </select></div>

        <div><label>Starting Point:</label>
        <select name="start_point" style="width:100%; padding:8px;">
            <optgroup label="Mirpur"><option value="Mirpur 1">Mirpur 1</option><option value="Mirpur 10">Mirpur 10</option></optgroup>
            <optgroup label="Other"><option value="Uttara House Building">Uttara House Building</option><option value="Dhanmondi 32">Dhanmondi 32</option><option value="Tongi College Gate">Tongi College Gate</option><option value="DIU Campus">DIU Campus</option></optgroup>
        </select></div>

        <div><label>Destination:</label>
        <select name="end_point" style="width:100%; padding:8px;">
            <option value="DIU Campus">DIU Campus</option>
            <option value="Mirpur">Mirpur</option>
            <option value="Uttara">Uttara</option>
            <option value="Dhanmondi">Dhanmondi</option>
        </select></div>

        <div><label>Departure Time:</label>
        <select name="departure_time" style="width:100%; padding:8px;">
            <option value="07:00 AM">07:00 AM</option>
            <option value="08:30 AM">08:30 AM</option>
            <option value="01:00 PM">01:00 PM</option>
            <option value="04:20 PM">04:20 PM</option>
        </select></div>

        <div><label>Seats:</label><input type="number" name="total_seats" value="50" style="width:100%; padding:7px;"></div>
        <div><label style="color:red;">PIN:</label><input type="password" name="security_pin" required style="width:100%; padding:7px; border: 1px solid red;"></div>

        <button type="submit" style="grid-column: span 3; padding: 12px; background: #0073aa; color: white; border: none; font-weight: bold;">➕ Add New Route</button>
    </form>

    <hr>

    <h3 style="margin-top: 20px; color: #d9534f;">⚙️ Manage Existing Routes</h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff;">
        <thead>
            <tr style="background: #333; color: #fff; text-align: left;">
                <th style="padding:12px;">ID</th>
                <th style="padding:12px;">Route Info</th>
                <th style="padding:12px;">Time</th>
                <th style="padding:12px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_buses as $bus): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:12px; color: #888;">#<?php echo $bus->route_id; ?></td>
                
                <td style="padding:12px;">
                    <strong><?php echo esc_html($bus->route_name); ?></strong><br>
                    <small><?php echo esc_html($bus->start_point); ?> ➔ <?php echo esc_html($bus->end_point); ?></small>
                </td>
                <td style="padding:12px; font-weight:bold;"><?php echo esc_html($bus->departure_time); ?></td>
                <td style="padding:12px;">
                    <form method="post" onsubmit="return confirm('Delete this route?');" style="display:inline-flex; gap:5px;">
                        <input type="hidden" name="dats_action" value="delete">
                        
                        <input type="hidden" name="bus_id" value="<?php echo $bus->route_id; ?>">
                        
                        <input type="password" name="security_pin" placeholder="PIN" required style="width:50px; padding:5px; border:1px solid #ccc;">
                        <button type="submit" style="background:#d9534f; color:white; border:none; padding:5px 15px; border-radius:4px; cursor:pointer;">DELETE</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>