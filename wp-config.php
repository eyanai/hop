<?php // Modified for Hebrew translation
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
define('DB_NAME', 'hop');

/** MySQL database username */
define('DB_USER', 'hoptest');

/** MySQL database password */
define('DB_PASSWORD', 'hoptest');

/** MySQL hostname */
define('DB_HOST', 'www.hoptest.com.tigris.nethost.co.il');

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
define('AUTH_KEY',         'rsaok<h4Mr2[TD8<Nj~9CxGo&2]{&N]7Dw+Eg3@Mry4OZ0wspl~N2*?_WP1az7wb');
define('SECURE_AUTH_KEY',  '|N@$f=9Ih{OO~MiPf5aoGuc>jW<Wj2x}}{eU!i*kp58qF8CZm3GI^& >,p&w=]9M');
define('LOGGED_IN_KEY',    '* j_d^B{7,<L=%WKK_:9tWpN(7cM-GF*b|>0]o9JBy<CSr-zc*0i+v&ZCj2s<EWb');
define('NONCE_KEY',        'CcH%Dg3>oG^l*.393]_dutJaaM 4NbPR8lf)P !~dL]5f.^7X2JmeCx#N_k`%oa(');
define('AUTH_SALT',        '{RRCiPvY:C3:}Q1 %!j>[a[(,4Z,.dF+-)jE0wXcFr34DQ#>XU&>2z21,By2TYg ');
define('SECURE_AUTH_SALT', '/|Jf)@8Kb|mjzU9m@&7uG{j6i-e-b4CJV73UBy}LUezQ$7njpb#YYnYjBTO]oJM_');
define('LOGGED_IN_SALT',   '#6}CqohBaq)]W$ Zea/f4o4&0oZvk?E-lON(!}kYsz L=s6}v<C04fE ]l!$;?L%');
define('NONCE_SALT',       'e;WUkvqUw?((`Px$XEpmT=d0J$2WGdn}w<AIXz5<zym~?yGMkG7vN0>)h,HJ[C[(');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 * By default, the Hebrew locale is used.
 */
define('WPLANG', 'he_IL');

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
