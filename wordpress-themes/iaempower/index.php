<?php
/**
 * Blog archive: used as the "Página de entradas" once configured in
 * Ajustes → Lectura (see iaempower_maybe_suggest_posts_page()), and as
 * the fallback for search results and any other post archive.
 */
get_header();
?>
<section class="section" style="padding-top:clamp(3rem,6vw,4.5rem);">
	<div class="container">
		<div class="section-head">
			<?php if ( is_search() ) : ?>
				<p class="eyebrow">Búsqueda</p>
				<h2 data-reveal><?php printf( esc_html__( 'Resultados para: %s', 'iaempower' ), '<em>' . esc_html( get_search_query() ) . '</em>' ); ?></h2>
			<?php else : ?>
				<p class="eyebrow"><?php echo esc_html( iaempower_mod( 'recursos_eyebrow' ) ); ?></p>
				<h2 data-reveal><?php echo esc_html( iaempower_mod( 'recursos_title' ) ); ?></h2>
				<p class="section-lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'recursos_lede' ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="blog-grid">
				<?php
				$i = 0;
				while ( have_posts() ) :
					the_post();
					$word_count   = str_word_count( wp_strip_all_tags( get_the_content() ) );
					$reading_mins = max( 1, (int) round( $word_count / 200 ) );
					?>
					<article class="card blog-card" data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
						<a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( $reading_mins ); ?> min</time>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							<span class="read-more"><?php echo esc_html( iaempower_mod( 'blog_read_more' ) ); ?></span>
						</a>
					</article>
					<?php
					$i++;
				endwhile;
				?>
			</div>
			<div class="pagination">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 1,
					'prev_text' => '← Anteriores',
					'next_text' => 'Siguientes →',
				) );
				?>
			</div>
		<?php else : ?>
			<p class="section-lede"><?php echo is_search() ? 'No se ha encontrado ningún artículo con esa búsqueda.' : 'Todavía no hay artículos publicados. Vuelve pronto.'; ?></p>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
