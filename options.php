<?php $theme_tags = IF_Child_Theme_Generator::getThemeTags(); ?>
<form method="post" class="wrap if-child-gen if-child-gen-form">
    <?php wp_nonce_field( 'if-child-gen' ); ?>

    <h1><?php _e('Child Theme Generator', 'if-child-gen'); ?></h1>

    <div class="metabox-holder">
        <div class="meta-box-sortables ui-sortable">
            <div class="postbox">
                <h2 class="hndle"><span><?php _e( 'Basic Settings', 'if-child-gen' ); ?></span></h2>
                <div class="inside">

                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th><label><?php esc_html_e( 'Parent Theme', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <select name="parent_theme">
                                        <?php $themes = wp_get_themes(); $current_theme = get_stylesheet(); ?>
                                        <?php
                                            foreach( $themes as $theme ) :
                                                $slug = $theme->get_stylesheet();
                                                if( false !== $theme->parent() ) { continue; /* skip child themes */ }
                                        ?>

                                            <option value="<?php echo $slug; ?>"
                                                    <?php selected( $current_theme, $slug ); ?>
                                            >
                                                <?php
                                                    echo $theme->name;
                                                    if( $slug == $current_theme ) {
                                                        _ex( '(Current)', 'parent theme selection active theme', 'if-child-gen' );
                                                    }
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="postbox collapsible closed">
                <button type="button" class="handlediv button-link" aria-expanded="true">
                    <span class="screen-reader-text">
                        <?php _e( 'Toggle panel: Customize Child Theme Details', 'if-child-gen' ); ?>
                    </span>
                    <span class="toggle-indicator" aria-hidden="true"></span>
                </button>
                <h2 class="hndle ui-sortable-handle">
                    <span><?php _e( 'Customize Child Theme Details', 'if-child-gen' ); ?></span>
                </h2>

                <div class="inside">
                    <p>
                        <?php _e( 'If you wish to distribute your child theme you may want to fill in the theme details bellow.', 'if-child-gen' ); ?>
                        <br>
                        <?php _e( 'These fields are required if you want to upload the theme to the WordPress Theme Directory.', 'if-child-gen' ); ?>
                    </p>

                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th><label><?php esc_html_e( 'Theme Name', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_name"
                                           placeholder="<?php echo esc_attr( _x( 'My New Child Theme', 'Theme name placeholder', 'if-child-gen' ) ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Theme URI', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_uri"
                                           placeholder="<?php echo esc_attr( _x( 'http://www.example.com/my-theme', 'Theme URI placeholder', 'if-child-gen' ) ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Description', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <textarea rows="3"
                                              cols="50"
                                              class="large-text"
                                              name="theme_description"
                                              placeholder="<?php echo esc_attr( _x( 'Short description for my new theme', 'Description placeholder', 'if-child-gen' ) ); ?>"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Author', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <p><label><?php esc_html_e( 'Author Name', 'if-child-gen' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_author"
                                           placeholder="echo esc_attr( _x( 'John Doe', 'Author name placeholder', 'if-child-gen' ) )"
                                           value="<?php echo wp_get_current_user()->display_name; ?>">

                                    <p><label><?php esc_html_e( 'Author Homepage', 'if-child-gen' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_author_uri"
                                           placeholder="<?php echo esc_attr( _x( 'http://www.example.com', 'Author homepage placeholder', 'if-child-gen' ) ); ?>"
                                           value="<?php echo wp_get_current_user()->user_url; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Version', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_version"
                                           placeholder="<?php echo esc_attr( _x( 'x.y.z', 'Version number placeholder', 'if-child-gen' ) ); ?>"
                                           value="1.0.0">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Text Domain', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_text_domain"
                                           placeholder="<?php echo esc_attr( _x( 'my-theme', 'Text domain placeholder', 'if-child-gen' ) ); ?>">
                                    <p class="description">
                                        <?php _e( 'The text-domain is used for translations', 'if-child-gen' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Tags', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <?php foreach( $theme_tags as $category=>$tags ) : ?>
                                        <div class="theme-tags-list">
                                            <h4><?php echo $category; ?></h4>
                                            <?php foreach( $tags as $tag ) : ?>
                                                <label>
                                                    <input type="checkbox"
                                                           name="theme_tags[]"
                                                           value="<?php echo esc_attr( $tag ); ?>">
                                                    <?php echo ucwords( str_replace( array( '-', '_' ), ' ', $tag ) ); ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'License', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <p><label><?php esc_html_e( 'License Name', 'if-child-gen' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_license"
                                           placeholder="<?php echo esc_attr( _x( 'GNU General Public License v2', 'License name placeholder', 'if-child-gen' ) ); ?>"
                                           value="GNU General Public License v2">

                                    <p><label><?php esc_html_e( 'Full License URL', 'if-child-gen' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_license_uri"
                                           placeholder="<?php esc_attr( _x( 'http://www.gnu.org/licenses/gpl-2.0.html', 'Full license URL placeholder', 'if-child-gen' ) ); ?>"
                                           value="http://www.gnu.org/licenses/gpl-2.0.html">

                                    <p><label><?php esc_html_e( 'License Excerpt', 'if-child-gen' ); ?></label></p>
                                    <textarea rows="5"
                                              cols="50"
                                              class="large-text"
                                              name="license_text"
                                              placeholder="<?php echo esc_attr( _x( 'Short license excerpt', 'License excerpt placeholder', 'if-child-gen' ) ); ?>">GNU GENERAL PUBLIC LICENSE

Version 2, June 1991

Copyright (C) 1989, 1991 Free Software Foundation, Inc.
51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.</textarea>

                                    <p class="description">
                                        <?php _e( 'WordPress requires that themes will be licensed under a GPL compatible license.', 'if-child-gen' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Stylesheet comments', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <textarea rows="3"
                                              cols="50"
                                              class="large-text"
                                              name="extra_comments"
                                              placeholder="<?php echo esc_attr( _x( 'Add a comment to your style.css file', 'Stylesheet comments placeholder', 'if-child-gen' ) ); ?>">This is where you put all the CSS for your child theme</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <hr>
    <button type="submit" class="button button-primary if-child-gen-submit-btn">
        <?php _e( 'Create Child Theme', 'if-child-gen' ); ?>
    </button>
    <button type="button" class="button button-secondary expand-style-css-header-code-button">
        <?php _e( 'I just want the style.css header', 'if-child-gen' ); ?>
    </button>

    <div class="spinner" style="float: none; width: auto; padding-left: 26px;">
        <?php _e( 'Creating child theme...', 'if-child-gen' ); ?>
    </div>

    <div class="style-css-header-code-wrapper animate-visibility slide-out">
		<p class="description"><?php _e( 'This block is updated automatically as you change the theme information.', 'if-child-gen' ); ?></p>
        <textarea rows="6" cols="50" class="large-text" style="text-align: left; direction: ltr" id="style-css-header-code" readonly></textarea>
    </div>
</form>
