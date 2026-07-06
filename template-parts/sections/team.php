<?php
/**
 * Section: Team
 *
 * With exactly one team member, renders a large two-column bio layout instead
 * of a card grid — a one-person business isn't a "team," it's a bio. With
 * more than one member, falls back to the standard card grid.
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_team',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}

$is_solo = 1 === $query->post_count;
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
			$img_id = absint( get_post_meta( get_the_ID(), 'team_image', true ) );
			$role   = get_post_meta( get_the_ID(), 'role', true );
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
					</div>
				<?php endif; ?>
				<div class="<?php echo $img_id ? 'md:w-1/2' : ''; ?> w-full">
					<h3 class="tts-card__title text-h2"><?php the_title(); ?></h3>
					<?php if ( $role ) : ?>
						<p class="tts-card__meta mb-6"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>
					
					<?php
					// Extract intro paragraph and rest of content
					$content = get_the_content();
					$paragraphs = array_filter( array_map( 'trim', explode( '</p>', $content ) ) );
					
					if ( ! empty( $paragraphs ) ) {
						// First paragraph as intro
						echo '<p class="font-bold mb-4">' . wp_kses_post( str_replace( '<p>', '', $paragraphs[0] ) ) . '</p>';
						
						// Remaining paragraphs as body copy
						for ( $i = 1; $i < count( $paragraphs ); $i++ ) {
							echo '<p class="mb-4">' . wp_kses_post( str_replace( '<p>', '', $paragraphs[ $i ] ) ) . '</p>';
						}
					}
					?>
					
					<div class="instructor-highlights">
						<?php
						// Render three highlight items with icons
						$highlights = [
							[ 'icon' => '🏆', 'text' => 'Pro-Level Experience: Performance background with <em>Blue Man Group</em> & <em>Powerman 5000</em>.' ],
							[ 'icon' => '🎒', 'text' => 'All Ages Welcome: Friendly, patient instruction tailored for kids (ages 7+) and adults.' ],
							[ 'icon' => '📐', 'text' => 'Ergonomics-Focused: Learn the proper hand technique, wrist rotation, and posture to prevent strain.' ],
						];
						
						foreach ( $highlights as $highlight ) {
							?>
							<div class="instructor-highlight">
								<div class="instructor-highlight__icon"><?php echo $highlight['icon']; ?></div>
								<div class="instructor-highlight__text"><?php echo wp_kses_post( $highlight['text'] ); ?></div>
							</div>
							<?php
						}
						?>
					</div>
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
