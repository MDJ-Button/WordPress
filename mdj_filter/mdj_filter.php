<?php
/**
 * mdj_filter
 *
 * @package     mdj_filter
 * @author      Martin De Jonge
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: mdj_filter
 * Plugin URI:  N/A
 * Description: 
 * Version:     1.0.0
 * Author:      Martin De Jonge
 * Author URI:  https://www.fiverr.com/martindejonge_
 * Text Domain: mdj_filter
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

function init_mdj_filter()
{
	generateJS();
}

function generateJS()
{
?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		var year = '';
		var make = '';
		var model = '';
		var tire_size = '';

        //-------------------------------------------
        // Not my code
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        //-------------------------------------------

        

		$(document).ready(function(){
            let showlabels = getCookie("showlabels_cookie");
            //console.log("showlabels = " + showlabels);

            if(showlabels === "true") 
            {
                let filter_address = getCookie("filter_cookie");
                //console.log("filter_address = " + filter_address);
                
                if(window.location.href.indexOf(filter_address) > -1) 
                {
                    let year_cookie = getCookie("year_cookie");
                    let make_cookie = getCookie("make_cookie");
                    let model_cookie = getCookie("model_cookie");
                    let tiresize_cookie = getCookie("tiresize_cookie");

                    console.log('year' + year_cookie);
                    console.log('make' + make_cookie);
                    console.log('model' + model_cookie);
                    console.log('tire' + tiresize_cookie);

                    $("#wpfBlock_1 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:first-child").text(year_cookie);
                    $("#wpfBlock_1 > div.wpfFilterContent > div.wpfCheckboxHier > select").prop('disabled', true);

                    $("#wpfBlock_2 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:first-child").text(make_cookie);
                    $("#wpfBlock_2 > div.wpfFilterContent > div.wpfCheckboxHier > select").prop('disabled', true);

                    $("#wpfBlock_3 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:first-child").text(model_cookie);
                    $("#wpfBlock_3 > div.wpfFilterContent > div.wpfCheckboxHier > select").prop('disabled', true);

                    $("#wpfBlock_4 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:first-child").text(tiresize_cookie);
                    $("#wpfBlock_4 > div.wpfFilterContent > div.wpfCheckboxHier > select").prop('disabled', true);
                }
                else
                {
                    document.cookie = "showlabels_cookie = false";
                }
            }
            else
            {
                let reset_cookie = getCookie("reset_cookie");

                if(reset_cookie === "true") 
                {
                    document.cookie = "reset_cookie = false";
                    window.location = '#jump';
                }
            }

            $('#mdj_filter_reset').click(function() {
                //alert("reset");
                document.cookie = "reset_cookie = true";
                document.cookie = "showlabels_cookie = false";
            });
			$('#wpfBlock_1').on('change', function(){
				year = $("#wpfBlock_1 > div.wpfFilterContent > div.wpfCheckboxHier > select").val();
                year_l = $("#wpfBlock_1 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:selected").text();
                document.cookie = "year_cookie = " + year_l;
            });
			$('#wpfBlock_2').on('change', function(){
				make = $("#wpfBlock_2 > div.wpfFilterContent > div.wpfCheckboxHier > select").val();
                make_l = $("#wpfBlock_2 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:selected").text();
                document.cookie = "make_cookie = " + make_l;
			});

			$('#wpfBlock_3').on('change', function(){
				model = $("#wpfBlock_3 > div.wpfFilterContent > div.wpfCheckboxHier > select").val();
                model_l = $("#wpfBlock_3 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:selected").text();
                document.cookie = "model_cookie = " + model_l;
			});

			$('#wpfBlock_4').on('change', function(){
				tire_size = $("#wpfBlock_4 > div.wpfFilterContent > div.wpfCheckboxHier > select").val();
                tire_size_l = $("#wpfBlock_4 > div.wpfFilterContent > div.wpfCheckboxHier > select > option:selected").text();
                document.cookie = "tiresize_cookie = " + tire_size_l;
				myFunction();
			});

			function myFunction() {
				var currentURL = window.location.href;

				console.log(currentURL);
				console.log(year);
				console.log(make);
				console.log(model);
				console.log(tire_size);

                $.ajax({
					url: "/wp-content/plugins/mdj_filter/action.php", // the url we want to send and get data from
					type: "POST", // type of the data we send (POST/GET)
					data: { "callSetYear": year, 
                            "callSetMake": make,
                            "callSetModel": model,
                            "callSetTire_Size": tire_size
                    }, // the data we want to send
					success: function(data){ // when successfully sent data and returned
						// do something with the returned data
						console.log(data);
						console.log('success - ' + data);
                        document.cookie = "filter_cookie = ?filter_sku="+ data;
                        document.cookie = "showlabels_cookie = true";
                        window.location = '?filter_sku='+ data + '#jump';
					}
				}).done(function(){
					// this part will run when we send and return successfully
                    
                    //window.location = '?filter_sku=2004825247#jump';
					console.log("Success.");
				}).fail(function(){
					// this part will run when an error occurres
					console.log("An error has occurred.");
				}).always(function(){
					// this part will always run no matter what
					console.log("Complete.");
				});

				//window.location.hash = '#jump';
			}
		});
	</script>
<?
}

add_shortcode('mdj_filter', 'init_mdj_filter');
// Use shortcode [mdj_filter]

?>