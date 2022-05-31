<?php

return [
    /*
     * Define the key that pack the data
     */
    'pack' => 'data',

    /*
     * Define the key that pagination pack the data
     */
    'pagination_pack' => 'meta',

    /*
     * Define the pagination data keys
     */
    'pagination_info' => [
        'current_page' => 'current_page',
        'last_page'    => 'last_page',
        'per_page'     => 'per_page',
        'total'        => 'total'
    ],

    /*
     * Define the content of the additional response
     */
    'additional' => [
        'code' => 1,
        'time' => time()
    ],
];