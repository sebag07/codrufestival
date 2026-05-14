<?php

get_header();
?>

<main id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div id="left-area">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <h1 class="entry-title main_title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h1>

                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'entry-featured-image')); ?>
                                </a>
                            <?php endif; ?>

                            <div class="entry-content">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>

                    <?php the_posts_pagination(); ?>
                <?php else : ?>
                    <article class="no-results">
                        <h1><?php esc_html_e('Nothing Found', 'codrufestival'); ?></h1>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
