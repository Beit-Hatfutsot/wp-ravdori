<?php


function super_unique($array)
{
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

  foreach ($result as $key => $value)
  {
    if ( is_array($value) )
    {
      $result[$key] = super_unique($value);
    }
  }

  return $result;
}



$countriesArray = array();

// WP_User_Query arguments
$args = array ( 'role' => 'adult' );

// The User Query
$user_query = new WP_User_Query( $args );


// The User Loop
if ( ! empty( $user_query->results ) )
{
        foreach ( $user_query->results as $user )
        {
            $birthCountryID = get_field( 'acf-user-adult-birth-place', 'user_' . $user->ID );
            $term = get_term( $birthCountryID,  SCHOOLS_TAXONOMY );
			$lat = get_field( 'acf-school-latitude'  , SCHOOLS_TAXONOMY . '_' . $birthCountryID);
			$lng = get_field( 'acf-school-longitude' , SCHOOLS_TAXONOMY . '_' . $birthCountryID);
			
            $countriesArray[] = array('id' => $birthCountryID , 'name' => $term->name , 'lat' => $lat , 'lng' => $lng);
        }
		
		$countriesArray = super_unique ( $countriesArray );
}


?>

<script>
    $ = jQuery;

    var map;


    function initMap()
    {
        var myOptions = {
                            center: new google.maps.LatLng( 31, 35 ),
                            zoom: 2,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };

        map = new google.maps.Map( document.getElementById("map"), myOptions );

        // Create the address array
        var addressArray = new Array();

        <?php foreach ( $countriesArray as $country ):  ?>
            addressArray.push({ name: "<?php echo $country['name'];?>", id: "<?php echo $country['id'];?>" , lat: "<?php echo $country['lat'];?>" , lng: "<?php echo $country['lng'];?>" });
        <?php endforeach;?>


        //var geocoder     = new google.maps.Geocoder();
        var markerBounds = new google.maps.LatLngBounds();

		
		/*
		function spotPosition(address , termId) {
        geocoder.geocode({ 'address': address }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                 addCountryMarker( results , status , markerBounds , termId ) 
            }
            else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                setTimeout(function () {
                    //Recursively calling spotPosition method for lost addresses
                    spotPosition(address  , termId);
                }, 1);
            }
        });
		}*/
		
        for (var i = 0; i < addressArray.length; i++)
        {
            (function(termId)
            {

			var results = {lat: parseFloat(addressArray[i].lat), lng: parseFloat(addressArray[i].lng) };
			addCountryMarker( results , 'OK' , markerBounds , termId ); 
			
				//spotPosition( addressArray[i].name  , termId );
              //geocoder.geocode( { 'address': addressArray[i].name }, function(results, status){ addCountryMarker( results , status , markerBounds , termId ) });

            })(addressArray[i].id);
        }


    } // initmap


    function addCountryMarker ( results , status , markerBounds , termId )
    {
        //if (status == google.maps.GeocoderStatus.OK)
        //{
            var iconBase ="<?php echo IMAGES_DIR . '/general/'?>";
            var currentIcon = iconBase;

            var currentTerm = '<?php echo isset ( $_GET['country'] ) ? $_GET['country'] : -1; ?>';


            if ( currentTerm == termId )
            {
                currentIcon += 'mapmarkercurrent.png';
            }
            else
            {
                currentIcon += 'mapmarker.png'
            }

            var marker = new google.maps.Marker({
                map: map,
                position: results,
                icon: currentIcon
            });

            marker.addListener('click', function()
            {
                window.location.assign( '<?php echo get_the_permalink();?>?country=' +  termId );
            });

            markerBounds.extend(results);
           // map.fitBounds(markerBounds);
        //}

    }


</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIYgYR7NOYNOx0anUSVUap31yBjgz49bY&callback=initMap&language=en" async defer></script>

<div class="col-xs-12">
    <div id="map" style="height: 300px; width:100%;"></div>
</div>