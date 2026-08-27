<?php
// Cleanup script
if (file_exists(__FILE__)) {
    unlink(__FILE__);
}
echo "Cleaned up";
