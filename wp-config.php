<?php

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'example');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'bU)~(2wsOg{Cs-1s7rO7E+&N1>/+o]%rI{8Ax@h<m4modV1G$);3+y{|>m1sr:(J');
define('SECURE_AUTH_KEY',  'kgDZu`x?@PBl$GLFaVSCQh}i-iE$<|KQW{[4J#%`gAtRWm+?0kD3Pn#pbak_e=0P');
define('LOGGED_IN_KEY',    'G9PP4*9=a}*V+ Kkp0k.!:/lfNok4IaHJa/,-NW8g:}S^yVgZ4@-+d$Hci*e2^Hw');
define('NONCE_KEY',        '![|w1J7pL4Q{V3W+FdLS+Ex,1wo/`I|$|=1@>W&]qKkV.=uO`+YkmY2<G{TQ`;:Y');
define('AUTH_SALT',        '%.D44U-vCS_sIz|};dliAT-_TKJ/G `?LS-G+-Tb#Jp[00k|[wccOedTs.+2H|$m');
define('SECURE_AUTH_SALT', 'p4I&qje{8+k@o5I|vLsviGNZ-n{aH6HK]G~bsa%e^-l`N!8G`g-zS|| R}!SW(X}');
define('LOGGED_IN_SALT',   'I}$MoTXt2V=dQ72dJ<#]GoofFET]b7~g-T4!xZuTj&Nc j@-+.ThL>ptP?AgsJDe');
define('NONCE_SALT',       '/-).aoVMIdXV|-,I<#d39t`-3.TPKjKH3,]76UJZ-/l^~,qpwh.[uyy2sK7GqONq');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'bs_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', FALSE);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
