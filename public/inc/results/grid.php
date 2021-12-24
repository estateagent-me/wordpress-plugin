<div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-4">
    <a href="<?php echo $property->detailURL(); ?>" class="card w-100 shadow-sm property-box property-box-grid">
        <?php if ($property->status != 'Available'): ?>
            <p class="status-banner py-1 m-0 text-center <?php echo strtolower(str_replace(' ','-',$property->status)); ?>">
                <?php echo $property->status; ?>
            </p>
        <?php endif; ?>
        <div class="card-img-top property-img border-bottom shadow-none" style="background-image:url('<?php echo $property->image_default; ?>');">
        </div>
        <div class="card-body">
            <h5 class="card-title">
                <?php echo $property->street_name; ?>, <?php echo $property->town; ?>
            </h5>
            <p class="card-text">
                <?php echo $property->num_beds; ?> bed <?php echo $property->type; ?>
            </p>
        </div>
        <div class="card-footer text-muted">
            <strong><?php echo $property->price(); ?></strong>
        </div>
        </a>
</div>