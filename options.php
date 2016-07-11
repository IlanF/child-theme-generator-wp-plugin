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
                                <th><label><?php esc_html_e( 'Parent Theme', 'if-child-theme' ); ?></label></th>
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
                                <th><label><?php esc_html_e( 'Theme Name', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_name"
                                           placeholder="<?php esc_attr_e( 'Your theme\'s name', 'if-child-gen' ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Theme URI', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_uri"
                                           placeholder="<?php esc_attr_e( 'Theme homepage URL', 'if-child-gen' ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Description', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <textarea rows="3"
                                              cols="50"
                                              class="large-text"
                                              name="theme_description"
                                              placeholder="<?php esc_attr_e( 'Short description of the theme', 'if-child-gen' ); ?>"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Author', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <p><label><?php esc_html_e( 'Author Name', 'if-child-theme' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_author"
                                           placeholder="<?php esc_attr_e( 'Author name', 'if-child-gen' ); ?>"
                                           value="<?php echo wp_get_current_user()->display_name; ?>">

                                    <p><label><?php esc_html_e( 'Author Homepage', 'if-child-theme' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_author_uri"
                                           placeholder="<?php esc_attr_e( 'Author\'s website URL', 'if-child-gen' ); ?>"
                                           value="<?php echo wp_get_current_user()->user_url; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Version', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_version"
                                           placeholder="<?php esc_attr_e( '1.0.0', 'if-child-gen' ); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Text Domain', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_text_domain"
                                           placeholder="<?php esc_attr_e( 'Short identifier for the theme (slug)', 'if-child-gen' ); ?>">
                                    <p class="description">
                                        <?php _e( 'The text-domain is used for translations', 'if-child-gen' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Tags', 'if-child-theme' ); ?></label></th>
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
                                <th><label><?php esc_html_e( 'License', 'if-child-theme' ); ?></label></th>
                                <td>
                                    <p><label><?php esc_html_e( 'License Name', 'if-child-theme' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_license"
                                           placeholder="<?php esc_attr_e( 'License name', 'if-child-gen' ); ?>"
                                           value="GNU General Public License v2">

                                    <p><label><?php esc_html_e( 'Full License URL', 'if-child-theme' ); ?></label></p>
                                    <input type="text"
                                           class="regular-text"
                                           name="theme_license_uri"
                                           placeholder="<?php esc_attr_e( 'Full license URL', 'if-child-gen' ); ?>"
                                           value="http://www.gnu.org/licenses/gpl-2.0.html">

                                    <p class="description">
                                        <?php _e( 'WordPress requires that themes will be licensed under a GPL compatible license.', 'if-child-gen' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'License Text', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <textarea rows="3"
                                              cols="50"
                                              class="large-text"
                                              name="license_text"
                                              placeholder="<?php esc_attr_e( 'Extra license information.', 'if-child-gen' ); ?>"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Extra comments', 'if-child-gen' ); ?></label></th>
                                <td>
                                    <textarea rows="3"
                                              cols="50"
                                              class="large-text"
                                              name="extra_comments"
                                              placeholder="<?php esc_attr_e( 'More comments you want to add to the theme.', 'if-child-gen' ); ?>"></textarea>
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
        <textarea  rows="6" cols="50" class="large-text" id="style-css-header-code" readonly></textarea>
    </div>
</form>
