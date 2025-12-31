<?php
/**
 * Template for displaying all pages
 *
 * @package HoangPhi_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hoangphi' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();

