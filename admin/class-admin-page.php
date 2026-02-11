<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class SEIC_Admin_Page {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_menu_page() {
        add_menu_page(
            esc_html__( 'Image Carousel', 'seic' ),
            esc_html__( 'Image Carousel', 'seic' ),
            'manage_options',
            'seic-settings',
            array( $this, 'render_admin_page' ),
            'dashicons-images-alt2',
            60
        );
    }

    public function enqueue_assets( $hook ) {
        if ( 'toplevel_page_seic-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'seic-admin',
            plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin.css',
            array(),
            '1.0.52'
        );
    }

    public function register_settings() {
        register_setting( 'seic_settings_group', 'seic_lazy_load_enabled' );
        register_setting( 'seic_settings_group', 'seic_default_speed' );
    }

    public function render_admin_page() {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard';
        ?>
        <div class="seic-admin-wrapper">
            <div class="seic-sidebar">
                <div class="seic-logo">
                    <h2>Simple Carousel</h2>
                    <p class="seic-version">v1.0.52</p>
                </div>
                <nav class="seic-nav">
                    <a href="?page=seic-settings&tab=dashboard" class="<?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Dashboard', 'seic' ); ?>
                    </a>
                    <a href="?page=seic-settings&tab=settings" class="<?php echo $active_tab === 'settings' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Settings', 'seic' ); ?>
                    </a>
                    <a href="?page=seic-settings&tab=changelog" class="<?php echo $active_tab === 'changelog' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Changelog', 'seic' ); ?>
                    </a>
                </nav>
            </div>

            <div class="seic-content">
                <?php if ( $active_tab === 'dashboard' ) : ?>
                    <div class="seic-card">
                        <h1><?php esc_html_e( 'Welcome to Simple Elementor Image Carousel', 'seic' ); ?></h1>
                        <p><?php esc_html_e( 'Thank you for using our plugin. This lightweight plugin helps you create beautiful image carousels in Elementor with high performance.', 'seic' ); ?></p>
                        
                        <div class="seic-features-grid">
                            <div class="seic-feature">
                                <span class="dashicons dashicons-performance"></span>
                                <h3><?php esc_html_e( 'High Performance', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Optimized for speed with built-in lazy loading support.', 'seic' ); ?></p>
                            </div>
                            <div class="seic-feature">
                                <span class="dashicons dashicons-layout"></span>
                                <h3><?php esc_html_e( 'Flexible Layouts', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Customize columns, spacing, and alignment effortlessly.', 'seic' ); ?></p>
                            </div>
                            <div class="seic-feature">
                                <span class="dashicons dashicons-smartphone"></span>
                                <h3><?php esc_html_e( 'Responsive', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Looks great on all devices with mobile-first design.', 'seic' ); ?></p>
                            </div>
                            <div class="seic-feature">
                                <span class="dashicons dashicons-image-rotate"></span>
                                <h3><?php esc_html_e( 'Infinite Loop', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Seamless continuous scrolling for better user experience.', 'seic' ); ?></p>
                            </div>
                            <div class="seic-feature">
                                <span class="dashicons dashicons-controls-play"></span>
                                <h3><?php esc_html_e( 'Autoplay', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Auto-advance slides with customizable timing and hover pause.', 'seic' ); ?></p>
                            </div>
                            <div class="seic-feature">
                                <span class="dashicons dashicons-format-gallery"></span>
                                <h3><?php esc_html_e( 'Lightbox Ready', 'seic' ); ?></h3>
                                <p><?php esc_html_e( 'Built-in Elementor lightbox integration for full-screen viewing.', 'seic' ); ?></p>
                            </div>
                        </div>

                        <div class="seic-getting-started" style="margin-top: 40px; padding: 20px; background: #f0f7ff; border-radius: 8px; border-left: 4px solid #2271b1;">
                            <h2><?php esc_html_e( 'Getting Started', 'seic' ); ?></h2>
                            <ol style="padding-left: 20px; line-height: 1.8;">
                                <li><?php esc_html_e( 'Edit any page with Elementor', 'seic' ); ?></li>
                                <li><?php esc_html_e( 'Search for "Simple Image Carousel" in the widget panel', 'seic' ); ?></li>
                                <li><?php esc_html_e( 'Drag and drop it onto your page', 'seic' ); ?></li>
                                <li><?php esc_html_e( 'Add images from your media library', 'seic' ); ?></li>
                                <li><?php esc_html_e( 'Customize the appearance and behavior', 'seic' ); ?></li>
                            </ol>
                        </div>
                    </div>

                <?php elseif ( $active_tab === 'settings' ) : ?>
                    <div class="seic-card">
                        <h1><?php esc_html_e( 'Global Settings', 'seic' ); ?></h1>
                        <form method="post" action="options.php">
                            <?php settings_fields( 'seic_settings_group' ); ?>
                            <?php do_settings_sections( 'seic_settings_group' ); ?>
                            
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Enable Global Lazy Load', 'seic' ); ?></th>
                                    <td>
                                        <label class="seic-switch">
                                            <input type="checkbox" name="seic_lazy_load_enabled" value="1" <?php checked( 1, get_option( 'seic_lazy_load_enabled' ), true ); ?> />
                                            <span class="slider round"></span>
                                        </label>
                                        <p class="description"><?php esc_html_e( 'Enable lazy loading for all carousels by default to improve page speed.', 'seic' ); ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Default Transition Speed (ms)', 'seic' ); ?></th>
                                    <td>
                                        <input type="number" name="seic_default_speed" value="<?php echo esc_attr( get_option( 'seic_default_speed', 450 ) ); ?>" class="regular-text" min="150" max="3000" />
                                        <p class="description"><?php esc_html_e( 'Default animation speed in milliseconds (150-3000). Recommended: 450-600.', 'seic' ); ?></p>
                                    </td>
                                </tr>
                            </table>
                            
                            <?php submit_button(); ?>
                        </form>
                    </div>

                <?php elseif ( $active_tab === 'changelog' ) : ?>
                    <div class="seic-card">
                        <h1><?php esc_html_e( 'Changelog', 'seic' ); ?></h1>
                        
                        <div class="seic-changelog">
                            <div class="seic-changelog-entry">
                                <h3>Version 1.0.52 <span class="seic-badge seic-badge-critical">Critical Fix</span></h3>
                                <p class="seic-date">February 5, 2025</p>
                                <ul>
                                    <li><strong>Fixed:</strong> Blank slides appearing between images - Optimized slide duplication</li>
                                    <li><strong>Fixed:</strong> Over-duplication causing empty carousel slots</li>
                                    <li><strong>Added:</strong> Show/Hide Pagination Dots toggle in widget settings</li>
                                    <li><strong>Improved:</strong> Loop mode only duplicates when necessary (not for large galleries)</li>
                                    <li><strong>Improved:</strong> Better Swiper auto-calculation for looped slides</li>
                                    <li><strong>Enhanced:</strong> Pagination dots now have visibility control</li>
                                </ul>
                            </div>

                            <div class="seic-changelog-entry">
                                <h3>Version 1.0.51 <span class="seic-badge seic-badge-update">Update</span></h3>
                                <p class="seic-date">February 5, 2025</p>
                                <ul>
                                    <li><strong>Fixed:</strong> Image height consistency - All images now maintain uniform height</li>
                                    <li><strong>Fixed:</strong> Loop mode stability - Better infinite scrolling with proper slide duplication</li>
                                    <li><strong>Improved:</strong> Image display with object-fit: cover for better presentation</li>
                                    <li><strong>Improved:</strong> Loop requires 3x visible slides for seamless transitions</li>
                                    <li><strong>Enhanced:</strong> Default image height set to 400px for consistent display</li>
                                    <li><strong>Enhanced:</strong> Better CSS transitions for smoother animations</li>
                                </ul>
                            </div>

                            <div class="seic-changelog-entry">
                                <h3>Version 1.0.50 <span class="seic-badge seic-badge-critical">Critical Fix</span></h3>
                                <p class="seic-date">February 5, 2025</p>
                                <ul>
                                    <li><strong>Fixed:</strong> Navigation arrows not working - Complete navigation system overhaul</li>
                                    <li><strong>Fixed:</strong> Pagination dots not clickable - Proper z-index and pointer-events implementation</li>
                                    <li><strong>Fixed:</strong> Slides not rendering properly - Corrected Swiper container structure</li>
                                    <li><strong>Fixed:</strong> Carousel not sliding smoothly - Improved transition configuration</li>
                                    <li><strong>Fixed:</strong> Images not showing in loop mode - Better slide duplication logic</li>
                                    <li><strong>Improved:</strong> Navigation arrows now perfectly circular with centered icons</li>
                                    <li><strong>Improved:</strong> Better console logging for debugging</li>
                                    <li><strong>Improved:</strong> Enhanced compatibility with Swiper 11/12</li>
                                    <li><strong>Updated:</strong> CSS with proper navigation visibility</li>
                                    <li><strong>Updated:</strong> JavaScript with better error handling</li>
                                </ul>
                            </div>

                            <div class="seic-changelog-entry">
                                <h3>Version 1.0.45</h3>
                                <p class="seic-date">January 28, 2025</p>
                                <ul>
                                    <li>Enhanced slide duplication algorithm for better loop mode</li>
                                    <li>Improved autoplay configuration</li>
                                    <li>Better responsive breakpoint handling</li>
                                    <li>Code optimization and cleanup</li>
                                </ul>
                            </div>

                            <div class="seic-changelog-entry">
                                <h3>Version 1.0.40</h3>
                                <p class="seic-date">January 20, 2025</p>
                                <ul>
                                    <li>Added RTL (Right-to-Left) language support</li>
                                    <li>Improved lazy loading implementation</li>
                                    <li>Enhanced caption display options</li>
                                    <li>Fixed minor CSS conflicts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .seic-version {
                font-size: 12px;
                color: #666;
                margin: 5px 0 0 0;
            }
            .seic-getting-started h2 {
                margin-top: 0;
                font-size: 18px;
            }
            .seic-changelog-entry {
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 1px solid #ddd;
            }
            .seic-changelog-entry:last-child {
                border-bottom: none;
            }
            .seic-changelog-entry h3 {
                margin-bottom: 5px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .seic-date {
                color: #666;
                font-size: 14px;
                margin-bottom: 15px;
            }
            .seic-changelog-entry ul {
                list-style: disc;
                padding-left: 25px;
            }
            .seic-changelog-entry li {
                margin-bottom: 8px;
                line-height: 1.6;
            }
            .seic-badge {
                display: inline-block;
                padding: 3px 10px;
                font-size: 11px;
                font-weight: 600;
                border-radius: 3px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .seic-badge-critical {
                background: #d63638;
                color: #fff;
            }
            .seic-badge-update {
                background: #2271b1;
                color: #fff;
            }
        </style>
        <?php
    }
}