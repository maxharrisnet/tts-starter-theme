<?php
/**
 * Section: FAQs — accessible accordion
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_faq',
	'posts_per_page' => -1,
	'no_found_rows'  => true,
	'orderby'        => 'meta_value_num',
	'meta_key'       => 'display_order',
	'order'          => 'ASC',
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="faqs" aria-labelledby="faqs-heading">
	<div class="tts-container-prose">
		<div class="tts-section-heading">
			<h2 id="faqs-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Frequently Asked Questions', 'drumstudy' ); ?>
			</h2>
		</div>

		<div class="tts-faq-list">
			<?php
			$i = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				$i++;
				$panel_id = 'faq-panel-' . $i;
				$answer   = get_post_meta( get_the_ID(), 'answer', true );
				?>
				<div class="tts-faq-item">
					<button class="tts-faq__trigger"
					        aria-expanded="false"
					        aria-controls="<?php echo esc_attr( $panel_id ); ?>">
						<?php the_title(); ?>
					</button>
					<div id="<?php echo esc_attr( $panel_id ); ?>"
					     class="tts-faq__panel"
					     hidden>
						<?php echo wp_kses_post( $answer ); ?>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
