// index.php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    /**
     * hoangphi_homepage_content hook.
     * Chúng ta sẽ "treo" mọi section (Hero, Reels, Collection) vào đây.
     */
    do_action( 'hoangphi_homepage_content' );
    ?>
</main>

<?php get_footer(); ?>