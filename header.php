<!---1250764268-->
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<script type="text/javascript">
	if (navigator && navigator.serviceWorker) {
		navigator.serviceWorker.register('<?php echo get_template_directory_uri() ?>/sw.js');
	}
</script>

<head>
	<?php class_exists('ACF') ? the_field('custom_tags_head', 'options') : false; ?>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php class_exists('ACF') ? the_field('custom_tags_body', 'options') : false; ?>

	<div class="media-query-debugger fixed bottom-0 right-0 p-y-2 p-x-5"></div>

	<div class="page-wrap overflow-hidden antialiased">

		<header role="banner" class="fixed w-full z-50 shadow-2xl">

			<nav class="container h-full">
				<div class="row h-full flex items-center">
					<div class="col-12 flex items-center justify-between p-y-4">
						<div class="logo w-40">
							<a href="<?php echo get_site_url() ?>/#">
								<h1><?php bloginfo('name'); ?></h1>
								<?php render_svg('logo') ?>
							</a>
						</div>

						<input id="toggle-menu" type="checkbox" />
						<label for="toggle-menu" aria-label="Menu" class="lg:hidden">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</label>

						<?php // Main Menu
						wp_nav_menu([
							'menu' => 'main',
							'sort_column' => 'menu_order',
							'menu_class' => 'menu list-none lg:space-x-8 space-y-4 lg:space-y-0 p-0 m-0 items-center font-semibold flex',
							'container' => 'ul',
						]); ?>
					</div>
				</div>
			</nav>

		</header>

		<main class="dark:bg-black">