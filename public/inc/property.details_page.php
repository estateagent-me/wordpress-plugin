<section class="ea ea-property-details">
    <?php
    /**
     * Property details page
     */

    global $wpdb, $post;

    // current post (page) has to be property-details, obviously
    if ($post->post_name == 'property-details'):

        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        if (! isset($uri_segments[4]) && $uri_segments[1] != 'wp-admin')
        {
            // property not found TODO
            die('Property ID not found');
        }

        $propertyID = floatval($uri_segments[4]);

        $query = "SELECT * FROM `{$wpdb->prefix}ea_properties` WHERE property_id = '" . $propertyID . "'";
        $result = $wpdb->get_row( $query );
        $property = new \Property((array)$result);

        $features = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}ea_properties_features` WHERE `property_id` = '" . $propertyID . "'" );

        if (boolval($property->parking)) {
            array_push($features, (object)['feature' => 'Parking']);
        }

        if (boolval($property->garage)) {
            array_push($features, (object)['feature' => 'Garage']);
        }
        
        if (boolval($property->garden)) {
            array_push($features, (object)['feature' => 'Garden']);
        }

        if (boolval($property->no_chain)) {
            array_push($features, (object)['feature' => 'No chain']);
        }

        if (boolval($property->pets_allowed)) {
            array_push($features, (object)['feature' => 'Pets allowed']);
        }

        if (boolval($property->burglar_alarm)) {
            array_push($features, (object)['feature' => 'Burglar alarm']);
        }

        if (boolval($property->washing_machine)) {
            array_push($features, (object)['feature' => 'Washing machine']);
        }

        if (boolval($property->dishwasher)) {
            array_push($features, (object)['feature' => 'Dishwasher']);
        }

        $images = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}ea_properties_images` WHERE property_id = '" . $propertyID . "'" );
        $floorplans = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}ea_properties_floorplans` WHERE property_id = '" . $propertyID . "'" );
        $epcs = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}ea_properties_epcs` WHERE property_id = '" . $propertyID . "'" );

        $pdfs = [];
        
        foreach ($floorplans as $img) {
            if (strstr($img->path,'.pdf')) {
                $pdfs[] = $img;
            }
        } 
        foreach ($epcs as $img) {
            if (strstr($img->path,'.pdf')) {
                $pdfs[] = $img;
            }
        }

        $numTotalMedia = (count($images) + count($floorplans) + count($epcs)) - count($pdfs);
        ?>
        <div id="property_details">

            <div class="row">
                <div class="col-md-7 order-2 order-md-1">
                    <div id="images">
                        <?php if ($property->status != 'Available'): ?>
                            <p class="status-banner py-1 m-0 text-center <?php echo strtolower(str_replace(' ','-',$property->status)); ?>">
                                <?php echo $property->status; ?>
                            </p>
                        <?php endif; ?>
                        <ul id="lightSlider" class="m-0">
                            <?php if ($numTotalMedia === 0): ?>
                                <li data-thumb="https://estateagent.me/img/noimg.png">
                                    <img src="https://estateagent.me/img/noimg.png" />
                                </li>
                            <?php endif; ?>
                            <?php foreach ($images as $img):
                                if (strstr($img->path,'.pdf')) continue;
                                ?>
                                <li data-thumb="<?php echo $img->path; ?>">
                                    <a href="<?php echo $img->path; ?>"
                                        <?php echo (!empty($img->caption) ? 'data-footer="' . strip_tags($img->caption) . '"' : ''); ?>
                                        data-toggle="lightbox"
                                        data-gallery="gallery"
                                        >
                                            <img src="<?php echo $img->path; ?>" />
                                    </a>
                                    <?php if (!empty($img->caption)): ?>
                                        <div class="caption">
                                            <?php echo strip_tags($img->caption); ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php foreach ($floorplans as $img):
                                if (strstr($img->path,'.pdf')) continue;
                                ?>
                                <li data-thumb="<?php echo $img->path; ?>">
                                    <a href="<?php echo $img->path; ?>"
                                        <?php echo (!empty($img->caption) ? 'data-footer="' . strip_tags($img->caption) . '"' : ''); ?>
                                        data-toggle="lightbox"
                                        data-gallery="gallery"
                                        >
                                            <img src="<?php echo $img->path; ?>" />
                                    </a>
                                    <?php if (!empty($img->caption)): ?>
                                        <div class="caption">
                                            <?php echo strip_tags($img->caption); ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php foreach ($epcs as $img):
                                if (strstr($img->path,'.pdf')) continue;
                                ?>
                                <li data-thumb="<?php echo $img->path; ?>">
                                    <a href="<?php echo $img->path; ?>"
                                        <?php echo (!empty($img->caption) ? 'data-footer="' . strip_tags($img->caption) . '"' : ''); ?>
                                        data-toggle="lightbox"
                                        data-gallery="gallery"
                                        >
                                            <img src="<?php echo $img->path; ?>" />
                                    </a>
                                    <?php if (!empty($img->caption)): ?>
                                        <div class="caption">
                                            <?php echo strip_tags($img->caption); ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 order-1 order-md-2">
                    <div class="d-flex flex-fill flex-column h-100">
                        <h3 class="m-0">
                            <?php echo $property->town; ?>, <?php echo $property->county; ?>
                        </h3>
                        <p class="my-3 text-muted">
                            <?php echo $property->num_beds; ?> bed, <?php echo $property->type; ?><br />
                            <strong><?php echo $property->price(); ?></strong>
                        </p>
                        <div class="flex-fill d-none d-md-flex">
                            <div id="map" class="shadow-sm border" style="width:100%;height:100%;"></div>
                        </div>
                    </div>

                </div>
            </div><!-- row -->

            <div class="border-top mt-5 mb-3"></div>

            <div class="py-4 lead">
                <?php echo $property->description_short; ?>
            </div>

            <?php if (!empty($features)): ?>
                <ul class="p-0 pb-5 m-0 ul-3-col">
                    <?php foreach ($features as $feature):
                        if (strlen($feature->feature)<1) continue; ?>
                        <li>
                            <span>
                                <?php echo $feature->feature; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="text-muted">
                <?php echo $property->description_long; ?>
            </div>

            <?php if (!empty($pdfs)): ?>
                <hr />
                <p class="m-0 pt-4"><strong>Floorplan/EPC documents</strong></p>
                <ul class="p-0 pb-5 m-0 ul-3-col">
                    <?php foreach ($pdfs as $pdf): ?>
                        <li>
                            <span>
                                <a href="<?php echo $pdf->path; ?>" target="_blank">
                                    <?php
                                    if (!empty($pdf->caption)) {
                                        echo $pdf->caption.'.pdf';
                                    } else {
                                        echo substr($pdf->path, strrpos($pdf->path,'/')+1);
                                    }
                                    ?>
                                </a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <script type="text/javascript">
        var map;
        function initMap() {
            var propertyPos = {lat: <?php echo $property->lat; ?>, lng: <?php echo $property->long; ?>};

            map = new google.maps.Map(document.getElementById('map'), {
                center: propertyPos,
                zoom: 14
            });

            var marker = new google.maps.Marker({
                position: propertyPos,
                map: map
            });
        }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option('ea-google-maps-api-key'); ?>&callback=initMap" async defer></script>
        
    <?php
    endif;
    ?>
</section>