<?php
/**
 * Renders a list of articles
 * @param  WP_Query Result $articles    A returned WP_Query object ready to be looped over
 * @param  string $list_layout See switch statement for possible layouts
 * @return null              
 */
function sc_render_article_list($articles, $list_layout = "blocks") {

	$article_count = 0;

	while( $articles->have_posts() ) {
		$articles->the_post();

		// determine post size
		switch($list_layout) {
			case 'featured':
				$class = 'featured';
				break;

			case 'blocks':
				$class = 'block';
				$block_count = $article_count;
				break;

			case 'featured-blocks':
				if($article_count == 0)
					$class = 'featured';
				else
					$class = 'block';

				$block_count = $article_count - 1;
				break;

			case 'full':
				$class = 'full';

		}

		// maybe add col counter for blocks
		if($class == 'block' && ($block_count % 2) == 0)
				$class .= ' new-row';

		// set "date" text
		if(get_post_type() == 'tribe_events')
			$date_text = tribe_get_start_date();
		else
			$date_text = 'posted ' . get_the_time('F j, Y');

		// determine photo to use. use hero unless it's not defined.
		$hero_image = get_field( 'hero_image' );
		if( !empty($hero_image) ){
			$featured_img = '<img src="' . $hero_image['sizes']['square-medium'] . '" alt="" />';
		}else{
			$featured_img = get_avatar( get_the_author_meta( 'ID' ), 300, null, get_the_author() );
		}

		?>
		<article class="post <?php echo $class . ' ' . get_post_type() ?>">
			<header>
				<h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
				<span class="date"><?php echo $date_text ?></span>
				<a href="<?php the_permalink() ?>"><?php echo $featured_img ?></a>
			</header>

			<div class="content">
				<?php the_excerpt() ?>
				<a href="<?php the_permalink() ?>" class="read-more secondary-button">Read More</a>
			</div>

		</article>
		<?php

		$article_count++;
	}

	wp_reset_postdata();
}