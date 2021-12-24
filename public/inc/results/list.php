<div class="mb-4">
    <div class="card w-100 shadow-sm property-box property-box-list">
        <?php if ($property->status != 'Available'): ?>
            <p class="status-banner py-1 m-0 text-center <?php echo strtolower(str_replace(' ','-',$property->status)); ?>">
                <?php echo $property->status; ?>
            </p>
        <?php endif; ?>
        <div class="row no-gutters">
            <div class="col-md-4">
                <a href="<?php echo $property->detailURL(); ?>" class="d-block h-100 property-img shadow-none border-right" style="background-image:url('<?php echo $property->image_default; ?>');">
                </a>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <a href="<?php echo $property->detailURL(); ?>">
                        <h5 class="card-title">
                            <?php echo $property->street_name; ?>, <?php echo $property->town; ?>
                        </h5>
                    </a>
                    <p class="card-text m-0 text-truncate description">
                        <?php
                        if (strlen($property->description_short) > 2) {
                            echo strip_tags($property->description_short);
                        } else {
                            echo strip_tags($property->description_long);
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-md-4 text-center">
                    <strong><?php echo $property->price(); ?></strong>
                </div>
                <div class="col-md-8">
                    <ul class="list-inline d-flex m-0">
                        <li class="pr-3"><i class="fa fa-bed" aria-hidden="true"></i> <?php echo $property->num_beds; ?></li>
                        <li class="pr-3"><i class="fa fa-camera" aria-hidden="true"></i> <?php echo $property->num_images; ?></li>
                        <li class="pr-5"><i class="fa fa-shower" aria-hidden="true"></i> <?php echo $property->num_bath; ?></li>
                        <li><?php echo $property->type; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>