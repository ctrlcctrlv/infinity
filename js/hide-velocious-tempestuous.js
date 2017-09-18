$(document).ready(function(){
        var update_timer_vt;
        var execution_time = 60000;

        var loadVelociousTempestuous = function() {
                updateVTPage("velocious","velocious");
                updateVTPage("tempestuous","newest");
        }

        var update_vel_temp = function(){
                clearInterval(update_timer_vt);
                update_timer_vt = setInterval(loadVelociousTempestuous, execution_time);
        }

        var stop_update_vel_temp = function() {
                clearInterval(update_timer_vt);
        }

        function updateVTPage(id,src_tpl){
                if(active_page == "page"){
                        $.ajax({
                                url: 'https://8ch.net/templates/' + src_tpl + '.html',
                                context: document.body,
                                success: function(data) {
                                        var content = $(data).find('ul').html();
                                        $("div#"+id+"_main").find("section").find("ul").html(content);
                                }
                        });
                }
        }

        //*Auto update Velocious and Tempestous
        if (!localStorage.auto_update_velocious_tempestous) localStorage.auto_update_velocious_tempestous = 'true';
        $('#auto-update-fs').append('<label id="auto_update_velocious_tempestuous"><input type="checkbox">' + _("  Auto update tempestous and velocious threads") + '</label>');
        if (localStorage.auto_update_velocious_tempestous === 'true') {
                $('#auto_update_velocious_tempestuous>input').prop('checked', true);
                loadVelociousTempestuous();
                update_vel_temp();
        } else {
                $('#auto_update_velocious_tempestuous>input').prop('checked', false);
                stop_update_vel_temp();
        }
        $('#auto_update_velocious_tempestuous>input').on('click', function() {
                if ($('#auto_update_velocious_tempestuous>input').is(':checked')) {
                        localStorage.auto_update_velocious_tempestous = 'true';
                        update_vel_temp();
                } else {
                        localStorage.auto_update_velocious_tempestous = 'false';
                        stop_update_vel_temp();
                }
        });

        function vel_tem(sectiontpl){
                if (!localStorage['hide_'+sectiontpl]) localStorage['hide_'+sectiontpl] = 'false';
                $("#"+sectiontpl+"_main").find("section > .box-title").first().find('p').prepend('<a style="text-decoration: none" id="hide_'+sectiontpl+'_link" href="javascript:void(0)"></a> ');
                if (localStorage['hide_'+sectiontpl] === 'true') {
                        $("#hide_"+sectiontpl+"_link").text("[+]");
                        $("#"+sectiontpl+"_main").find("section").find("ul").hide();
                        $("#"+sectiontpl+"_main").find("section > .box-title").slice(-3).hide();
                        $("#"+sectiontpl+"_main").find("section").css('background','#98E');
			                  $("#"+sectiontpl+"_main").find(".box-title").toggleClass("col-6 col-12");
                } else {
                        $("#hide_"+sectiontpl+"_link").text("[-]");
                        $("#"+sectiontpl+"_main").find("section").find("ul").show();
                        $("#"+sectiontpl+"_main").find("section > .box-title").slice(-3).show();
                        $("#"+sectiontpl+"_main").find("section").css('background','#D6DAF0');
                }

                $('#hide_'+sectiontpl+'_link').on('click', function() {
                        if($("#hide_"+sectiontpl+"_link").text() == "[-]"){
                                localStorage['hide_'+sectiontpl] = 'true';
                                $("#hide_"+sectiontpl+"_link").text("[+]");
                                $("#"+sectiontpl+"_main").find("section").find("ul").hide();
                                $("#"+sectiontpl+"_main").find("section > .box-title").slice(-3).hide();
                                $("#"+sectiontpl+"_main").find("section").css('background','#98E');
				                        $("#"+sectiontpl+"_main").find(".box-title").toggleClass("col-6 col-12");
                        }else {
                                localStorage['hide_'+sectiontpl] = 'false';
                                $("#hide_"+sectiontpl+"_link").text("[-]");
                                $("#"+sectiontpl+"_main").find("section").find("ul").show();
                                $("#"+sectiontpl+"_main").find("section > .box-title").slice(-3).show();
                                $("#"+sectiontpl+"_main").find("section").css('background','#D6DAF0');
				                        $("#"+sectiontpl+"_main").find(".box-title").toggleClass("col-12 col-6");
                        }
                });
        }

        vel_tem("velocious");
	      vel_tem("recent");
        vel_tem("tempestuous");
});
