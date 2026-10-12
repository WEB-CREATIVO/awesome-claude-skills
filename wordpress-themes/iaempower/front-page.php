<?php
/**
 * The one-page IAEMPOWER site. Repeatable sections (servicios, casos,
 * equipo, precios, faq, recursos) pull from their custom post types;
 * everything else comes from the Customizer (see inc/customizer.php).
 * Most text elements below carry an id="<mod id>" — that's what lets
 * inc/customizer.php register a Selective Refresh partial for them, so
 * clicking the text in the Customizer's live preview jumps straight to
 * its field on the left.
 */
get_header();

$contact_status = isset( $_GET['iaempower_contact'] ) ? sanitize_key( $_GET['iaempower_contact'] ) : '';
?>

<div class="section-nav" id="sectionNav" aria-hidden="true">
	<button type="button" class="section-nav-btn" data-scroll="prev" tabindex="-1" title="Sección anterior">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 15l-6-6-6 6"></path></svg>
	</button>
	<button type="button" class="section-nav-btn" data-scroll="next" tabindex="-1" title="Siguiente sección">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"></path></svg>
	</button>
</div>

<section id="inicio" class="hero">
	<canvas class="hero-canvas" id="heroCanvas" aria-hidden="true"></canvas>
	<div class="hero-inner">
		<div class="hero-grid">
			<div class="hero-copy">
				<p class="eyebrow" id="hero_eyebrow"><?php echo esc_html( iaempower_mod( 'hero_eyebrow' ) ); ?></p>
				<h1 data-reveal>
					<span id="hero_headline_plain"><?php echo esc_html( iaempower_mod( 'hero_headline_plain' ) ); ?></span>
					<em id="hero_headline_emphasis"><?php echo esc_html( iaempower_mod( 'hero_headline_emphasis' ) ); ?></em>.
				</h1>
				<p class="hero-lede" id="hero_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'hero_lede' ) ); ?></p>
				<div class="hero-actions" data-reveal style="--i:2">
					<a href="#contacto" class="btn btn-accent" id="hero_cta_primary" data-magnetic><?php echo esc_html( iaempower_mod( 'hero_cta_primary' ) ); ?></a>
					<a href="#servicios" class="btn btn-ghost" id="hero_cta_secondary"><?php echo esc_html( iaempower_mod( 'hero_cta_secondary' ) ); ?></a>
				</div>
				<dl class="hero-stats" data-reveal style="--i:3">
					<div>
						<dt id="hero_stat1_label"><?php echo esc_html( iaempower_mod( 'hero_stat1_label' ) ); ?></dt>
						<dd id="hero_stat1_dd" data-count="<?php echo esc_attr( iaempower_mod( 'hero_stat1_value' ) ); ?>" data-suffix="<?php echo esc_attr( iaempower_mod( 'hero_stat1_suffix' ) ); ?>">0</dd>
					</div>
					<div>
						<dt id="hero_stat2_label"><?php echo esc_html( iaempower_mod( 'hero_stat2_label' ) ); ?></dt>
						<dd id="hero_stat2_dd" data-count="<?php echo esc_attr( iaempower_mod( 'hero_stat2_value' ) ); ?>" data-suffix="<?php echo esc_attr( iaempower_mod( 'hero_stat2_suffix' ) ); ?>">0</dd>
					</div>
					<div>
						<dt id="hero_stat3_label"><?php echo esc_html( iaempower_mod( 'hero_stat3_label' ) ); ?></dt>
						<dd id="hero_stat3_dd" data-count="<?php echo esc_attr( iaempower_mod( 'hero_stat3_value' ) ); ?>" data-suffix="<?php echo esc_attr( iaempower_mod( 'hero_stat3_suffix' ) ); ?>">0</dd>
					</div>
				</dl>
			</div>
			<aside class="hero-card" data-reveal style="--i:2" aria-label="<?php echo esc_attr( iaempower_mod( 'hero_card_meta' ) ); ?>">
				<div class="hero-card-meta">
					<span id="hero_card_meta"><?php echo esc_html( iaempower_mod( 'hero_card_meta' ) ); ?></span>
					<span class="hero-card-status" id="hero_card_status"><?php echo esc_html( iaempower_mod( 'hero_card_status' ) ); ?></span>
				</div>
				<?php
				$hero_card_title_lines = explode( "\n", iaempower_mod( 'hero_card_title' ), 2 );
				$hero_card_title_lead  = trim( $hero_card_title_lines[0] );
				$hero_card_title_rest  = isset( $hero_card_title_lines[1] ) ? trim( $hero_card_title_lines[1] ) : '';
				?>
				<h3 id="hero_card_title"><?php echo esc_html( $hero_card_title_lead ); ?><?php if ( $hero_card_title_rest !== '' ) : ?><br><em><?php echo esc_html( $hero_card_title_rest ); ?></em><?php endif; ?></h3>
				<div class="hero-card-bars" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
				<div class="hero-card-data">
					<span>
						<span id="hero_card_metric1_label"><?php echo esc_html( iaempower_mod( 'hero_card_metric1_label' ) ); ?></span>
						<b id="hero_card_metric1_value"><?php echo esc_html( iaempower_mod( 'hero_card_metric1_value' ) ); ?></b>
					</span>
					<span>
						<span id="hero_card_metric2_label"><?php echo esc_html( iaempower_mod( 'hero_card_metric2_label' ) ); ?></span>
						<b id="hero_card_metric2_value"><?php echo esc_html( iaempower_mod( 'hero_card_metric2_value' ) ); ?></b>
					</span>
				</div>
			</aside>
		</div>
	</div>
