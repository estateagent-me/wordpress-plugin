<div class="card property-box property-box-map">
    <?php if ($property->status != 'Available'): ?>
        <p class="status-banner py-1 m-0 text-center <?php echo strtolower(str_replace(' ','-',$property->status)); ?>">
            <?php echo $property->status; ?>
        </p>
    <?php endif; ?>
    <a href="<?php echo $property->detailURL(); ?>" class="property-img shadow-none" style="background-image:url('<?php echo $property->image_default; ?>');">
    </a>
    <div class="card-body">
        <a href="<?php echo $property->detailURL(); ?>">
            <h5 class="card-title">
                <?php echo $property->street_name; ?>, <?php echo $property->town; ?>
            </h5>
            <p class="card-text">
                <?php echo $property->num_beds; ?> bed <?php echo $property->type; ?>
            </p>
        </a>
    </div>
    <div class="card-footer text-muted">
        <strong><?php echo $property->price(); ?></strong>
    </div>
</div>