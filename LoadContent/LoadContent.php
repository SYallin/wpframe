<?php
/*
 * Load content 
 */


//use lib/RegisterPostType;

class LoadContent{
    
    use CheckRequiredClassesTrait;

    private $posttype;
    private $posttype_slug;
    private $default_post_data;
    
    public static $required_classes = array(
                                      'RequiredPlugins',
                                      );
    
    public $textdomain;
    public $cron_enable = false;
    public $plugin = array(
             'name'                 => 'Advanced Custom Fields Pro', // The plugin name
             'slug'                 => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name)
             'required'             => true,                         // If false, the plugin is only 'recommended' instead of required
             'version'              => '5.2.5',                      // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
             'force_activation'     => true,                         // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
             'force_deactivation'   => false,                        // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
             'external_url'         => '',                           // If set, overrides default API URL and points to an external URL
        );
    
    function __construct( $post_type ){
        $this->set_post_type( $post_type );

        self::check_required_classes( self::$required_classes );
        
        $this->textdomain = wp_get_theme()->get( 'TextDomain' );
        add_action( 'init', array( $this, 'init' ) ); 
    }
    
    function init(){
        if( $this->setup_acfpro() ){
            new RegisterPostType( $this->posttype );
            $this->set_cron();
        }
    }
    
    function set_post_type( $posttype ){
        $this->posttype = strtolower( $posttype );
        $this->posttype_slug = str_replace( " ", "", $this->posttype );
        $this->set_default_post_data();
    }
    
    function setup_acfpro(){
        $theme_plugins = new RequiredPlugins;
        $theme_plugins->add_plugin( $this->plugin );
        $theme_plugins->init();
        
        if( class_exists( 'acf_pro' ) && function_exists( 'acf_add_local_field_group' ) ){
            $acf_fields = array(
                'key'       => $this->posttype_slug,
                'title'     => 'Theme options',
                'fields'    => array (
                    array (
                        'key'       => $this->posttype_slug . '_update_time',
                        'label'     => 'Event should reoccur',
                        'name'      => $this->posttype_slug . '_update_time',
                        'type'      => 'select',
                        'choices'   => array (
                            'hourly'        => 'Hourly',
                            'twicedaily'    => 'Twicedaily',
                            'daily'         => 'Daily',
                        ),                        
                    ),
                    array (
                        'key'   => $this->posttype_slug . '_service_link',
                        'label' => 'Service Link',
                        'name'  => $this->posttype_slug . '_service_link',
                        'type'  => 'text',
                    ),

                ),
                'location' => array (
                    array (
                        array (
                            'param'     => 'options_page',
                            'operator'  => '==',
                            'value'     => 'acf-options-theme-options',
                        ),
                    ),
                ),
            );
            acf_add_local_field_group( $acf_fields );
            return true;
        }else{
            return false;
        }
        
    }
    
    function set_default_post_data(){
        $this->default_post_data = array(
                        'post_type'     => $this->posttype_slug,
                        'post_status'   => 'publish',
                        'post_author'   => 1,
        );
    }
    
    function insert_post( $post_data ){
        $post_data = array_merge( $post_data, $this->default_post_data );
        $post_id = wp_insert_post( $post_data );
        
    }

    function get_external_data(){
        // demo data
        $data = array(
                    array(
                        'post_title'    => 'My post4',
                        'post_content'  => 'This is my post.',
                     ),
                    array(
                        'post_title'    => 'My post9',
                        'post_content'  => 'This is my post.2',
                     ),
                    array(
                        'post_title'    => 'My post6',
                        'post_content'  => 'This is my post.3',
                     ),                      
            );
        return $data;
    }
    
    function update_posts(){
        foreach( $this->get_external_data() as $post ){
            if( !get_page_by_title( $post['post_title'], OBJECT, $this->posttype_slug ) ){
                $this->insert_post( $post );
            }
        }
    }
    
    function set_cron(){
        if( $this->cron_enable ){
            if( !wp_next_scheduled( $this->posttype_slug . '_loadcontent_update_posts' ) ) { 
                wp_schedule_event( time(), get_field( 'twitt_update_time', 'option' ), $this->posttype_slug . '_loadcontent_update_posts' );
            }
            add_action( $this->posttype_slug . '_loadcontent_update_posts', array( $this, 'update_posts' ) );
        }else{
            $this->update_posts();
        }
    }
}