</section>

<?php $marquee_items = array_filter( array_map( 'trim', explode( "\n", iaempower_mod( 'marquee_items' ) ) ) ); ?>
<div class="marquee-wrap">
	<span class="marquee-label" id="marquee_label"><?php echo esc_html( iaempower_mod( 'marquee_label' ) ); ?></span>
	<div class="marquee-track" aria-hidden="true">
		<?php foreach ( array_merge( $marquee_items, $marquee_items ) as $item ) : ?>
			<span><?php echo esc_html( $item ); ?></span>
		<?php endforeach; ?>
	</div>
</div>

<section id="pilares" class="section">
	<div class="mesh"></div>
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="pilares_eyebrow"><?php echo esc_html( iaempower_mod( 'pilares_eyebrow' ) ); ?></p>
			<h2 data-reveal id="pilares_title"><?php echo esc_html( iaempower_mod( 'pilares_title' ) ); ?></h2>
			<p class="section-lede" id="pilares_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'pilares_lede' ) ); ?></p>
		</div>
		<div class="pillars-grid">
			<article class="card pillar-lead" data-tilt data-reveal style="--i:0">
				<span class="pillar-tag" id="pillar1_tag"><?php echo esc_html( iaempower_mod( 'pillar1_tag' ) ); ?></span>
				<h3 id="pillar1_title"><?php echo esc_html( iaempower_mod( 'pillar1_title' ) ); ?></h3>
				<p id="pillar1_text"><?php echo esc_html( iaempower_mod( 'pillar1_text' ) ); ?></p>
			</article>
			<article class="card pillar-b" data-tilt data-reveal style="--i:1">
				<span class="pillar-tag" id="pillar2_tag"><?php echo esc_html( iaempower_mod( 'pillar2_tag' ) ); ?></span>
				<h3 id="pillar2_title"><?php echo esc_html( iaempower_mod( 'pillar2_title' ) ); ?></h3>
				<p id="pillar2_text"><?php echo esc_html( iaempower_mod( 'pillar2_text' ) ); ?></p>
			</article>
			<article class="card pillar-c" data-tilt data-reveal style="--i:2">
				<span class="pillar-tag" id="pillar3_tag"><?php echo esc_html( iaempower_mod( 'pillar3_tag' ) ); ?></span>
				<h3 id="pillar3_title"><?php echo esc_html( iaempower_mod( 'pillar3_title' ) ); ?></h3>
				<p id="pillar3_text"><?php echo esc_html( iaempower_mod( 'pillar3_text' ) ); ?></p>
			</article>
			<article class="card pillar-d" data-reveal style="--i:3">
				<div>
					<span class="pillar-tag" id="pillar4_tag"><?php echo esc_html( iaempower_mod( 'pillar4_tag' ) ); ?></span>
					<h3 id="pillar4_title"><?php echo esc_html( iaempower_mod( 'pillar4_title' ) ); ?></h3>
				</div>
				<div class="pillar-d-metric">
					<span class="value" id="pillar4_value"><?php echo esc_html( iaempower_mod( 'pillar4_value' ) ); ?></span>
					<span class="label" id="pillar4_label"><?php echo esc_html( iaempower_mod( 'pillar4_label' ) ); ?></span>
				</div>
			</article>
		</div>
	</div>
