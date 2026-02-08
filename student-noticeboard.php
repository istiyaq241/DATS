/* Template Name: DATS Student Board */

global $wpdb;

// --- 1. DESIGN LOGIC: GET BACKGROUND IMAGE ---
// This grabs the "Featured Image" you set in the WordPress Page Editor
$bg_image = get_the_post_thumbnail_url(); 
if (!$bg_image) {
    // Fallback Blue/Cyan Gradient
    $bg_style = "background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);";
} else {
    $bg_style = "background-image: url('" . esc_url($bg_image) . "'); background-size: cover; background-position: center;";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60"> 
    <title>Student Transport Board</title>
</head>
<body style="background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif;">

<style>
    /* --- FIX: HIDE DEFAULT WORDPRESS THEME ELEMENTS --- */
    /* This stops the image and title from appearing twice */
    .entry-title, .page-title, h1.wp-block-post-title,
    .post-thumbnail, .wp-block-post-featured-image, .featured-image { 
        display: none !important; 
    }
    
    /* --- STUDENT BANNER --- */
    .dats-student-banner {
        <?php echo $bg_style; ?>
        height: 250px;
        border-radius: 0 0 20px 20px; /* Rounded bottom */
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        margin-bottom: 40px;
        overflow: hidden;
    }
    
    /* Overlay for text readability */
    .dats-student-banner::before {
        content: '';
        position: absolute;
        top:0; left:0; right:0; bottom:0;
        background: rgba(0,0,0,0.3); /* Slight dark tint */
    }

    /* GLASS TITLE BOX */
    .dats-student-title {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        padding: 20px 50px;
        border-radius: 50px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        text-align: center;
        backdrop-filter: blur(5px);
        z-index: 10;
        border: 1px solid rgba(255,255,255,0.5);
    }

    .dats-student-title h1 {
        margin: 0;
        color: #2c3e50;
        font-family: 'Segoe UI', sans-serif;
        font-weight: 900;
        font-size: 2.2rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    /* SYSTEM STATUS SUBTITLE */
    .dats-subtitle {
        color: #27ae60;
        font-weight: bold;
        font-size: 0.9rem;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* PULSE ANIMATION DOT */
    .dats-dot {
        height: 10px; width: 10px;
        background-color: #27ae60;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
</style>

<div class="dats-student-banner">
    <div class="dats-student-title">
        <h1>CAMPUS NETWORK // LIVE</h1>
        <div class="dats-subtitle"><span class="dats-dot"></span> Real-time Schedule</div>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">

    <?php
    // --- 2. FETCH DATA (FILTERED FOR LIVE STATUS) ---
    // Only fetch buses where time is > CURTIME()
    $query = "
        SELECT 
            a.departure_time, a.start_loc, a.end_loc,
            r.route_name,
            b.bus_number, b.vendor_name
        FROM dats_assignments a
        JOIN dats_routes r ON a.route_id = r.route_id
        JOIN dats_buses b ON a.bus_id = b.bus_id
        WHERE CURDATE() BETWEEN a.valid_from AND a.valid_to
        AND STR_TO_DATE(a.departure_time, '%h:%i %p') > CURTIME()
        ORDER BY r.route_name ASC, STR_TO_DATE(a.departure_time, '%h:%i %p') ASC
    ";

    $results = $wpdb->get_results($query);

    // --- 3. GROUP BY ROUTE & DIRECTION ---
    $schedule = [];

    if ($results) {
        foreach ($results as $row) {
            $dest = strtolower($row->end_loc);
            if (strpos($dest, 'campus') !== false || strpos($dest, 'dsc') !== false || strpos($dest, 'university') !== false) {
                $dir = 'inbound';
            } else {
                $dir = 'outbound';
            }
            $schedule[$row->route_name][$dir][] = $row;
        }
    }

    // --- 4. HELPER FUNCTION (WITH SAFETY CHECK) ---
    if (!function_exists('dats_render_bus_list')) {
        function dats_render_bus_list($buses, $colorTheme) {
            if (empty($buses)) {
                return '<div style="padding: 15px; color: #aaa; text-align: center; font-style: italic;">No active buses</div>';
            }
            $html = '';
            foreach ($buses as $bus) {
                $html .= '<div style="background: #fff; padding: 12px 15px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">';
                $html .= '<div>';
                $html .= '<div style="font-weight: bold; font-size: 1.1rem; color: #333;">' . esc_html($bus->departure_time) . '</div>';
                $html .= '<div style="font-size: 0.85rem; color: #666;">' . esc_html($bus->start_loc) . ' ➝ ' . esc_html($bus->end_loc) . '</div>';
                $html .= '</div>';
                $html .= '<span style="background: '.$colorTheme.'; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; animation: pulse 1.5s infinite;">● LIVE</span>';
                $html .= '</div>';
            }
            return $html;
        }
    }

    // --- 5. DISPLAY THE BOARD ---
    if (empty($schedule)) {
        echo '<div style="text-align: center; padding: 50px; background: white; border-radius: 8px; color: #777;"><h3>🚫 No active buses right now.</h3><p>Please check back later.</p></div>';
    } else {
        foreach ($schedule as $route_name => $directions) {
            echo '<div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 30px;">';
            
            echo '<div style="background: #34495e; color: white; padding: 15px 20px; font-size: 1.2rem; font-weight: bold; letter-spacing: 0.5px;">';
            echo '🚌 '.esc_html($route_name);
            echo '</div>';
            
            echo '<div style="display: flex; flex-wrap: wrap;">';
            
            echo '<div style="flex: 1; min-width: 300px; border-right: 1px solid #eee;">';
            echo '<div style="background: #e3f2fd; color: #0d47a1; padding: 10px; font-weight: bold; text-align: center; border-bottom: 1px solid #bbdefb;">⬇️ To Campus (Inbound)</div>';
            echo dats_render_bus_list($directions['inbound'] ?? [], '#2196F3');
            echo '</div>';
            
            echo '<div style="flex: 1; min-width: 300px;">';
            echo '<div style="background: #e8f5e9; color: #1b5e20; padding: 10px; font-weight: bold; text-align: center; border-bottom: 1px solid #c8e6c9;">⬆️ From Campus (Outbound)</div>';
            echo dats_render_bus_list($directions['outbound'] ?? [], '#4CAF50');
            echo '</div>';
            
            echo '</div>'; 
            echo '</div>'; 
        }
    }
    ?>

</div>
</body>
</html>