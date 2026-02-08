/* Template Name: DATS Home Page */

// --- GET BACKGROUND IMAGE ---
// Uses the "Featured Image" of the page, or falls back to a cool tech-transport gradient
$bg_image = get_the_post_thumbnail_url();
if (!$bg_image) {
    $hero_style = "background: linear-gradient(135deg, #1c1e21 0%, #34495e 100%);";
} else {
    $hero_style = "background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('" . esc_url($bg_image) . "'); background-size: cover; background-position: center;";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATS - Campus Transport</title>
</head>
<body style="margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6;">

<style>
    /* HIDE DEFAULT WP THEME ELEMENTS */
    .entry-title, .page-title, h1.wp-block-post-title, header, footer { display: none !important; }
    
    /* GLOBAL LAYOUT */
    body, html { height: 100%; margin: 0; }
    
    /* HERO SECTION */
    .dats-hero {
        <?php echo $hero_style; ?>
        height: 85vh; /* Takes up 85% of the screen */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 20px;
        position: relative;
    }

    .dats-hero h1 {
        font-size: 4rem;
        margin: 0;
        font-weight: 800;
        letter-spacing: -1px;
        text-transform: uppercase;
    }

    .dats-hero p {
        font-size: 1.5rem;
        font-weight: 300;
        margin-top: 10px;
        margin-bottom: 40px;
        color: #ecf0f1;
        max-width: 600px;
    }

    /* ACTION BUTTONS */
    .dats-btn-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .dats-btn {
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1rem;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dats-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .btn-student {
        background-color: #27ae60; /* Green */
        color: white;
        border: 2px solid #27ae60;
    }

    .btn-admin {
        background-color: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.8);
    }
    .btn-admin:hover {
        background-color: white;
        color: #333;
    }

    /* FEATURES SECTION */
    .dats-features {
        display: flex;
        justify-content: center;
        gap: 30px;
        padding: 60px 20px;
        background: white;
        flex-wrap: wrap;
        margin-top: -50px; /* Overlap effect */
        position: relative;
        z-index: 10;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .feature-card {
        flex: 1;
        min-width: 250px;
        text-align: center;
        padding: 20px;
    }

    .feature-icon { font-size: 3rem; margin-bottom: 15px; display: block; }
    .feature-title { font-weight: bold; font-size: 1.2rem; color: #2c3e50; margin-bottom: 10px; }
    .feature-desc { color: #7f8c8d; line-height: 1.6; font-size: 0.95rem; }

    /* FOOTER */
    .dats-footer {
        text-align: center;
        padding: 40px;
        color: #95a5a6;
        font-size: 0.9rem;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .dats-hero h1 { font-size: 2.5rem; }
        .dats-hero p { font-size: 1.1rem; }
        .dats-features { margin-top: 0; border-radius: 0; box-shadow: none; }
    }
</style>

<div class="dats-hero">
    <h1>DATS</h1>
    <p>The smartest way to get around campus. Real-time schedules, live tracking, and reliable transport.</p>
    
    <div class="dats-btn-container">
        <a href="/dats/bus-schedule/" class="dats-btn btn-student">
            🚍 Check Bus Schedule
        </a>
       <a href="/dats/fleet-management-console/" class="dats-btn btn-admin">
            🔐 Staff Login
        </a>
    </div>
</div>

<div class="dats-features">
    <div class="feature-card">
        <span class="feature-icon">🕒</span>
        <div class="feature-title">Real-Time Updates</div>
        <div class="feature-desc">Never guess when the bus is coming. See live departure times updated by our dispatch team.</div>
    </div>
    <div class="feature-card">
        <span class="feature-icon">🛡️</span>
        <div class="feature-title">Safety First</div>
        <div class="feature-desc">Authorized drivers and tracked vehicles ensure a secure journey to and from the university.</div>
    </div>
    <div class="feature-card">
        <span class="feature-icon">🗺️</span>
        <div class="feature-title">Campus Coverage</div>
        <div class="feature-desc">Comprehensive routes covering Mirpur, Uttara, Dhanmondi, and all major student hubs.</div>
    </div>
</div>

<div class="dats-footer">
    &copy; <?php echo date("Y"); ?> Daffodil Smart Transport System. All rights reserved.
</div>

</body>
</html>