</section>

<section id="contraste" class="section section-alt">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="contraste_eyebrow"><?php echo esc_html( iaempower_mod( 'contraste_eyebrow' ) ); ?></p>
			<h2 data-reveal id="contraste_title"><?php echo esc_html( iaempower_mod( 'contraste_title' ) ); ?></h2>
			<p class="section-lede" id="contraste_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'contraste_lede' ) ); ?></p>
		</div>
		<div class="contrast-grid">
			<div class="contrast-col contrast-before" data-reveal style="--i:0">
				<h3 id="contrast_before_title"><?php echo esc_html( iaempower_mod( 'contrast_before_title' ) ); ?></h3>
				<ul>
					<?php foreach ( array_filter( array_map( 'trim', explode( "\n", iaempower_mod( 'contrast_before_items' ) ) ) ) as $item ) : ?>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 6l12 12M18 6L6 18"></path></svg><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="contrast-vs" id="contrast_vs_label" aria-hidden="true"><?php echo esc_html( iaempower_mod( 'contrast_vs_label' ) ); ?></div>
			<div class="contrast-col contrast-after" data-reveal style="--i:1">
				<h3 id="contrast_after_title"><?php echo esc_html( iaempower_mod( 'contrast_after_title' ) ); ?></h3>
				<ul>
					<?php foreach ( array_filter( array_map( 'trim', explode( "\n", iaempower_mod( 'contrast_after_items' ) ) ) ) as $item ) : ?>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 13l4 4L19 7"></path></svg><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php if ( iaempower_mod( 'leak_enabled' ) ) : ?>
<section id="calculadora" class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="leak_eyebrow"><?php echo esc_html( iaempower_mod( 'leak_eyebrow' ) ); ?></p>
			<h2 data-reveal id="leak_title"><?php echo esc_html( iaempower_mod( 'leak_title' ) ); ?></h2>
			<p class="section-lede" id="leak_lead" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'leak_lead' ) ); ?></p>
		</div>
		<div class="leak-calc" id="leakCalculator" data-reveal style="--i:2"
			data-weeks="<?php echo esc_attr( iaempower_mod( 'leak_weeks_per_year' ) ); ?>"
			data-recovery="<?php echo esc_attr( iaempower_mod( 'leak_recovery_pct' ) ); ?>"
			data-investment="<?php echo esc_attr( iaempower_mod( 'leak_avg_investment' ) ); ?>">
			<div class="hero-card-meta leak-calc-meta">
				<span>Estimador de fuga operativa</span>
				<span class="hero-card-status">En vivo</span>
			</div>
			<div class="leak-fields">
				<div class="leak-field">
					<div class="leak-field-head">
						<label for="leakHours">Horas manuales / semana</label>
						<b id="leakHoursOut">0 h</b>
					</div>
					<input type="range" id="leakHours" min="1" max="40" step="1" value="<?php echo esc_attr( iaempower_mod( 'leak_hours_default' ) ); ?>">
				</div>
				<div class="leak-field">
					<div class="leak-field-head">
						<label for="leakPeople">Personas implicadas</label>
						<b id="leakPeopleOut">0</b>
					</div>
					<input type="range" id="leakPeople" min="1" max="10" step="1" value="<?php echo esc_attr( iaempower_mod( 'leak_people_default' ) ); ?>">
				</div>
				<div class="leak-field">
					<div class="leak-field-head">
						<label for="leakCost">Coste hora medio</label>
						<b id="leakCostOut">0 €</b>
					</div>
					<input type="range" id="leakCost" min="10" max="500" step="5" value="<?php echo esc_attr( iaempower_mod( 'leak_cost_default' ) ); ?>">
				</div>
			</div>
			<div class="leak-results" aria-live="polite">
				<div>
					<span>Coste anual estimado</span>
					<b id="leakAnnualCost">—</b>
				</div>
				<div>
					<span>Recuperable con IAEMPOWER</span>
					<b id="leakRecoverable">—</b>
				</div>
			</div>
			<p class="leak-payback">Payback estimado: <strong id="leakPayback">—</strong></p>
			<p class="leak-disclaimer" id="leak_disclaimer"><?php echo esc_html( iaempower_mod( 'leak_disclaimer' ) ); ?></p>
			<a href="#contacto" class="btn btn-accent" id="leak_cta"><?php echo esc_html( iaempower_mod( 'leak_cta' ) ); ?></a>
		</div>
	</div>
