/*Auto update catalog list*/
if (active_page === "catalog") {
$(document).ready(function(){
        var countdown_interval_catalog;
        var catalog_interval_mindelay = 60000;
        var catalog_interval_delay = catalog_interval_mindelay;
        var catalog_current_time = catalog_interval_delay;

        var decrement_timer_catalog = function() {
                catalog_current_time = catalog_current_time - 1000;
                $('#update_catalog_secs').text(catalog_current_time/1000);

                if (catalog_current_time <= 0) {
                        updateCatalogContent()
                        catalog_current_time = catalog_interval_delay + 1000;
                }
        }

        var catalog_auto_update = function(delay) {
                clearInterval(countdown_interval_catalog);

                catalog_current_time = delay;
                countdown_interval_catalog = setInterval(decrement_timer_catalog, 1000);
                $('#update_catalog_secs').text(catalog_current_time/1000);
        }

        var catalog_stop_auto_update = function() {
                clearInterval(countdown_interval_catalog);
        }

        // Add an update catalog link
        $('span.catalog_search').after("&nbsp;<span id='updater_catalog_panel'><a href='#' style='text-decoration:none; cursor:pointer;' id='update_catalog'>[Update]</a><label id='auto_update_catalog_status'><input type='checkbox' id='auto_update_catalog_cb'></label> "+_("Auto")+" (<span id='update_catalog_secs'></span>)</span>");

        // Set the updater checkbox according to user setting
        if (localStorage.auto_catalog_update === 'true') {
                $('#auto_update_catalog_cb').prop('checked', true);
                catalog_auto_update(catalog_interval_mindelay);
        }

        $('#auto_update_catalog_status>input').on('click', function() {
                if ($('#auto_update_catalog_status>input').is(':checked')) {
                        localStorage.auto_catalog_update = 'true';
                        catalog_auto_update(catalog_interval_mindelay);
                } else {
                        localStorage.auto_catalog_update = 'false';
                        catalog_stop_auto_update();
                        $('#update_catalog_secs').text("");
                }
        });



        $('#update_catalog').on('click', function() {
                updateCatalogContent();

                if($("#auto_update_catalog_cb").is(':checked')){
                        catalog_auto_update(catalog_interval_mindelay);
                }

        });

        function updateCatalogContent(){
                if(active_page == "catalog"){
                        var body = $(this).parents('#Grid');
                        var url = window.location.href;
                        $('#update_catalog_secs').text("Updating...");
                        $.ajax({
                                url: url,
                                context: document.body,
                                success: function(data) {
                                        var content = $(data).find('#Grid').html();
                                        //Update catalog content
                                        $("#Grid").html(content);
                                        //Sort catalog by Bump Order, Creation Date, Reply Count, Random and sort by image sizes
                                        var v_sort_by = $("#sort_by").val();
                                        var v_images_size = $("#image_size").val();
                                        $('#Grid').mixItUp('sort', (v_sort_by == "random" ? v_sort_by : "sticky:desc " + v_sort_by))

                                        //Change image size
                                        $(".grid-li").removeClass("grid-size-vsmall");
                                        $(".grid-li").removeClass("grid-size-small");
                                        $(".grid-li").removeClass("grid-size-medium");
                                        $(".grid-li").removeClass("grid-size-large");
                                        $(".grid-li").addClass("grid-size-"+v_images_size);

                                        initImageHover();
                                        $('#update_catalog_secs').text("");
                                }
                        });
                }
        }

});
}
