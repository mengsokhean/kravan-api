<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

   'allowed_origins' => [
    'https://kravan-pictures.vercel.app', // ដាក់ Link Vercel របស់បងនៅទីនេះ
    'https://kravanpictures.com',         // បើបងមាន Domain ផ្ទាល់ខ្លួន
    'http://localhost:3000',             // សម្រាប់តេស្តក្នុងម៉ាស៊ីន
],
    

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];