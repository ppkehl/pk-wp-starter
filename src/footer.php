            </main>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12">

                    </div>
                </div>
            </div>
        </footer>

        <?php get_template_obj('template-parts/cookies.php') ?>

        <?php (class_exists('ACF') ? the_field('custom_tags_footer', 'options') : false) ?>

        <?php wp_footer(); ?>

    </body>

</html>