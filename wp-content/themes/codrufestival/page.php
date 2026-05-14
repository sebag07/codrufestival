<?php

get_header();

?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title main_title"><?php the_title(); ?></h1>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'entry-featured-image' ) ); ?>
					<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();
						wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'codrufestival' ), 'after' => '</div>' ) );
					?>
					</div>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>

				</article>
			<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php

get_footer();
