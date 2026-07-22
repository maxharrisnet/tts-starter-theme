<?php
/**
 * Section: Team
 *
 * With exactly one team member, renders a two-column bio layout instead of a
 * card grid — a one-person business isn't a "team," it's a bio. All bio copy
 * sits in a single column beside the photo. With more than one member, falls
 * back to the standard card grid.
 *
 * Variants (via $args['variant']):
 *   summary — default. Opening copy only, plus a link to the full bio.
 *   full    — the complete bio, used on the About page.
 *
 * The summary falls back to rendering everything when there's no About page
 * to link to, so the rest of the bio never becomes unreachable.
 *
 * @package drumstudy
 */

$variant = ( isset( $args['variant'] ) && 'full' === $args['variant'] ) ? 'full' : 'summary';

$query = new WP_Query( [
	'post_type'      => 'drumstudy_team',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}

$is_solo   = 1 === $query->post_count;
$bio_url   = 'full' === $variant ? '' : drumstudy_get_template_page_url( 'templates/template-about.php' );
$show_full = 'full' === $variant || ! $bio_url;
?>
<section class="tts-section" id="team" aria-labelledby="team-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="team-heading" class="tts-section-heading__title">
				<?php echo esc_html( drumstudy_get_option( 'drumstudy_archive_header_team' ) ?: ( $is_solo ? __( 'Meet Your Instructor', 'drumstudy' ) : __( 'Meet the Team', 'drumstudy' ) ) ); ?>
			</h2>
		</div>

		<?php if ( $is_solo ) : ?>
			<?php
			$query->the_post();
			$img_id  = absint( get_post_meta( get_the_ID(), 'team_image', true ) );
			$img2_id = absint( get_post_meta( get_the_ID(), 'team_image_2', true ) );
			$role    = get_post_meta( get_the_ID(), 'role', true );

			// Split content into paragraphs. The summary variant shows only the
			// opening copy; the full variant shows all of it.
			$content    = get_the_content();
			$paragraphs = array_values( array_filter( array_map( 'trim', explode( '</p>', $content ) ) ) );
			$paragraphs = array_map( fn( $p ) => str_replace( '<p>', '', $p ), $paragraphs );

			if ( ! $show_full ) {
				$paragraphs = array_slice( $paragraphs, 0, 2 );
			}

			$highlights = [
				[ 'icon' => '🏆', 'text' => 'Pro-Level Experience: Performance background with <em>Blue Man Group</em> & <em>Powerman 5000</em>.' ],
				[ 'icon' => '🎒', 'text' => 'All Ages Welcome: Friendly, patient instruction tailored for kids (ages 7+) and adults.' ],
				[ 'icon' => '📐', 'text' => 'Ergonomics-Focused: Learn the proper hand technique, wrist rotation, and posture to prevent strain.' ],
			];

			// Built once so it can sit under the photo (balancing the image
			// column) or fall back into the text column when there's no photo.
			ob_start();
			?>
			<div class="instructor-highlights">
				<?php foreach ( $highlights as $highlight ) : ?>
					<div class="instructor-highlight">
						<div class="instructor-highlight__icon"><?php echo $highlight['icon']; ?></div>
						<div class="instructor-highlight__text"><?php echo wp_kses_post( $highlight['text'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			$highlights_html = ob_get_clean();
			?>
			<div class="flex flex-col md:flex-row items-start gap-10">
				<?php if ( $img_id ) : ?>
					<div class="md:w-1/2 w-full">
						<?php
						echo wp_get_attachment_image(
							$img_id,
							'tts-feature',
							false,
							[
								'class'   => 'w-full h-auto',
								'loading' => 'lazy',
								'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
							]
						);
						?>

						<?php if ( $show_full && $img2_id ) : ?>
							<?php
							echo wp_get_attachment_image(
								$img2_id,
								'tts-feature',
								false,
								[
									'class'   => 'w-full h-auto mt-6',
									'loading' => 'lazy',
									'alt'     => get_post_meta( $img2_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
								]
							);
							?>
						<?php endif; ?>

						<?php echo $highlights_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped above ?>
					</div>
				<?php endif; ?>

				<div class="<?php echo $img_id ? 'md:w-1/2' : ''; ?> w-full">
					<h3 class="tts-card__title text-h2"><?php the_title(); ?></h3>
					<?php if ( $role ) : ?>
						<p class="tts-card__meta mb-6"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>

					<?php
					foreach ( $paragraphs as $i => $paragraph ) {
						$class = 0 === $i ? 'font-bold mb-4' : 'mb-4';
						echo '<p class="' . esc_attr( $class ) . '">' . wp_kses_post( $paragraph ) . '</p>';
					}
					?>

					<?php if ( ! $img_id ) : ?>
						<?php echo $highlights_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped above ?>
					<?php endif; ?>

					<?php if ( ! $show_full && $bio_url ) : ?>
						<p class="mt-8">
							<a href="<?php echo esc_url( $bio_url . '#team' ); ?>" class="tts-btn tts-btn--secondary">
								<?php esc_html_e( 'Read the Full Story', 'drumstudy' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<div class="tts-grid-3">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template-parts/cards/card-team' );
				}
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
