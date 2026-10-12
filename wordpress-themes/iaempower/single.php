<?php
/**
 * Single blog post — used by the Recursos section links. Kept as a
 * plain, readable article page in the same visual language as the
 * front page rather than a full section-by-section layout.
 */
get_header();
?>
<section class="section" style="padding-top:clamp(3rem,6vw,4.5rem);">
	<div class="container" style="max-width:44rem;">
		<?php while ( have_posts() ) : the_post(); ?>
			<article>
				<p class="eyebrow"><a href="<?php echo esc_url( home_url( '/#recursos' ) ); ?>" style="color:inherit;text-decoration:none;">← Recursos</a></p>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" style="font-family:'JetBrains Mono',monospace;font-size:.75rem;color:var(--text-muted);display:block;margin-top:1rem;">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
				<h1 style="margin-top:.75rem;font-size:clamp(2rem,4vw,2.75rem);"><?php echo iaempower_title_with_emphasis( get_the_title() ); ?></h1>
				<div class="section-lede entry-content" style="margin-top:1.5rem;max-width:none;line-height:1.75;">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php get_footer(); ?>
