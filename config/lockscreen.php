<?php

return [
    /**
     * Time to live
     * Seconds to wait after last activity before locking the account.
     */
    'ttl' => 2 * 60 * 60, // 2 hours

    /**
     * Append middleware to the web middleware group.
     * If false, you should add the middleware 'lockscreen' manually to the routes.
     */
    'append_middleware' => true,
];
