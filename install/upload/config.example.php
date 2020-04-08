<?php
// HTTP
define('HTTP_SERVER', getenv('CATALOG_HTTP_SERVER'));
define('HTTP_CATALOG', getenv('CATALOG_HTTP_SERVER'));

// HTTPS
define('HTTPS_SERVER', getenv('CATALOG_HTTPS_SERVER'));
define('HTTPS_CATALOG', getenv('CATALOG_HTTPS_SERVER'));

// DIR
define('DIR_APPLICATION', getenv('CATALOG_DIR_APPLICATION'));
define('DIR_SYSTEM', getenv('DIR_SYSTEM'));
define('DIR_IMAGE', getenv('DIR_IMAGE'));
define('DIR_STORAGE', getenv('DIR_STORAGE'));
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', getenv('DIR_CACHE'));
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', getenv('DIR_LOGS'));
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mpdo');
define('DB_HOSTNAME', getenv('MYSQL_HOST'));
define('DB_USERNAME', getenv('MYSQL_USERNAME'));
define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
define('DB_DATABASE', getenv('DB_NAME'));
define('DB_PORT', getenv('MYSQL_PORT'));
define('DB_PREFIX', 'oc_');

//AWS region
define('AWS_REGION', getenv('AWS_REGION'));

// S3 buckets
define('UPLOADED_IMAGE_BUCKET', getenv('UPLOADED_IMAGE_BUCKET'));
