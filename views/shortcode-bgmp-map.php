<script type="text/javascript">
    var bgmpData = {
        options: <?php echo json_encode( $this->getMapOptions( $attributes ) ); ?>,
        markers: <?php echo json_encode( $this->getMapPlacemarks( $attributes ) ); ?>
    };

    (function($){
        $(document).ready(function(){
            $("#getLocation").on("click", function(){
                navigator.geolocation.getCurrentPosition(is_success,is_error);
                function is_success(position) {
                    bgmpData.options.latitude = position.coords.latitude;
                    bgmpData.options.longitude = position.coords.longitude;
                    bgmp_wrapper( $ );
                }
                function is_error(error) {}
            });
        });
    })(jQuery);
</script>

<p><button id="getLocation">現在地へ移動</button></p>
<div id="<?php echo self::PREFIX; ?>map-canvas">
    <p><?php _e( 'Loading map...', 'basic-google-maps-placemarks' ); ?></p>
    <p><img src="<?php echo plugins_url( 'images/loading.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading', 'basic-google-maps-placemarks' ); ?>" /></p>
</div>