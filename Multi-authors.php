<?php
/**
 * Plugin Name: Multi authors plugin
 * Description: A simple plugin for adding multiple authors to a post
 * Version: 0.0
 * Author: Magdalena Korpela
 * Author URI: https://github.com/magosb
 * License: MIT
 * 
 * @package WordPress
 * @author  Magdalena Korpela
 * @version 1.0
 */
defined( 'ABSPATH' ) or die( 'Access denied' );

add_post_meta($post_id, 'test', 'test');
