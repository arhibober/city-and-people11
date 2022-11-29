<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'city-and-people11' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'WAbR^k2FKk{/fsSN;#>G-A=si<;k[^F`IB`Kqdbnm=)PI(1KOLkLlNE84K5_PP{u' );
define( 'SECURE_AUTH_KEY',  '7zZ}-*l>,DVb,BF@q.<Y-PxU3@s#1Q&3B:$@fx9OCSpgj@H@lB)GPWr,B|(1#*7S' );
define( 'LOGGED_IN_KEY',    'LMt|4Ez4Ut3r=U8lm$, Ydz1@h2*WNGlzF T5B[`6wA?ovrbg-|8^*<H/R?5BI6@' );
define( 'NONCE_KEY',        '%pkc=<0IA46y3$J*xLxL GN[arX +i&D})pdQIj30el7> e9yXc@e3_u3S2#En|X' );
define( 'AUTH_SALT',        'q}9u2qfAY9>*4X;H0@Szs:v}ttiHx2Apg x;Vs=[ <k>~a2T}eq:.@e1g{FbhL1u' );
define( 'SECURE_AUTH_SALT', '>mu=?CKT_TX7{W|B:xXu->jsA-Oqcj$!{OGQ8T?[?nIqWE;)Z2K`jPJXKXVDTHmw' );
define( 'LOGGED_IN_SALT',   '_.0^vKYb6kfXY^U!Ar!@f7)E))%)vX]p<jes4v&Y)Z+vbs7nYY}o!xOeW(gV[ d-' );
define( 'NONCE_SALT',       ';!]c[#?w1zu=Ej:9H>OF+aeh`X*u(M7}x5`L/y6I`3m*Om-G<WY5iR|q]BC)8eV/' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('WP_USE_EXT_MYSQL', true);
