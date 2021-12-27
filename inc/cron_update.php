<?php

/**
 * Function that gets run every time the CRON runs
 * This updates DB tables
 */
function EACronUpdate( $surpress_messages = true )
{
    global $wpdb;

    // if the number of tables in the DB is less than the required number, run install()
    $num_tables = $wpdb->query("SHOW TABLES LIKE '%ea_%'");
    if ($num_tables < REQUIRED_NUM_TABLES) {
        install();
    }

    $auth_key = get_option('ea-auth-key');
    if (empty($auth_key)) die( 'Error - no auth key' );

    $agent_id = get_option('ea-agent-id');
    if (empty($agent_id)) die( 'Error - no agent ID' );


    /** ---------------------------------------------------------------------------------------------------- **/
    /**
     * Property types request & install
     */
    $url = EA_DOMAIN . '/xml/properties/types/' . $agent_id;

    if (!$surpress_messages) echo "<h1>cURL request to -- {$url}</h1>";

    $url = $url . '?' . http_build_query([
        'site_url' => get_site_url(),
        'version' => get_bloginfo('version'),
    ]);

    // setup curl
    $ch = curl_init($url);
    curl_setopt_array($ch, array( 
        CURLOPT_RETURNTRANSFER      => true, 
        CURLOPT_URL                 => $url,
        CURLOPT_HTTPHEADER          => ['Authorisation: ' . $auth_key],
    ));
    $xml = curl_exec($ch);
    curl_close($ch);

    // check for errors
    libxml_use_internal_errors(true);
    if (! simplexml_load_string($xml) )
    {
        $errors = libxml_get_errors();
        foreach(libxml_get_errors() as $error) {
            if (!$surpress_messages) echo $error->message . '<br />';
        }
        libxml_clear_errors();
    }

    // no errors
    else
    {
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = (object)json_decode(json_encode($xml), true);
        $xml->types = (object)$xml->types;
        $xml->types->category = (object)$xml->types->category;

        // remove all data in Property Types tabe
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_property_types');

        /**
         * loop types
         */
        foreach ($xml->types->category as $category) {

            $category = (object)$category;
            $category->types = (object)$category->types;
            $category->types->type = (object)$category->types->type;
            foreach ($category->types->type as $type) {
                // insert / update
                $type = (object)$type;
                $data = array( 
                    'type_id' => $type->id,
                    'name' => $type->name,
                    'category' => $category->name,
                );
                $wpdb->replace( 
                    $wpdb->prefix . 'ea_property_types', 
                    $data
                );
            }
        }
        if (!$surpress_messages) echo '<p>Property types done</p>';
    }

    /** ---------------------------------------------------------------------------------------------------- **/
    
    /**
     * Agent info & properties install
     */
    $noimg = str_replace('/inc','',plugin_dir_url(__FILE__)) . 'public/img/noimg.png';

    $url = EA_DOMAIN . '/xml/properties/by-agent/' . $agent_id;

    if (!$surpress_messages) echo "<h1>cURL request to -- {$url}</h1>";

    $url = $url . '?' . http_build_query([
        'site_url' => get_site_url(),
        'version' => get_bloginfo('version'),
    ]);

    // setup curl
    $ch = curl_init($url);
    curl_setopt_array($ch, array( 
        CURLOPT_RETURNTRANSFER      => true, 
        CURLOPT_URL                 => $url,
        CURLOPT_HTTPHEADER          => ['Authorisation: ' . $auth_key],
    ));
    $xml = curl_exec($ch);
    curl_close($ch);

    // check for errors
    libxml_use_internal_errors(true);
    if (! simplexml_load_string($xml) )
    {
        $errors = libxml_get_errors();
        foreach(libxml_get_errors() as $error) {
            if (!$surpress_messages) echo $error->message . '<br />';
        }
        libxml_clear_errors();
    }

    // no errors
    else
    {
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = (object)json_decode(json_encode($xml), true);
        
        // Convert all child's to objects
        $xml->agent = (object)$xml->agent;
        $xml->agent->address = (object)$xml->agent->address;
        $xml->agent->social_media = (object)$xml->agent->social_media;
        $xml->properties = (object)$xml->properties;
        $xml->properties->property = (object)$xml->properties->property;

        // remove all data in Agents table
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_agent');

        // remove all data in Property-related table
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_properties');
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_properties_features');
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_properties_images');
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_properties_epcs');
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ea_properties_floorplans');

        // insert / update agent
        $wpdb->replace( 
            $wpdb->prefix . 'ea_agent', 
            array( 
                'agent_id' => $xml->agent->agent_id,
                'company_name' => $xml->agent->company_name,
                'primary_email' => $xml->agent->primary_email,
                'phone' => $xml->agent->phone,
                'mobile' => $xml->agent->mobile,
                'website' => $xml->agent->website,
                'logo' => $xml->agent->logo,
                // addr
                'property_number' => $xml->agent->address->property_number,
                'street_name' => $xml->agent->address->street_name,
                'town' => $xml->agent->address->town,
                'county' => $xml->agent->address->county,
                'postcode' => $xml->agent->address->postcode,
                'postcode_pt1' => $xml->agent->address->postcode_pt1,
                'postcode_pt2' => $xml->agent->address->postcode_pt2,
                'lat' => $xml->agent->address->lat,
                'long' => $xml->agent->address->long,
                // social
                'social_facebook' => $xml->agent->social_media->facebook,
                'social_twitter' => $xml->agent->social_media->twitter,
                'social_instagram' => $xml->agent->social_media->instagram,
                'social_linkedin' => $xml->agent->social_media->linkedin,
            )
        );

        /**
         * loop properties
         */
        foreach ($xml->properties->property as $property)
        {
            // Convert all child's to objects
            $property = (object)$property;
            $property->address = (object)$property->address;
            $property->features = (object)$property->features;
            $property->features->feature = (object)$property->features->feature;
            $property->images = (object)$property->images;
            $property->images->image = (object)$property->images->image;
            $property->epcs = (object)$property->epcs;
            $property->epcs->epc = (object)$property->epcs->epc;
            $property->floorplans = (object)$property->floorplans;
            $property->floorplans->floorplan = (object)$property->floorplans->floorplan;

            // insert / update properties
            $wpdb->replace( 
                $wpdb->prefix . 'ea_properties', 
                array( 
                    'property_id' => $property->property_id,
                    'sale_or_rent' => $property->sale_or_rent,
                    'status' => $property->status,
                    'type' => $property->type,
                    'type_id' => $property->type_id,
                    'tenure' => $property->tenure,
                    'price' => $property->price,
                    'price_desc' => $property->price_desc,
                    // addr
                    'property_number' => $property->address->property_number,
                    'street_name' => $property->address->street_name,
                    'town' => $property->address->town,
                    'county' => $property->address->county,
                    'postcode' => $property->address->postcode,
                    'postcode_pt1' => $property->address->postcode_pt1,
                    'postcode_pt2' => $property->address->postcode_pt2,
                    'lat' => $property->address->lat,
                    'long' => $property->address->long,
                    //
                    'num_beds' => $property->num_beds,
                    'num_bath' => $property->num_bath,
                    'num_living_rooms' => $property->num_living_rooms,
                    'num_floors' => $property->num_floors,
                    'parking' => $property->parking,
                    'garage' => $property->garage,
                    'garden' => $property->garden,
                    'no_chain' => $property->no_chain,
                    'description_short' => $property->description_short,
                    'description_long' => $property->description_long,
                    'image_default' => (!empty($property->image_default) ? $property->image_default : $noimg),
                ) 
            );

            /**
             * loop features
             */
            foreach ($property->features->feature as $feature)
            {
                $feature = (object)$feature;
                $wpdb->replace( 
                    $wpdb->prefix . 'ea_properties_features', 
                    array( 
                        'feature_id' => $feature->feature_id,
                        'property_id' => $property->property_id,
                        'feature' => $feature->feature,
                    ) 
                );
            }

            /**
             * loop images
             */
            foreach ($property->images->image as $image)
            {
                $image = (object)$image;
                $wpdb->replace( 
                    $wpdb->prefix . 'ea_properties_images', 
                    array( 
                        'image_id' => $image->image_id,
                        'property_id' => $property->property_id,
                        'path' => $image->path,
                        'caption' => $image->caption,
                    ) 
                );
            }

            /**
             * loop EPC's
             */
            foreach ($property->epcs->epc as $epc)
            {
                $epc = (object)$epc;
                $wpdb->replace( 
                    $wpdb->prefix . 'ea_properties_epcs', 
                    array( 
                        'epc_id' => $epc->epc_id,
                        'property_id' => $property->property_id,
                        'path' => $epc->path,
                        'caption' => $epc->caption,
                    ) 
                );
            }

            /**
             * loop Floorpans
             */
            foreach ($property->floorplans->floorplan as $floorplan)
            {
                $floorplan = (object)$floorplan;
                $wpdb->replace( 
                    $wpdb->prefix . 'ea_properties_floorplans', 
                    array( 
                        'floorplan_id' => $floorplan->floorplan_id,
                        'property_id' => $property->property_id,
                        'path' => $floorplan->path,
                        'caption' => $floorplan->caption,
                    ) 
                );
            }
        }

        if (!$surpress_messages) {
            echo '<pre>';
            print_r($xml);
            echo '</pre>';
        }
    }
}