</section>
<?php endif; ?>

<section id="servicios" class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="servicios_eyebrow"><?php echo esc_html( iaempower_mod( 'servicios_eyebrow' ) ); ?></p>
			<h2 data-reveal id="servicios_title"><?php echo esc_html( iaempower_mod( 'servicios_title' ) ); ?></h2>
			<p class="section-lede" id="servicios_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'servicios_lede' ) ); ?></p>
		</div>
		<div class="services-grid">
			<?php
			$services = new WP_Query( array(
				'post_type'      => 'iaempower_service',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );
			$i = 0;
			while ( $services->have_posts() ) :
				$services->the_post();
				?>
				<article class="card service-card" data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
					<?php echo iaempower_service_icon_svg( iaempower_meta( get_the_ID(), 'icon', 'agent' ) ); // phpcs:ignore ?>
					<h3><?php the_title(); ?></h3>
					<div class="service-desc"><?php echo wp_kses_post( wpautop( has_excerpt() ? get_the_excerpt() : get_the_content() ) ); ?></div>
				</article>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section class="statement section-alt">
	<div class="mesh"></div>
	<div class="container">
		<blockquote data-reveal>
			<p id="manifesto_quote"><?php echo nl2br( esc_html( iaempower_mod( 'manifesto_quote' ) ) ); ?></p>
			<cite id="manifesto_cite"><?php echo esc_html( iaempower_mod( 'manifesto_cite' ) ); ?></cite>
		</blockquote>
	</div>
</section>

<section id="arquitectura" class="section">
	<div class="container architecture-grid">
		<div class="architecture-copy">
			<p class="eyebrow" id="architecture_eyebrow"><?php echo esc_html( iaempower_mod( 'architecture_eyebrow' ) ); ?></p>
			<h2 data-reveal id="architecture_title"><?php echo esc_html( iaempower_mod( 'architecture_title' ) ); ?></h2>
			<p class="section-lede" id="architecture_lead" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'architecture_lead' ) ); ?></p>
			<a href="#contacto" class="btn btn-accent" id="architecture_cta" data-reveal style="--i:2"><?php echo esc_html( iaempower_mod( 'architecture_cta' ) ); ?></a>
		</div>
		<div class="architecture-map" data-reveal style="--i:2" aria-label="Diagrama: personas y datos conectados por IA producen un resultado">
			<span class="architecture-node architecture-node-a" id="architecture_node1"><?php echo esc_html( iaempower_mod( 'architecture_node1' ) ); ?></span>
			<span class="architecture-node architecture-node-b" id="architecture_node2"><?php echo esc_html( iaempower_mod( 'architecture_node2' ) ); ?></span>
			<span class="architecture-node architecture-node-c" id="architecture_node3"><?php echo esc_html( iaempower_mod( 'architecture_node3' ) ); ?></span>
			<span class="architecture-node architecture-node-d" id="architecture_node4"><?php echo esc_html( iaempower_mod( 'architecture_node4' ) ); ?></span>
		</div>
	</div>
</section>

