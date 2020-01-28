<?php
if (env('WEB_SERVER', 'amd64-ubuntu1804') == 'windows-64') {
    $binary_pdf = base_path('resources/wkhtmltopdf/wkhtmltopdf.exe');
} else {
    $binary_pdf = base_path('resources/wkhtmltopdf/wkhtmltopdf-' . env('WEB_SERVER'));
}

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    
    'pdf' => [
        'enabled' => true,
        'binary'  => $binary_pdf,
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    
    'image' => [
        'enabled' => false,
        'binary'  => null,
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
