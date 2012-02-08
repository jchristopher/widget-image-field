<?php
/*
    Plugin Name: Widget Image Field
    Plugin URI: http://mondaybynoon.com/wordpress/widget-image-field/
    Description: This prepares an image field for use within your own Widgets
    Author: Jonathan Christopher
    Version: 0.3
    Author URI: http://mondaybynoon.com/
*/

if( !defined( 'IS_ADMIN' ) )
    define( 'IS_ADMIN',  is_admin() );

define( 'WIDGET_IMAGE_FIELD_VERSION', '0.3' );
define( 'WIDGET_IMAGE_FIELD_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
define( 'WIDGET_IMAGE_FIELD_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );


class WidgetImageField
{
    private $image_id;
    private $src;
    private $width;
    private $height;
    private $widget_field;
    private $widget = null;

    function __construct( $widget = null, $image_id = 0 )
    {
        $uri        = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : NULL ;
        $file       = basename( parse_url( $uri, PHP_URL_PATH ) );

        // if we're on the Widgets page
        if( $uri && in_array( $file, array( 'widgets.php' ) ) && IS_ADMIN )
        {
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'widgetimagefield', WIDGET_IMAGE_FIELD_URL . '/script.js', array( 'jquery', 'jquery-ui-core', 'thickbox', 'media-upload' ), false, true );

            wp_enqueue_style( 'thickbox' );
            wp_enqueue_style( 'widgetimagefield', WIDGET_IMAGE_FIELD_URL . '/style.css' );
        }

        // set our properties
        $this->widget = $widget;
        if( $image_id )
        {
            $this->image_id         = intval( $image_id );
            $this->src              = $this->get_image_src( $this->image_id );
            $this->width            = $this->get_image_width( $this->image_id );
            $this->height           = $this->get_image_height( $this->image_id );
            $this->widget_field     = $this->get_widget_field( $widget, $this->image_id );
        }
    }

    function get_image( $size = 'thumbnail' )
    {
        $image = false;

        if( $this->image_id )
        {
            $image = wp_get_attachment_image_src( $this->image_id, $size );
        }

        return $image;
    }

    function get_image_src( $size = 'thumbnail' )
    {
        $src = false;

        if( $this->image_id )
        {
            $image      = $this->get_image( $size );
            $src        = $image[0];
        }

        return $src;
    }

    function get_image_dimensions( $size = 'thumbnail' )
    {
        $dimensions = array( null, null );

        if( $this->image_id )
        {
            $image          = $this->get_image( $size );
            $dimensions     = array( $image[1], $image[2] );
        }

        return $dimensions;
    }

    function get_image_width( $size = 'thumbnail' )
    {
        $width = false;

        if( $this->image_id )
        {
            $dimensions     = $this->get_image_dimensions( $size );
            $width          = $dimensions[0];
        }

        return $width;
    }

    function get_image_height( $size = 'thumbnail' )
    {
        $height = false;

        if( $this->image_id )
        {
            $dimensions     = $this->get_image_dimensions( $size );
            $height         = $dimensions[1];
        }

        return $height;
    }

    function get_widget_field( $field_name = null )
    {
        $field = false;
        if( $this->widget && ( isset( $this->widget->image_field ) || $field_name ) )
        {
            $field  = "<div class='iti-image-widget-field'><div class='iti-image-widget-image' id='" . $this->widget->id . "'>";
            $field .= "<input type='hidden' style='display:none;' id='" . $this->widget->get_field_id( $this->widget->image_field ) . "' name='" . $this->widget->get_field_name( $this->widget->image_field ) . "' value='" . $this->image_id . "' />";

            if( $this->image_id )
            {
                $field .= "<img src='" . $this->src . "' width='" . $this->width . "' height='" . $this->height . "' />";
            }

            $field .= "</div>";

            $field .= "<a class='button iti-image-widget-trigger' href='media-upload.php?TB_iframe=1&amp;width=640&amp;height=1500' title='" . __( 'Choose Image' ) . "'>";
            $field .= __( 'Choose Image' );
            $field .= "</a></div>";
        }
        return $field;
    }
}