<section id="casos" class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="casos_eyebrow"><?php echo esc_html( iaempower_mod( 'casos_eyebrow' ) ); ?></p>
			<h2 data-reveal id="casos_title"><?php echo esc_html( iaempower_mod( 'casos_title' ) ); ?></h2>
			<p class="section-lede" id="casos_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'casos_lede' ) ); ?></p>
		</div>
		<div class="cases-grid">
			<?php
			$cases = new WP_Query( array(
				'post_type'      => 'iaempower_case',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );
			$i = 0;
			while ( $cases->have_posts() ) :
				$cases->the_post();
				$case_id = get_the_ID();
				?>
				<article class="card case-card" data-tilt data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
					<p class="sector"><?php echo esc_html( iaempower_meta( $case_id, 'sector' ) ); ?></p>
					<h3><?php the_title(); ?></h3>
					<div class="case-row">
						<p><b class="case-reto-label"><?php echo esc_html( iaempower_mod( 'case_reto_label' ) ); ?></b> <?php echo esc_html( iaempower_meta( $case_id, 'reto' ) ); ?></p>
						<p><b class="case-solucion-label"><?php echo esc_html( iaempower_mod( 'case_solucion_label' ) ); ?></b> <?php echo esc_html( iaempower_meta( $case_id, 'solucion' ) ); ?></p>
					</div>
					<div class="case-metric">
						<span class="value" data-count="<?php echo esc_attr( iaempower_meta( $case_id, 'metric_value', '0' ) ); ?>" data-prefix="<?php echo esc_attr( iaempower_meta( $case_id, 'metric_prefix' ) ); ?>" data-suffix="<?php echo esc_attr( iaempower_meta( $case_id, 'metric_suffix' ) ); ?>"><?php echo esc_html( iaempower_meta( $case_id, 'metric_prefix' ) . iaempower_meta( $case_id, 'metric_value', '0' ) . iaempower_meta( $case_id, 'metric_suffix' ) ); ?></span>
						<span class="label"><?php echo esc_html( iaempower_meta( $case_id, 'metric_label' ) ); ?></span>
					</div>
				</article>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section id="equipo" class="section section-alt">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="equipo_eyebrow"><?php echo esc_html( iaempower_mod( 'equipo_eyebrow' ) ); ?></p>
			<h2 data-reveal id="equipo_title"><?php echo esc_html( iaempower_mod( 'equipo_title' ) ); ?></h2>
			<p class="section-lede" id="equipo_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'equipo_lede' ) ); ?></p>
		</div>
		<div class="team-grid">
			<?php
			$team = new WP_Query( array(
				'post_type'      => 'iaempower_team',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );
			$i = 0;
			while ( $team->have_posts() ) :
				$team->the_post();
				?>
				<?php
				$photo_id  = absint( iaempower_meta( get_the_ID(), 'photo' ) );
				$has_photo = $photo_id || has_post_thumbnail();
				?>
				<article class="team-card" data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
					<?php if ( $has_photo ) : ?>
						<div class="avatar-photo">
							<?php if ( $photo_id ) : ?>
								<?php echo wp_get_attachment_image( $photo_id, 'medium', false, array( 'alt' => get_the_title() ) ); ?>
							<?php else : ?>
								<?php the_post_thumbnail( 'medium' ); ?>
							<?php endif; ?>
						</div>
					<?php else : ?>
						<div class="avatar">
							<?php echo iaempower_team_avatar_svg( $i ); // phpcs:ignore ?>
						</div>
					<?php endif; ?>
					<h3><?php the_title(); ?></h3>
					<span class="role"><?php echo esc_html( iaempower_meta( get_the_ID(), 'role' ) ); ?></span>
					<div class="bio"><?php the_content(); ?></div>
				</article>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section id="precios" class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="precios_eyebrow"><?php echo esc_html( iaempower_mod( 'precios_eyebrow' ) ); ?></p>
			<h2 data-reveal id="precios_title"><?php echo esc_html( iaempower_mod( 'precios_title' ) ); ?></h2>
			<p class="section-lede" id="precios_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'precios_lede' ) ); ?></p>
		</div>
		<div class="pricing-grid">
			<?php
			$prices = new WP_Query( array(
				'post_type'      => 'iaempower_price',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );
			$i = 0;
			while ( $prices->have_posts() ) :
				$prices->the_post();
				$price_id   = get_the_ID();
				$featured   = iaempower_meta( $price_id, 'featured' ) === '1';
				$features   = array_filter( array_map( 'trim', explode( "\n", iaempower_meta( $price_id, 'features' ) ) ) );
				?>
				<article class="card price-card<?php echo $featured ? ' is-featured' : ''; ?>" data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
					<span class="price-tag"><?php echo esc_html( iaempower_meta( $price_id, 'tag_label' ) ); ?></span>
					<h3><?php the_title(); ?></h3>
					<p class="tagline"><?php echo esc_html( iaempower_meta( $price_id, 'tagline' ) ); ?></p>
					<p class="amount"><?php echo esc_html( iaempower_meta( $price_id, 'amount' ) ); ?> <span><?php echo esc_html( iaempower_meta( $price_id, 'amount_unit' ) ); ?></span></p>
					<ul>
						<?php foreach ( $features as $feature ) : ?>
							<li><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="#contacto" class="btn <?php echo $featured ? 'btn-accent' : 'btn-ghost'; ?>"<?php echo $featured ? ' data-magnetic' : ''; ?>><?php echo esc_html( iaempower_meta( $price_id, 'cta_label', 'Empezar aquí' ) ); ?></a>
				</article>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section id="objeciones" class="section section-alt">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="objeciones_eyebrow"><?php echo esc_html( iaempower_mod( 'objeciones_eyebrow' ) ); ?></p>
			<h2 data-reveal id="objeciones_title"><?php echo esc_html( iaempower_mod( 'objeciones_title' ) ); ?></h2>
			<p class="section-lede" id="objeciones_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'objeciones_lede' ) ); ?></p>
		</div>
		<div class="faq-list">
			<?php
			$faqs = new WP_Query( array(
				'post_type'      => 'iaempower_faq',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );
			$i = 0;
			while ( $faqs->have_posts() ) :
				$faqs->the_post();
				$faq_id = 'faq-' . get_the_ID();
				?>
				<div class="faq-item" data-reveal style="--i:<?php echo esc_attr( $i % 4 ); ?>">
					<button class="faq-q" id="<?php echo esc_attr( $faq_id ); ?>-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>-a">
						<?php the_title(); ?><span class="plus"></span>
					</button>
					<div class="faq-a-wrap" id="<?php echo esc_attr( $faq_id ); ?>-a" role="region" aria-labelledby="<?php echo esc_attr( $faq_id ); ?>-q">
						<div class="faq-a"><?php the_content(); ?></div>
					</div>
				</div>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section id="recursos" class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow" id="recursos_eyebrow"><?php echo esc_html( iaempower_mod( 'recursos_eyebrow' ) ); ?></p>
			<h2 data-reveal id="recursos_title"><?php echo esc_html( iaempower_mod( 'recursos_title' ) ); ?></h2>
			<p class="section-lede" id="recursos_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'recursos_lede' ) ); ?></p>
		</div>
		<div class="blog-grid">
			<?php
			$posts = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 4,
			) );
			$i = 0;
			while ( $posts->have_posts() ) :
				$posts->the_post();
				$word_count   = str_word_count( wp_strip_all_tags( get_the_content() ) );
				$reading_mins = max( 1, (int) round( $word_count / 200 ) );
				?>
				<article class="card blog-card" data-reveal style="--i:<?php echo esc_attr( $i % 3 ); ?>">
					<a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( $reading_mins ); ?> min</time>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						<span class="read-more blog-read-more"><?php echo esc_html( iaempower_mod( 'blog_read_more' ) ); ?></span>
					</a>
				</article>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<section id="contacto" class="section section-alt section-contact">
	<div class="container">
		<div class="contact-grid">
			<div class="contact-info">
				<p class="eyebrow" id="contacto_eyebrow"><?php echo esc_html( iaempower_mod( 'contacto_eyebrow' ) ); ?></p>
				<h2 data-reveal id="contacto_title"><?php echo esc_html( iaempower_mod( 'contacto_title' ) ); ?></h2>
				<p class="lede" id="contacto_lede" data-reveal style="--i:1"><?php echo esc_html( iaempower_mod( 'contacto_lede' ) ); ?></p>
				<a class="contact-mark" data-reveal style="--i:2" href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( iaempower_mod( 'contact_address' ) ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( iaempower_mod( 'contact_mark_aria' ) ); ?> (abre Google Maps)">
					<svg viewBox="0 0 320 180" aria-hidden="true">
						<rect width="320" height="180" fill="var(--ground)"></rect>
						<polygon points="0,180 40,95 80,140 130,60 175,120 210,85 260,150 320,110 320,180" fill="var(--ground-2)"></polygon>
						<circle cx="175" cy="120" r="5" fill="var(--accent)"></circle>
						<circle cx="175" cy="120" r="10" fill="none" stroke="var(--accent)" stroke-width="1" opacity=".6"></circle>
						<line x1="175" y1="120" x2="175" y2="60" stroke="var(--accent)" stroke-width="1" stroke-dasharray="2 3" opacity=".5"></line>
						<text x="20" y="30" id="contact_mark_caption" fill="var(--accent)" font-family="JetBrains Mono, monospace" font-size="11" letter-spacing="1"><?php echo esc_html( iaempower_mod( 'contact_mark_caption' ) ); ?></text>
					</svg>
				</a>
				<dl class="contact-facts" data-reveal style="--i:3">
					<div><dt id="contact_label_address"><?php echo esc_html( iaempower_mod( 'contact_label_address' ) ); ?></dt><dd id="contact_address"><?php echo esc_html( iaempower_mod( 'contact_address' ) ); ?></dd></div>
					<div><dt id="contact_label_email"><?php echo esc_html( iaempower_mod( 'contact_label_email' ) ); ?></dt><dd><a href="mailto:<?php echo esc_attr( iaempower_mod( 'contact_email' ) ); ?>" id="contact_email"><?php echo esc_html( iaempower_mod( 'contact_email' ) ); ?></a></dd></div>
					<div><dt id="contact_label_phone"><?php echo esc_html( iaempower_mod( 'contact_label_phone' ) ); ?></dt><dd>
						<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', iaempower_mod( 'contact_phone1' ) ) ); ?>" id="contact_phone1"><?php echo esc_html( iaempower_mod( 'contact_phone1' ) ); ?></a><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', iaempower_mod( 'contact_phone2' ) ) ); ?>" id="contact_phone2"><?php echo esc_html( iaempower_mod( 'contact_phone2' ) ); ?></a>
					</dd></div>
					<div><dt id="contact_label_hours"><?php echo esc_html( iaempower_mod( 'contact_label_hours' ) ); ?></dt><dd id="contact_hours"><?php echo esc_html( iaempower_mod( 'contact_hours' ) ); ?></dd></div>
				</dl>
			</div>

			<form class="contact-form<?php echo $contact_status === 'success' ? ' is-submitted' : ''; ?>" id="contactForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-reveal style="--i:2" novalidate>
				<input type="hidden" name="action" value="iaempower_contact">
				<?php wp_nonce_field( 'iaempower_contact', 'iaempower_contact_nonce' ); ?>
				<div class="form-fields">
					<div class="form-row">
						<div class="field"><label for="f-name" id="form_label_name"><?php echo esc_html( iaempower_mod( 'form_label_name' ) ); ?></label><input id="f-name" name="name" type="text" required autocomplete="name"></div>
						<div class="field"><label for="f-email" id="form_label_email"><?php echo esc_html( iaempower_mod( 'form_label_email' ) ); ?></label><input id="f-email" name="email" type="email" required autocomplete="email"></div>
					</div>
					<div class="form-row">
						<div class="field"><label for="f-company" id="form_label_company"><?php echo esc_html( iaempower_mod( 'form_label_company' ) ); ?></label><input id="f-company" name="company" type="text" autocomplete="organization"></div>
						<div class="field"><label for="f-phone" id="form_label_phone"><?php echo esc_html( iaempower_mod( 'form_label_phone' ) ); ?></label><input id="f-phone" name="phone" type="tel" autocomplete="tel"></div>
					</div>
					<div class="field">
						<label for="f-message" id="form_label_message"><?php echo esc_html( iaempower_mod( 'form_label_message' ) ); ?></label>
						<textarea id="f-message" name="message" required></textarea>
					</div>
					<p class="form-privacy-notice" id="form_privacy_notice"><?php echo esc_html( iaempower_mod( 'form_privacy_notice' ) ); ?></p>
					<div class="field-check">
						<input id="f-consent" name="consent" type="checkbox" required>
						<label for="f-consent" id="form_consent_text"><?php echo esc_html( iaempower_mod( 'form_consent_text' ) ); ?> <a href="<?php echo esc_url( iaempower_mod( 'legal_url_privacidad' ) ); ?>" target="_blank" rel="noopener">Política de privacidad</a>.</label>
					</div>
					<button type="submit" class="btn btn-accent" id="form_submit_label" data-magnetic><?php echo esc_html( iaempower_mod( 'form_submit_label' ) ); ?></button>
					<p class="form-note" id="form_note"><?php echo esc_html( iaempower_mod( 'form_note' ) ); ?></p>
					<?php if ( $contact_status === 'error' ) : ?>
						<p class="form-note" style="color:var(--danger-muted);"><span id="form_error_text"><?php echo esc_html( iaempower_mod( 'form_error_text' ) ); ?></span> <?php echo esc_html( iaempower_mod( 'contact_email' ) ); ?>.</p>
					<?php endif; ?>
				</div>

				<div class="form-success<?php echo $contact_status === 'success' ? ' is-visible' : ''; ?>" id="formSuccess">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle><path d="M8 12l3 3 5-6"></path>
					</svg>
					<h3 id="form_success_title"><?php echo esc_html( iaempower_mod( 'form_success_title' ) ); ?></h3>
					<p id="form_success_text"><?php echo esc_html( iaempower_mod( 'form_success_text' ) ); ?></p>
				</div>
			</form>
		</div>
	</div>
</section>

<?php get_footer(); ?>
