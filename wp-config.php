<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ctynhua');

/** MySQL database username */
define('DB_USER', 'hoang');

/** MySQL database password */
define('DB_PASSWORD', 'Hoang123');

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
define('AUTH_KEY',         'Yt@]ec+*5?++MK)Tqm:JOh|VgGZ|<Zcs>Q}o^~Ba|jIy=uz0#jSNGd$9iqO0QW|6');
define('SECURE_AUTH_KEY',  'stJ;pmilvY&|HVJwZ#5O5.7/Iy/chb3De$$+Z&=Q=Yq d7TS.9A40)8s|h8#|Z8&');
define('LOGGED_IN_KEY',    'T@PMj|[B-~Q+c!^W?WsSUI]GzL/Kt)s6?>5_tuS0b{(F,Tcl+(Wgx7Ig9%2*`=qZ');
define('NONCE_KEY',        'U~.IA&wZ3}lN4I:wq!*@d<u4xh~p%w+-hirs(Ljog5rnh(XV9p=yMWtN,c-?9y-~');
define('AUTH_SALT',        'nc~1~5#X/|3tBZa#<82)xNdE:/Yd8kdgWpK)Q}rSp-RX[2$unRY|**5p8UD.twa*');
define('SECURE_AUTH_SALT', 'P1s/P@Ki%%Mg7L|]bKdCpd?#V{d.-e3$ZZzBX2tRIP|5ef?U6XEd(nR)s_iuI>$6');
define('LOGGED_IN_SALT',   '/8S5jCS} ebuabB#H!R,.O|blh1.tj>;Iel):LUfnfok10,&Yk}h>`#i >u6~;T*');
define('NONCE_SALT',       ')>F@.cz|$bg|eoU-D~./PK7HALR6}vgOi$9Ru9fyT~~6bf<)AdN3]KP|Hv-ulmi6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
