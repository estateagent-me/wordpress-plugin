<section class="ea ea-search-form">
    <?php
    /**
     * Property search form
     */
    
    include 'configs.php';

    global $wpdb;

    $tablename = $wpdb->prefix . "ea_property_types";

    $property_types = [];
    $property_types_raw = $wpdb->get_results( "SELECT * FROM `{$tablename}` ORDER BY category, name" );

    foreach ($property_types_raw as $type) {
        $property_types[$type->category][] = $type;
    }

    // Set a variable ready for search SALE/RENT pre-selection
    if (esc_attr( get_option('ea-default-search-selection') )): ?>
        <script type="text/javascript">
            const EA_DEFAULT_SEARCH_SELECTION = '<?php echo esc_attr( get_option('ea-default-search-selection') ); ?>';
        </script>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="ea-search-form form-inline">
        <input type="hidden" name="ea-search" value="1" />

        <div class="row w-100 pb-3">
            <?php
            /**
             * Sale or Rent
             */
            ?>
            <div class="col-4 ea-search-form-group ea-search-form-sale-rent">
                <div class="d-flex form-group h-100 align-items-stretch">
                    <div data-search="sale" onclick="jQuery('.ea-search-form-group.rent, .ea-search-form-group .rent').hide();jQuery('.ea-search-form-group.sale, .ea-search-form-group .sale').show();jQuery('*[name=ea-search-sale-rent]').val('sale');jQuery(this).parent().find('div').removeClass('accent-background');jQuery(this).addClass('accent-background');" class="col-6 toggle p-0 text-center d-flex justify-content-center flex-column <?php echo (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent']=='sale' ? 'accent-background' : (!isset($_POST['ea-search-sale-rent']) ? 'accent-background' : '') ); ?>">
                        For Sale
                    </div>
                    <div data-search="rent" onclick="jQuery('.ea-search-form-group.sale, .ea-search-form-group .sale').hide();jQuery('.ea-search-form-group.rent, .ea-search-form-group .rent').show();jQuery('*[name=ea-search-sale-rent]').val('rent');jQuery(this).parent().find('div').removeClass('accent-background');jQuery(this).addClass('accent-background');" class="col-6 toggle p-0 text-center d-flex justify-content-center flex-column <?php echo (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent']=='rent' ? 'accent-background' : ''); ?>">
                        For rent
                    </div>
                    <input type="hidden" name="ea-search-sale-rent" value="<?php echo (isset($_POST['ea-search-sale-rent']) ? $_POST['ea-search-sale-rent'] : 'sale'); ?>" />
                </div>
            </div>

            <?php
            /**
             * SALE - Price min/max 
             */
            ?>
            <div style="<?php echo (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent']!='sale' ? 'display:none;' : ''); ?>" class="col-5 ea-search-form-group ea-search-form-price-sale sale">
                <div class="form-group align-items-center">
                    <label class="mr-2" for="ea-search-min-price-sale">Price</label>
                    <div class="selects flex-grow-1">
                        <div class="row no-gutters">
                            <div class="col pr-1">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-min-price-sale" id="ea-search-min-price-sale">
                                    <option value="">Min</option>
                                    <?php foreach ($ea_public_config['prices'] as $price => $label): ?>
                                        <option value="<?php echo $price; ?>" <?php echo (isset($_POST['ea-search-min-price-sale']) && $_POST['ea-search-min-price-sale']==$price ? 'selected="selected"' : ''); ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-max-price-sale" id="ea-search-max-price-sale">
                                    <option value="">Max</option>
                                    <?php foreach ($ea_public_config['prices'] as $price => $label): ?>
                                        <option value="<?php echo $price; ?>" <?php echo (isset($_POST['ea-search-max-price-sale']) && $_POST['ea-search-max-price-sale']==$price ? 'selected="selected"' : ''); ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /**
             * RENT - Price min/max 
             */
            ?>
            <div style="<?php echo (isset($_POST['ea-search-sale-rent']) && $_POST['ea-search-sale-rent']!='rent' ? 'display:none;' : (!isset($_POST['ea-search-sale-rent']) ? 'display:none;' : '') ); ?>" class="col-5 ea-search-form-group ea-search-form-price-rent rent">
                <div class="form-group align-items-center">
                    <label class="mr-2" for="ea-search-min-price-rent">Price</label>
                    <div class="selects flex-grow-1">
                        <div class="row no-gutters">
                            <div class="col pr-1">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-min-price-rent" id="ea-search-min-price-rent">
                                    <option value="">Min</option>
                                    <?php foreach ($ea_public_config['prices_rent'] as $price => $label): ?>
                                        <option value="<?php echo $price; ?>" <?php echo (isset($_POST['ea-search-min-price-rent']) && $_POST['ea-search-min-price-rent']==$price ? 'selected="selected"' : ''); ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-max-price-rent" id="ea-search-max-price-rent">
                                    <option value="">Max</option>
                                    <?php foreach ($ea_public_config['prices_rent'] as $price => $label): ?>
                                        <option value="<?php echo $price; ?>" <?php echo (isset($_POST['ea-search-max-price-rent']) && $_POST['ea-search-max-price-rent']==$price ? 'selected="selected"' : ''); ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /**
             * Bedrooms min/max 
             */
            ?>
            <div class="col-3 ea-search-form-group ea-search-form-beds">
                <div class="form-group">
                    <label class="mr-2" for="ea-search-min-beds">Beds</label>
                    <div class="selects flex-grow-1">
                        <div class="row no-gutters">
                            <div class="col pr-1">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-min-beds" id="ea-search-min-beds">
                                    <option value="">Min</option>
                                    <?php for($i=1; $i<=10; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo (isset($_POST['ea-search-min-beds']) && $_POST['ea-search-min-beds']==$i ? 'selected="selected"' : ''); ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-max-beds" id="ea-search-max-beds">
                                    <option value="">Max</option>
                                    <?php for($i=1; $i<=10; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo (isset($_POST['ea-search-max-beds']) && $_POST['ea-search-max-beds']==$i ? 'selected="selected"' : ''); ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /**
             * Property type
             */
            ?>
            <div class="col-5 ea-search-form-group ea-search-form-type">
                <div class="form-group">
                    <div class="row no-gutters w-100">
                        <?php if (!empty($property_types)): ?>
                            <div class="col pr-1">
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-type-parent" id="ea-search-type-parent">
                                    <option value="">- Property Type -</option>
                                    <?php foreach ($property_types as $parent => $children):
                                        $parentVal = '';
                                        foreach ($children as $type) { $parentVal .= "{$type->type_id},"; }
                                        ?>
                                        <option <?php echo (isset($_POST['ea-search-type-parent'])&&$_POST['ea-search-type-parent']==substr($parentVal,0,-1) ? 'selected' : ''); ?>
                                            value="<?php echo substr($parentVal,0,-1); ?>" data-parent="<?php echo $parent; ?>">
                                                <?php echo $parent; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col" style="display:none;" id="ea-search-type-children-col">
                                <?php
                                $__post_search_type_children = isset($_POST['ea-search-type-children']) ? $_POST['ea-search-type-children'] : false;
                                ?>
                                <select style="width:100%;max-width:100%;" class="form-control" name="ea-search-type-children" id="ea-search-type-children">
                                    <?php foreach ($property_types as $parent => $children):
                                        $parentVal = '';
                                        foreach ($children as $type) { $parentVal .= "{$type->type_id},"; }
                                        $selected = false;
                                        if (!$__post_search_type_children) {
                                            $selected = true;
                                        } else {
                                            if ($__post_search_type_children == substr($parentVal,0,-1)) {
                                                $selected = true;
                                            }
                                        }
                                        ?>
                                        <option <?php echo $selected; ?>
                                            value="<?php echo substr($parentVal,0,-1); ?>" data-all data-parent="<?php echo $parent; ?>">
                                                - All -
                                        </option>
                                        <?php foreach ($children as $type): ?>
                                            <option <?php echo ($__post_search_type_children === $type->type_id ? 'selected' : ''); ?>
                                                value="<?php echo $type->type_id; ?>" data-parent="<?php echo $parent; ?>">
                                                    <?php echo $type->name; ?>
                                            </option>
                                    <?php endforeach;
                                    endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php
            /**
             * Town search 
             */
            ?>
            <div class="col-7 ea-search-form-group ea-search-form-location">
                <div class="form-group">
                    <label class="mr-2 hide-md" for="ea-search-location">Location</label>
                    <input class="flex-grow-1 form-control" type="text" name="ea-search-location" id="ea-search-location" value="<?php echo (isset($_POST['ea-search-location']) ? stripslashes($_POST['ea-search-location']) : ''); ?>" placeholder="Eg, Southampton or Liverpool" />
                </div>
            </div>

            <div class="col-7 ea-search-form-group ea-search-form-include-additional m-0 pb-0 d-flex align-items-center">
                <div class="form-group">
                    <input style="margin:0 10px 0 0;" type="checkbox" name="include_additional" id="include_additional" value="1" <?php echo (isset($_POST['include_additional'])&&boolval($_POST['include_additional']) ? 'checked="checked"' : ''); ?> />
                    <label style="padding:0;" class="form-check-label" for="include_additional">
                        <span class="sale" style="<?php echo (isset($_POST['ea-search-sale-rent'])&&$_POST['ea-search-sale-rent']!='sale' ? 'display:none;' : ''); ?>">
                            Include Under Offer & Sold STC
                        </span>
                        <span class="rent" style="<?php echo (isset($_POST['ea-search-sale-rent'])&&$_POST['ea-search-sale-rent']!='rent' ? 'display:none;' : (!isset($_POST['ea-search-sale-rent']) ? 'display:none;' : '') ); ?>">
                            Include Under Offer, Let Agreed & Let
                        </span>
                    </label>
                </div>
            </div>

            <div class="col-5 ea-search-form-group ea-search-form-button pb-0">
                <div class="form-group">
                    <button class="btn btn-primary btn-block">Search</button>
                </div>
            </div>

        </div><!-- row -->

        
    </form>

</section>