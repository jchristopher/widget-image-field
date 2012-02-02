# Overview

**THIS IS NOT A WIDGET**

This plugin simply makes it easier for you to use an 'image' field <em>within <strong>your</strong> Widgets</em>.

## Usage

Plugin usage <strong>within a Widget you've created</strong> is as follows:

```php
<?php

// we can only use this Widget if the plugin is active
if( class_exists( 'WidgetImageField' ) )
   add_action( 'widgets_init', create_function( '', "register_widget( 'ITI_Widget_Image' );" ) );


class ITI_Widget_Image extends WP_Widget
{
   var $image_field = 'image';      // Defines the field name for your image field

   function __construct()
   {
       $widget_ops = array(
               'classname'     => 'iti_image',
               'description'   => __( "Add an image")
           );
       parent::__construct( 'iti_image', __('Image'), $widget_ops );
   }

   function widget( $args, $instance )
   {
       extract($args);

       $image_id   = $instance[$this->image_field];
       $image      = new WidgetImageField( $this, $image_id );

       echo $before_widget;

           // here you can customize the output of the field
           ?>
               <img src="<?php echo $image->get_image_src( 'thumbnail' ); ?>" width="<?php echo $image->get_image_width( 'thumbnail' ); ?>" height="<?php echo $image->get_image_height( 'thumbnail' ); ?>" />

           <?php

       echo $after_widget;
   }

   function form( $instance )
   {
       $image_id   = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
       $image      = new WidgetImageField( $this, $image_id );

       // output the field
       echo $image->get_widget_field( $this->image_field );  // parameter is optional, default is $this->image_field
   }

   function update( $new_instance, $old_instance )
   {
       $instance = $old_instance;
       $instance[$this->image_field] = intval( strip_tags( $new_instance[$this->image_field] ) );
       return $instance;
   }
}
```

You can replace the image size you're looking for in `widget()` by swapping out `thumbnail` with your desired image size.
