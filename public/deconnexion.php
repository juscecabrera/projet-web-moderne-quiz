<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
quiz_require_post_token();
quiz_sign_out();
