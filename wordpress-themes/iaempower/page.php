<?php
/**
 * Static WordPress Pages (e.g. Aviso Legal, Política de Privacidad,
 * Cookies) — without this, WordPress falls back to index.php, which is
 * an excerpt-based archive loop and only shows the first few lines.
 */
get_header();
?>
<section class="section" style="padding-top:clamp(3rem,6vw,4.5rem);">
	<div class="container" style="max-width:44rem;">
		<?php while ( have_posts() ) : the_post(); ?>
			<article>
				<p class="eyebrow"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;text-decoration:none;">← Inicio</a></p>
				<h1 style="margin-top:1rem;font-size:clamp(2rem,4vw,2.75rem);"><?php echo iaempower_title_with_emphasis( get_the_title() ); ?></h1>
				<div class="section-lede entry-content" style="margin-top:1.5rem;max-width:none;line-height:1.75;">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php get_footer(); ?>
