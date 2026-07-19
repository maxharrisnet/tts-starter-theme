<?php
/**
 * Template Name: How It Works
 *
 * Fixed marketing page — copy is developer-maintained (AGENT/TDS "How It
 * Works" Page Copy.md is the source document). Three numbered sections plus
 * a closing CTA.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<!-- Page heading -->
	<section class="tts-section" aria-labelledby="hiw-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h1 id="hiw-heading" class="tts-section-heading__title"><?php esc_html_e( 'How It Works', 'drumstudy' ); ?></h1>
				<p class="tts-section-heading__subtitle">
					<?php esc_html_e( 'Learning the drums doesn\'t have to be overwhelming. At The Drum Study, we break down complex rhythms into step-by-step, bite-sized lesson plans designed to take you from a total beginner to a confident groove-maker.', 'drumstudy' ); ?>
				</p>
			</div>
		</div>
	</section>

	<!-- 01 — What You'll Achieve -->
	<section class="tts-section" aria-labelledby="hiw-achieve-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<span class="tts-eyebrow" aria-hidden="true">01</span>
				<h2 id="hiw-achieve-heading" class="tts-section-heading__title"><?php esc_html_e( 'What You\'ll Achieve', 'drumstudy' ); ?></h2>
				<p class="tts-section-heading__subtitle">
					<?php esc_html_e( 'Our structured lesson plans are engineered to yield real, tangible results. By staying consistent with the curriculum, you will build:', 'drumstudy' ); ?>
				</p>
			</div>

			<div class="tts-grid-2">
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Rock-Solid Coordination', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'Develop independent limb control — the "limb independence" that makes drumming look like magic.', 'drumstudy' ); ?></p>
					</div>
				</div>
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Sheet Music Literacy', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'Learn to read and write drum notation, allowing you to learn new beats on your own.', 'drumstudy' ); ?></p>
					</div>
				</div>
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Musical Timing', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'Master the internal clock required to keep perfect time and drive a live band.', 'drumstudy' ); ?></p>
					</div>
				</div>
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Repertoire', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'Build a mental catalog of essential drum grooves and fills across rock, pop, jazz, and blues.', 'drumstudy' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 02 — Finding Your Rhythm (Age Guidelines) -->
	<section class="tts-section" aria-labelledby="hiw-ages-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<span class="tts-eyebrow" aria-hidden="true">02</span>
				<h2 id="hiw-ages-heading" class="tts-section-heading__title"><?php esc_html_e( 'Finding Your Rhythm', 'drumstudy' ); ?></h2>
				<p class="tts-section-heading__subtitle">
					<?php esc_html_e( 'We divide our learning paths by age group to match physical development, attention span, and learning goals.', 'drumstudy' ); ?>
				</p>
			</div>

			<div class="tts-grid-2">
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Youth (Ages 7–10)', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'At this stage, we focus on building a joyful relationship with the instrument.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'The Focus:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Developing basic motor skills, simple rhythm recognition, and hand-eye coordination through fun, gamified exercises.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Parental Role:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Highly encouraged! Children in this age group succeed best when a parent helps track their practice sessions and cheers them on.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Pacing:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Shorter, high-energy modules designed to keep young minds engaged.', 'drumstudy' ); ?></p>
					</div>
				</div>
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'Adults & Teens (Ages 10+)', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'For older students, the curriculum shifts toward deeper technique and self-directed growth.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'The Focus:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Developing proper stick grip (matched or traditional), complex rudiments, speed, endurance, and stylistic versatility.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Parental Role:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Entirely independent. Students are expected to manage their own schedules and progress.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Pacing:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Comprehensive lesson pathways that allow you to dive deep into your favorite musical genres.', 'drumstudy' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 03 — Gear & Practice Habits -->
	<section class="tts-section" aria-labelledby="hiw-gear-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<span class="tts-eyebrow" aria-hidden="true">03</span>
				<h2 id="hiw-gear-heading" class="tts-section-heading__title"><?php esc_html_e( 'Gear & Practice Habits', 'drumstudy' ); ?></h2>
				<p class="tts-section-heading__subtitle">
					<?php esc_html_e( 'You don\'t need a massive drum set taking up your entire living room to get started. Success is built on consistency and the right foundation.', 'drumstudy' ); ?>
				</p>
			</div>

			<div class="tts-grid-2">
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'The Starter Gear', 'drumstudy' ); ?></h3>
						<p><strong><?php esc_html_e( 'Phase 1 (Day 1):', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Just a pair of drumsticks (we recommend size 5A) and a quiet rubber practice pad.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Phase 2 (When ready):', 'drumstudy' ); ?></strong> <?php esc_html_e( 'An electronic drum kit (great for low-volume headphone practice) or a standard acoustic setup.', 'drumstudy' ); ?></p>
					</div>
				</div>
				<div class="tts-card">
					<div class="tts-card__body">
						<h3 class="tts-card__title"><?php esc_html_e( 'The 30-Minute Golden Rule', 'drumstudy' ); ?></h3>
						<p><?php esc_html_e( 'Success on the drums is about frequency, not marathon sessions once a week.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( '30 Minutes a Day:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'Committing to at least 30 minutes of daily, focused practice builds muscle memory much faster than practicing for 3 hours only on Sundays.', 'drumstudy' ); ?></p>
						<p><strong><?php esc_html_e( 'Focused Practice:', 'drumstudy' ); ?></strong> <?php esc_html_e( 'We\'ll show you how to split your daily 30 minutes into warm-ups, curriculum work, and "free play" to keep it fun and highly engaging.', 'drumstudy' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Closing CTA -->
	<section class="tts-section tts-section--sm" aria-labelledby="hiw-cta-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h2 id="hiw-cta-heading" class="tts-section-heading__title"><?php esc_html_e( 'Ready to Get Started?', 'drumstudy' ); ?></h2>
			</div>
			<div class="tts-cta-pair tts-cta-pair--center">
				<?php
				// Same source as the header button, so this CTA can never drift
				// out of sync with the site-wide primary action.
				$hiw_cta_label = drumstudy_get_option( 'drumstudy_cta_header_label' ) ?: drumstudy_get_option( 'drumstudy_cta_primary_label' );
				$hiw_cta_url   = drumstudy_get_option( 'drumstudy_cta_header_url' )   ?: drumstudy_get_option( 'drumstudy_cta_primary_url' );
				if ( $hiw_cta_label && $hiw_cta_url ) :
					?>
					<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $hiw_cta_url ) ); ?>" class="tts-btn tts-btn--primary">
						<?php echo esc_html( $hiw_cta_label ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
