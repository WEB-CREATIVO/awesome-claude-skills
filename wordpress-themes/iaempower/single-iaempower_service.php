<?php
/**
 * Single service page (/servicios/nombre/) — same layout as page.php,
 * plus a call to action to the contact form.
 */
get_header();
?>
<section class="section" style="padding-top:clamp(3rem,6vw,4.5rem);">
	<div class="container" style="max-width:44rem;">
		<?php while ( have_posts() ) : the_post(); ?>
			<article>
				<p class="eyebrow"><a href="<?php echo esc_url( home_url( '/#servicios' ) ); ?>" style="color:inherit;text-decoration:none;">← Servicios</a></p>
				<?php echo iaempower_service_icon_svg( iaempower_meta( get_the_ID(), 'icon', 'agent' ) ); // phpcs:ignore ?>
				<h1 style="margin-top:1rem;font-size:clamp(2rem,4vw,2.75rem);"><?php echo iaempower_title_with_emphasis( get_the_title() ); ?></h1>
				<?php if ( has_post_thumbnail() ) : ?>
					<div style="margin-top:1.5rem;"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;border-radius:12px;' ) ); ?></div>
				<?php endif; ?>
				<div class="section-lede entry-content" style="margin-top:1.5rem;max-width:none;line-height:1.75;">
					<?php the_content(); ?>
				</div>
				<p style="margin-top:2.5rem;">
					<a href="<?php echo esc_url( home_url( '/#contacto' ) ); ?>" class="btn btn-accent" data-gtag="click" data-gtag-event="service_cta_click">Hablemos de tu proyecto</a>
				</p>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php get_footer(); ?>
