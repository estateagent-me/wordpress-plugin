<section class="ea ea-login">
    <div class="text-center">
        <iframe src="<?php echo EA_DOMAIN; ?>/vendors/login/iframe?agent=<?php echo esc_attr(get_option('ea-agent-id')); ?><?php echo (get_option('ea-accent-colour') ? '&colour='.str_replace('#','',esc_attr(get_option('ea-accent-colour'))) : '' ); ?><?php echo (get_option('ea-accent-colour-alt') ? '&colour_alt='.str_replace('#','',esc_attr(get_option('ea-accent-colour-alt'))) : '' ); ?>"
            frameborder="0"
            height="170"
            allowtransparency="true"></iframe>
    </div>
</section>