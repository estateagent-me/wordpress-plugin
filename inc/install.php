<?php

/**
 * The number of tables required
 */
DEFINE('REQUIRED_NUM_TABLES', 7);

/**
 * install - CREATE TABLE's
 */
function install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    /**
     * agents
     */
    $table_name = $wpdb->prefix . 'ea_agent';
    $sql = "CREATE TABLE $table_name (
            `agent_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `company_name` varchar(200) NULL,
            `primary_email` varchar(200) NULL,
            `phone` varchar(50) NULL,
            `mobile` varchar(50) NULL,
            `website` varchar(200) NULL,
            `logo` varchar(200) NULL,
            `property_number` varchar(100) NULL,
            `street_name` varchar(200) NULL,
            `town` varchar(100) NULL,
            `county` varchar(100) NULL,
            `postcode` varchar(20) NULL,
            `postcode_pt1` char(6) NULL,
            `postcode_pt2` char(6) NULL,
            `lat` varchar(100) NULL,
            `long` varchar(100) NULL,
            `social_facebook` varchar(255) NULL,
            `social_twitter` varchar(255) NULL,
            `social_instagram` varchar(255) NULL,
            `social_linkedin` varchar(255) NULL,
        PRIMARY KEY  (agent_id)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * property types
     */
    $table_name = $wpdb->prefix . 'ea_property_types';
    $sql = "CREATE TABLE $table_name (
            `type_id` int(11) NOT NULL,
            `name` varchar(200) NOT NULL,
            `category` varchar(200) NOT NULL,
        PRIMARY KEY  (`type_id`)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * properties
     */
    $table_name = $wpdb->prefix . 'ea_properties';
    $sql = "CREATE TABLE $table_name (
            `property_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `sale_or_rent` char(6) NOT NULL,
            `status` varchar(50) NOT NULL,
            `type` varchar(50) NOT NULL,
            `type_id` int(8) NOT NULL,
            `tenure` varchar(50) NULL,
            `price` decimal(10,2) NOT NULL,
            `price_desc` varchar(50) NOT NULL,
            `property_number` varchar(100) NULL,
            `street_name` varchar(200) NULL,
            `town` varchar(100) NULL,
            `county` varchar(100) NULL,
            `postcode` varchar(20) NULL,
            `postcode_pt1` char(6) NULL,
            `postcode_pt2` char(6) NULL,
            `lat` varchar(100) NULL,
            `long` varchar(100) NULL,
            `num_beds` tinyint(2) NULL,
            `num_bath` tinyint(2) NULL,
            `num_living_rooms` tinyint(2) NULL,
            `num_floors` tinyint(2) NULL,
            `parking` tinyint(1) NULL,
            `garage` tinyint(1) NULL,
            `garden` tinyint(1) NULL,
            `no_chain` tinyint(1) NULL,
            `furnished_state` varchar(50) NULL,
            `rental_term` varchar(50) NULL,
            `image_default` varchar(255) NULL,
            `description_short` text,
            `description_long` text,
        PRIMARY KEY  (property_id)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * property features
     */
    $table_name = $wpdb->prefix . 'ea_properties_features';
    $sql = "CREATE TABLE $table_name (
            `feature_id` int(11) NOT NULL,
            `property_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `feature` varchar(255) NULL,
        PRIMARY KEY  (feature_id)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * property images
     */
    $table_name = $wpdb->prefix . 'ea_properties_images';
    $sql = "CREATE TABLE $table_name (
            `image_id` int(11) NOT NULL,
            `property_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `path` varchar(255) NULL,
            `caption` varchar(200) NULL,
        PRIMARY KEY  (image_id)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * property EPC's
     */
    $table_name = $wpdb->prefix . 'ea_properties_epcs';
    $sql = "CREATE TABLE $table_name (
            `epc_id` int(11) NOT NULL,
            `property_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `path` varchar(255) NULL,
            `caption` varchar(200) NULL,
        PRIMARY KEY  (epc_id)
    ) $charset_collate;";
    dbDelta( $sql );

    /**
     * property Floorplans
     */
    $table_name = $wpdb->prefix . 'ea_properties_floorplans';
    $sql = "CREATE TABLE $table_name (
            `floorplan_id` int(11) NOT NULL,
            `property_id` int(11) NOT NULL,
            `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `path` varchar(255) NULL,
            `caption` varchar(200) NULL,
        PRIMARY KEY  (floorplan_id)
    ) $charset_collate;";
    dbDelta( $sql );

    echo '<h1>DB Install Run successful</h1>';
}