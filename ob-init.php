<?php
/**
 * Auto-prepended to every PHP request via .htaccess (see the
 * "OUTPUT BUFFERING" section there for why this exists).
 *
 * Starts an explicit, unlimited output buffer before any page's own
 * code runs, so that a later session_start() call (e.g. in Navbar.php,
 * which typically runs after a page's own <head> HTML has already been
 * echoed) can still send its Set-Cookie header — headers can be sent
 * at any point up until the buffer is actually flushed, which normally
 * happens automatically at the end of the request.
 *
 * This file must never produce any visible output itself.
 */
ob_start();
