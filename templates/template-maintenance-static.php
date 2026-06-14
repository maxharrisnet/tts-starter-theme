<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Under Maintenance</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
	font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;
	font-size: 1.125rem;
	line-height: 1.6;
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #0f0f14;
	color: #f0f0f0;
	padding: 2rem;
}
.wrap {
	max-width: 520px;
	text-align: center;
}
h1 {
	font-size: clamp(1.5rem, 4vw, 2.5rem);
	font-weight: 700;
	margin-bottom: 1rem;
}
p {
	color: #a0a0b0;
	margin-bottom: 1.5rem;
}
@media (prefers-reduced-motion: reduce) {
	*, *::before, *::after { animation: none !important; transition: none !important; }
}
</style>
</head>
<body>
<div class="wrap">
	<h1><?php echo esc_html( tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' ) ); ?></h1>
	<p>
		<?php
		$message = tts_get_option( 'tts_maintenance_message' );
		echo $message
			? esc_html( $message )
			: esc_html__( "We're working on something. Check back soon.", 'tts-theme' );
		?>
	</p>
</div>
</body>
</html>
