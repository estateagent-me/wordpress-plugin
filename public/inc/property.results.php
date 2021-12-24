<section class="ea ea-search-results">
    <?php
    /**
     * Property results
     */

    global $wpdb;

    $tablename = $wpdb->prefix . "ea_properties";

    $params = [];

    $query = "SELECT *,
        (SELECT COUNT(*) FROM `{$wpdb->prefix}ea_properties_images`
            WHERE `{$wpdb->prefix}ea_properties_images`.`property_id` = `{$tablename}`.`property_id`
            AND SUBSTR(`{$wpdb->prefix}ea_properties_images`.`path`, -3) != 'pdf')
        AS num_images
    FROM `{$tablename}` WHERE 1=1 ";

    if (! isset($_POST['ea-search'])) {

        $query .= " AND `status` = 'Available' ORDER BY RAND() LIMIT 3 ";

    } else {

        // sale or rental
        if (isset($_POST['ea-search-sale-rent'])) {
            $query .= " AND `sale_or_rent` = %s ";
            $params[] = ucwords($_POST['ea-search-sale-rent']);

            if ($_POST['ea-search-sale-rent'] == 'sale') {
                unset($_POST['ea-search-min-price-rent']);
                unset($_POST['ea-search-max-price-rent']);
            }
            elseif ($_POST['ea-search-sale-rent'] == 'rent') {
                unset($_POST['ea-search-min-price-sale']);
                unset($_POST['ea-search-max-price-sale']);
            }
        }

        // location search
        if (isset($_POST['ea-search-location']) && strlen($_POST['ea-search-location']) > 0)
        {
            $query .= " AND (
                    `town` LIKE '%%%s%%'
                    OR `county` LIKE '%%%s%%'
                    OR `postcode` LIKE '%%%s%%'
                ) ";
            $params[] = $_POST['ea-search-location'];
            $params[] = $_POST['ea-search-location'];
            $params[] = $_POST['ea-search-location'];
        }

        // min beds
        if (isset($_POST['ea-search-min-beds']) && $_POST['ea-search-min-beds'] != '') {
            $query .= " AND `num_beds` >= '%s' ";
            $params[] = $_POST['ea-search-min-beds'];
        }

        // max beds
        if (isset($_POST['ea-search-max-beds']) && $_POST['ea-search-max-beds'] != '') {
            $query .= " AND `num_beds` <= '%s' ";
            $params[] = $_POST['ea-search-max-beds'];
        }

        // min price SALE
        if (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent'] == 'sale'
                && isset($_POST['ea-search-min-price-sale']) && $_POST['ea-search-min-price-sale'] != '') {
            $query .= " AND `price` >= '%s' ";
            $params[] = $_POST['ea-search-min-price-sale'];
        }

        // max price SALE
        if (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent'] == 'sale'
                && isset($_POST['ea-search-max-price-sale']) && $_POST['ea-search-max-price-sale'] != '') {
            $query .= " AND `price` <= '%s' ";
            $params[] = $_POST['ea-search-max-price-sale'];
        }

        // min price RENT
        if (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent'] == 'rent'
                && isset($_POST['ea-search-min-price-rent']) && $_POST['ea-search-min-price-rent'] != '') {
            $query .= " AND `price` >= '%s' ";
            $params[] = $_POST['ea-search-min-price-rent'];
        }

        // max price RENT
        if (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent'] == 'rent'
                && isset($_POST['ea-search-max-price-rent']) && $_POST['ea-search-max-price-rent'] != '') {
            $query .= " AND `price` <= '%s' ";
            $params[] = $_POST['ea-search-max-price-rent'];
        }

        // property type
        if (isset($_POST['ea-search-type']) && $_POST['ea-search-type'] != '') {
            $query .= " AND `type_id` = '%s' ";
            $params[] = $_POST['ea-search-type'];
        }

        if (isset($_POST['ea-search-type-children']) && $_POST['ea-search-type-children'] != '') {
            $propertyTypes = esc_sql($_POST['ea-search-type-children']);
            $query .= esc_sql(" AND `type_id` IN({$propertyTypes}) ");
        }

        // include Under Offer, SSTC, Let Agreed is _NOT_ selected
        if (!isset($_POST['include_additional'])) {
            $query .= " AND `status` = 'Available' ";
        }
    }

    $query = $wpdb->prepare( $query, $params );
    $results = $wpdb->get_results( $query );

    $properties = [];
    foreach ($results as $key => $data) {
        $properties[$key] = new \Property((array)$data);
    }
    ?>

    <div class="border-top my-5"></div>

    <?php if (isset($_POST['ea-search'])): ?>
        <h2>Property Results</h2>
    <?php else: ?>
        <h2>Featured Properties</h2>
    <?php endif; ?>

    <?php if (count($properties) > 0): ?>

        <div id="property_results">

            <div class="d-flex justify-content-between align-items-end border-bottom mb-3">
                <ul class="nav nav-tabs m-0 border-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" style="box-shadow:none;" id="grid-view" data-toggle="tab" href="#grid" role="tab" aria-controls="grid" aria-selected="true">
                            <i class="fa fa-th-large" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-flex">
                        <a class="nav-link" style="box-shadow:none;" id="list-view" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="false">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="box-shadow:none;" id="map-view" data-toggle="tab" href="#map" role="tab" aria-controls="map" aria-selected="false">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
                <?php if (isset($_POST['ea-search'])): ?>
                    <p class="text-muted"><?php echo count($properties); ?> Properties Found</p>
                <?php endif; ?>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="grid-view">
                    <div class="row">
                        <?php foreach ($properties as $property): ?>
                            <?php include('results/grid.php'); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-view">
                    <?php foreach ($properties as $property): ?>
                        <?php include('results/list.php'); ?>
                    <?php endforeach; ?>
                </div>
                <div class="tab-pane fade" id="map" role="tabpanel" aria-labelledby="map-view">
                    <?php include('results/map.php'); ?>
                </div>
            </div>

        </div>

    <?php else: ?>
        <?php if (isset($_POST['ea-search'])): ?>
            <p class="text-muted">Sorry, no properties found matching your search criteria.</p>
        <?php endif; ?>
    <?php endif; ?>

</section>