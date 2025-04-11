<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sivolap_61386' );

/** Database username */
define( 'DB_USER', 'sivolap_61386' );

/** Database password */
define( 'DB_PASSWORD', '22c20eaab9409280de70' );

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
define( 'AUTH_KEY',         'Kxnw=D-Z%)S ZRz;VIn7:?+ovPT!Ih&UY=_[9aJw4s>-% `.?_sLW|Uxms3^E[*7' );
define( 'SECURE_AUTH_KEY',  'o<+t{:A#>,:f-+&qJ$ IO9;XKs/%~=92$J2YIJ*}?Kc/Y?@bknl3]YeVJf=Al{FT' );
define( 'LOGGED_IN_KEY',    '%t6iI#J4y1iGLuPJ!i1{WxY,|l&vBFJLgU{V!~_[KVk6Tptk$}Z0Q?Z3Iwi2V}EN' );
define( 'NONCE_KEY',        'Ft*<C)DvErVYu0~rDQj*682F7Q|?J.q2XH96h3h.9]NBF:l21|Mh1lXkp(V }?_x' );
define( 'AUTH_SALT',        'zcSX&etOHiv+$h9z3<0.4L.|.FI(wf9d}>wX#c_!_2x!BLK+;H0iE}|6Mbt7G_&t' );
define( 'SECURE_AUTH_SALT', 'aPT9ov/=uE[%=f(U#-8gdg?zMSaJ*pWI4@}q:J7xQ(&9Y%M#{af,&Kn$ep}|KUI[' );
define( 'LOGGED_IN_SALT',   'n8H[Zgl2sL& ?p)HuX:%:&@;:>s0O5gH8M*$#CY/T:e[Of@l}V-lNo2<$Be;a=Gd' );
define( 'NONCE_SALT',       'Ga!@R9cHM%-not{tLA7f#:d7eodyB%DDS>kGzun0j,BSfeLm?YkilagtME33g^IM' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_n5L3v_';


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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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