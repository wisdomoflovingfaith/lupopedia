<?php
require_once(dirname(__FILE__) . '/admin_bootstrap.php');

function channel_admin_page_start($title, $subtitle) {
    channel_admin_security_headers();
    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"en\">\n";
    echo "<head>\n";
    echo "  <meta charset=\"UTF-8\">\n";
    echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo "  <title>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</title>\n";
    echo "  <link rel=\"stylesheet\" href=\"../assets/css/channels_admin.css\">\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "  <div class=\"channel-admin-card\" style=\"min-height: 100vh; border-radius: 0; background: transparent; box-shadow: none;\">\n";
    echo "    <div class=\"channel-admin-kicker\">" . htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') . "</div>\n";
    echo "    <h1 style=\"margin-top: 6px; margin-bottom: 16px;\">" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</h1>\n";
}

function channel_admin_page_end() {
    echo "  </div>\n";
    echo "</body>\n";
    echo "</html>\n";
}
?>
