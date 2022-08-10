<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<script type="text/javascript">
	/* Service worker */
	if (navigator && navigator.serviceWorker) {
		navigator.serviceWorker.register('<?php echo get_template_directory_uri() ?>/sw.js');
	}
	/* Dark mode */
	if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
		document.documentElement.classList.add('dark');
	} else {
		document.documentElement.classList.remove('dark');
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

	<div class="[ media-query-debugger ] [ fixed bottom-0 right-0 p-y-2 p-x-5 ]"></div>

	<div class="[ page-wrap ] [ overflow-hidden antialiased ]">

		<header class="[ page-header ] [ fixed w-full z-50 shadow-2xl ]" role="banner">

			<nav class="[ main-nav ] [ container h-full ]">
				<div class="[ main-nav-inner ] [ row h-full flex items-center relative ]">
					<div class="[ main-nav-col ] [ col-12 flex items-center justify-between p-y-4 ]">

						<div class="[ logo ] [ w-32 lg:w-40 ]">
							<a href="<?php echo get_site_url() ?>/#">
								<h1><?php bloginfo('name'); ?></h1>
								<?php render_svg('logo') ?>
							</a>
						</div>

						<div class="[ menu-items ] [ flex space-x-8 ]">

							<input class="[ menu-toggle ]" id="menu-toggle" type="checkbox" />
							<label class="[ menu-toggle-label ] [ lg:hidden ]" for="menu-toggle" aria-label="Menu">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</label>

							<div class="[ menu-wrap ] [ flex lg:space-x-8 ]">
								<?php // Main Menu
									wp_nav_menu([
										'menu' => 'main',
										'sort_column' => 'menu_order',
										'menu_class' => '[ menu ] [ list-none lg:space-x-8 space-y-4 lg:space-y-0 p-0 m-0 items-center font-semibold flex ]',
										'container' => 'ul',
									]);
								?>

								<div class="[ theme-toggle ] [ mr-5 lg:mr-0 ]" id="theme-toggle"></div>

							</div>

						</div>

					</div>
				</div>
			</nav>

		</header>

		<main class="[ page-main ] [ dark:bg-black ]">