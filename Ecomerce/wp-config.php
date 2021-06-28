<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Ecomerce' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'SwN6#f.g` r1SB/4[9~15VWlK[IIcV.`J<Ou`Ghg[ZV6<E+jq3`O2fu8DnMCx.Zd' );
define( 'SECURE_AUTH_KEY',  'e-{wU@)PxpG^1}ii/sC4d2vAYGQ!nc{vU5M7 Vlv;p#e~yJ%s2/-OZpRG?XUfqzd' );
define( 'LOGGED_IN_KEY',    'T.G=;dY<ENNEj[MSWciuCsoyds:SYVR/lz8V7rH2g-g4Q=jyMa-L+uS0a@eZgvP~' );
define( 'NONCE_KEY',        '>@%z8dkLuQoDmANGZ-pd_9Kw_@0~$|`b%W>.2%Jx+l}HAwzQ(/EJH53Zy!E *,@/' );
define( 'AUTH_SALT',        'U6g Oh[GhhE(GN9-,EnQ0WA?B7R.j:<6^,=Y n>z.+JgQL*(Pw2B*k%+)P9j3x|}' );
define( 'SECURE_AUTH_SALT', '#a&/eWI*GI-!HPkj()&3iIkjsZF*mCD)fJ.s#4/p!i~MU2ux@OZS}jbLmo@s:Fr_' );
define( 'LOGGED_IN_SALT',   'E]N*HnM>/[$zw<j{z%:l| Ws?i#^Qx;fgzotvy-K,@IAkQ3L>Vs~FW9(<jPWPmyS' );
define( 'NONCE_SALT',       ')Inx svyP`z>6*xf`0:itkX98X|`)RAgX <li*kf);WINg+Y>do1#twQ]}IV/J m' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
