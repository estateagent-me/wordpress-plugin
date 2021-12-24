<style>
.accent-background,
.ea .btn {
    background: <?php echo (get_option('ea-accent-colour') ? esc_attr(get_option('ea-accent-colour')) : '#dd9933' ); ?> !important;
    color: <?php echo (get_option('ea-accent-colour-alt') ? esc_attr(get_option('ea-accent-colour-alt')) : '#ffffff' ); ?> !important;
}
.accent-color,
.ea a,
ul.ul-3-col li span:before {
    color: <?php echo (get_option('ea-accent-colour') ? esc_attr(get_option('ea-accent-colour')) : '#dd9933' ); ?> !important;
}
.ea .btn {
    border-color: <?php echo (get_option('ea-accent-colour') ? esc_attr(get_option('ea-accent-colour')) : '#dd9933' ); ?> !important;
}
</style>