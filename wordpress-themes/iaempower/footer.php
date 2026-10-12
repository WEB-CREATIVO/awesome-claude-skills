	</main>

	<footer class="site-footer">
		<div class="container">
			<div class="footer-inner">
				<div class="footer-brand">
					<img class="brand-mark" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/iaempower-icon.png' ); ?>" alt="" aria-hidden="true">
					<img class="footer-wordmark" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/iaempower-wordmark.png' ); ?>" alt="<?php bloginfo( 'name' ); ?> — Intelligent Solutions">
				</div>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav aria-label="Pie de página">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'container'      => false,
							'items_wrap'     => '<ul class="footer-links">%3$s</ul>',
							'depth'          => 1,
						) );
						?>
					</nav>
				<?php else : ?>
					<nav aria-label="Pie de página">
						<ul class="footer-links">
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#servicios' ) ); ?>">Servicios</a></li>
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#casos' ) ); ?>">Casos</a></li>
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#equipo' ) ); ?>">Equipo</a></li>
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#precios' ) ); ?>">Precios</a></li>
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#recursos' ) ); ?>">Recursos</a></li>
							<li><a href="<?php echo esc_url( iaempower_blog_url() ); ?>">Blog</a></li>
							<li><a href="<?php echo esc_url( iaempower_anchor_url( '#contacto' ) ); ?>">Contacto</a></li>
						</ul>
					</nav>
				<?php endif; ?>
				<?php $social_links = iaempower_social_links(); ?>
				<?php if ( $social_links ) : ?>
					<ul class="footer-social">
						<?php
						$social_labels = array(
							'linkedin'  => 'LinkedIn',
							'instagram' => 'Instagram',
							'tiktok'    => 'TikTok',
						);
						foreach ( $social_links as $platform => $url ) :
							?>
							<li>
								<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social_labels[ $platform ] ?? $platform ); ?>">
									<?php echo iaempower_social_icon_svg( $platform ); ?>
								</a>
							</li>
							<?php
						endforeach;
						?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="footer-meta">
				<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> · <?php echo esc_html( iaempower_mod( 'contact_address' ) ); ?> · <a href="<?php echo esc_url( iaempower_anchor_url( '#contacto' ) ); ?>" id="footer_credit_text" class="footer-credit-link"><?php echo esc_html( iaempower_mod( 'footer_credit_text' ) ); ?></a></span>
				<ul class="footer-legal-links">
					<li><a href="<?php echo esc_url( iaempower_mod( 'legal_url_aviso' ) ); ?>">Aviso legal</a></li>
					<li><a href="<?php echo esc_url( iaempower_mod( 'legal_url_privacidad' ) ); ?>">Política de privacidad</a></li>
					<li><a href="<?php echo esc_url( iaempower_mod( 'legal_url_cookies' ) ); ?>">Cookies</a></li>
				</ul>
			</div>
		</div>
		<?php if ( iaempower_mod( 'ai_transparency_enabled' ) ) : ?>
			<div class="container">
				<p class="footer-ai-note"><?php echo esc_html( iaempower_mod( 'ai_transparency_text' ) ); ?></p>
			</div>
		<?php endif; ?>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
