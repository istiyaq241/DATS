global $wpdb;

// --- 1. FETCH DATA ---
// We fetch active assignments, joined with Route names
$query = "
    SELECT 
        a.departure_time, a.start_loc, a.end_loc,
        r.route_name,
        b.bus_number, b.vendor_name
    FROM dats_assignments a
    JOIN dats_routes r ON a.route_id = r.route_id
    JOIN dats_buses b ON a.bus_id = b.bus_id
    WHERE CURDATE() BETWEEN a.valid_from AND a.valid_to
    ORDER BY r.route_name ASC, STR_TO_DATE(a.departure_time, '%h:%i %p') ASC
";

$results = $wpdb->get_results($query);

// --- 2. GROUP BY ROUTE & DIRECTION ---
$schedule = [];

foreach ($results as $row) {
    // Determine Direction
    $dest = strtolower($row->end_loc);
    if (strpos($dest, 'campus') !== false || strpos($dest, 'dsc') !== false || strpos($dest, 'university') !== false) {
        $dir = 'inbound';
    } else {
        $dir = 'outbound';
    }
    
    // Grouping: [Route Name] -> [Direction] -> [List of Buses]
    $schedule[$row->route_name][$dir][] = $row;
}

// --- 3. RENDER FUNCTION ---
function dats_render_bus_list($buses, $color_class) {
    if (empty($buses)) return '<div style="color:#aaa; font-style:italic; padding:10px;">- No buses -</div>';
    
    $html = '';
    foreach ($buses as $bus) {
        $html .= '<div style="background:white; border-bottom:1px solid #eee; padding:10px; display:flex; justify-content:space-between; align-items:center;">';
        
        // Time & Path
        $html .= '<div>';
        $html .= '<div style="font-weight:bold; font-size:1.1rem; color:#333;">'.$bus->departure_time.'</div>';
        $html .= '<div style="font-size:0.8rem; color:#666; margin-top:2px;">'.esc_html($bus->start_loc).' ➝ '.esc_html($bus->end_loc).'</div>';
        $html .= '</div>';
        
        // Bus Number Badge
        $html .= '<div style="text-align:right;">';
        $html .= '<span style="background:#f0f0f0; color:#333; padding:2px 6px; border-radius:4px; font-weight:bold; font-size:0.85rem;">'.esc_html($bus->bus_number).'</span>';
        $html .= '<div style="font-size:0.75rem; color:#888; margin-top:2px;">'.esc_html($bus->vendor_name).'</div>';
        $html .= '</div>';
        
        $html .= '</div>';
    }
    return $html;
}

// --- 4. DISPLAY LAYOUT ---
echo '<div style="font-family: \'Segoe UI\', sans-serif; max-width: 1100px; margin: 0 auto; padding: 20px;">';

// Header
echo '<div style="text-align:center; margin-bottom:40px;">';
echo '<h2 style="color:#2c3e50; font-size:2rem; margin-bottom:5px;">📅 Today\'s Transport Schedule</h2>';
echo '<p style="color:#7f8c8d; font-size:1rem;">'.date('l, F j, Y').'</p>';
echo '</div>';

if (empty($schedule)) {
    echo '<div style="text-align:center; padding:60px; background:white; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.05); color:#7f8c8d;">';
    echo '<div style="font-size:3rem; margin-bottom:10px;">💤</div>';
    echo '<h3 style="margin:0;">No buses active today.</h3>';
    echo '</div>';
} else {
    
    // LOOP THROUGH EACH ROUTE
    foreach ($schedule as $route_name => $directions) {
        echo '<div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 30px;">';
        
        // Route Header
        echo '<div style="background: #34495e; color: white; padding: 15px 20px; font-size: 1.2rem; font-weight: bold; letter-spacing: 0.5px;">';
        echo '🚌 '.esc_html($route_name);
        echo '</div>';
        
        // Columns Container
        echo '<div style="display: flex; flex-wrap: wrap;">';
        
        // COLUMN 1: INBOUND (To Campus)
        echo '<div style="flex: 1; min-width: 300px; border-right: 1px solid #eee;">';
        echo '<div style="background: #e3f2fd; color: #0d47a1; padding: 10px; font-weight: bold; text-align: center; border-bottom: 1px solid #bbdefb;">⬇️ To Campus (Inbound)</div>';
        echo dats_render_bus_list($directions['inbound'] ?? [], 'blue');
        echo '</div>';
        
        // COLUMN 2: OUTBOUND (From Campus)
        echo '<div style="flex: 1; min-width: 300px;">';
        echo '<div style="background: #e8f5e9; color: #1b5e20; padding: 10px; font-weight: bold; text-align: center; border-bottom: 1px solid #c8e6c9;">⬆️ From Campus (Outbound)</div>';
        echo dats_render_bus_list($directions['outbound'] ?? [], 'green');
        echo '</div>';
        
        echo '</div>'; // End Columns
        echo '</div>'; // End Route Card
    }
}

echo '</div>';