<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sedno
 */

if ( is_front_page() ) {
	get_template_part( 'template-parts/site-footer', 'front-page' );
}

