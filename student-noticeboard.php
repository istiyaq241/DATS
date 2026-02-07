global $wpdb;

// 1. Fetch all routes
$results = $wpdb->get_results("SELECT * FROM dats_routes ORDER BY departure_time ASC");

// 2. Prepare categories
$to_campus = [];
$from_campus = [];
$special = [];

foreach ($results as $row) {
    if ($row->end_point == 'DIU Campus') {
        $to_campus[] = $row;
    } elseif ($row->start_point == 'DIU Campus') {
        $from_campus[] = $row;
    } else {
        $special[] = $row;
    }
}

// 3. Helper function to render a table
function dats_render_table($title, $data, $header_color) {
    if (empty($data)) return '';
    
    $html = '<h3 style="color: '.$header_color.'; margin-top: 30px; border-bottom: 2px solid '.$header_color.';">'.$title.'</h3>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #fff;">';
    $html .= '<tr style="background: '.$header_color.'; color: white; text-align: left;">';
    $html .= '<th style="padding: 10px;">Route</th><th style="padding: 10px;">Time</th><th style="padding: 10px;">Details</th>';
    $html .= '</tr>';
    
    foreach ($data as $bus) {
        $html .= '<tr style="border-bottom: 1px solid #eee;">';
        $html .= '<td style="padding: 10px; font-weight: bold;">'.esc_html($bus->route_name).'</td>';
        $html .= '<td style="padding: 10px; color: #d35400; font-weight: bold;">'.esc_html($bus->departure_time).'</td>';
        $html .= '<td style="padding: 10px; font-size: 0.9em; color: #666;">'.esc_html($bus->start_point).' ➔ '.esc_html($bus->end_point).'</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

// 4. Output the organized views
echo '<div style="font-family: sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; background: #fdfdfd; border-radius: 10px;">';
echo '<h2 style="text-align: center; color: #333;">DIU Bus Schedule Dashboard</h2>';

echo dats_render_table('➡️ Morning: To Campus', $to_campus, '#2980b9');
echo dats_render_table('⬅️ Evening: From Campus', $from_campus, '#27ae60');
echo dats_render_table('🔄 Special/Inter-City Routes', $special, '#8e44ad');

if (empty($results)) {
    echo '<p style="text-align: center; padding: 20px;">No bus data available at the moment.</p>';
}

echo '</div>';