$(document).ready(function() {
        $("#ticket").keypress(function(event) {
            if (event.which == 13) {
                gdf.Recherche();
            }       
        });

        $('#rechercher').click(function () {        
            gdf.Recherche();                    
        });

        $('#profile-tab').click(function (e) {        
            var num = $("#num_ticket").val();
            if(num == '') return false;
            var message = $("span#message").text();

            if (message.includes("a été déjà")) {
                $(this).css({"color":"red","border":"2px solid red","background":"white"});
                $("#home-tab").css({"color":"white","background":"red"});
            }
        });

         $('#home-tab').click(function (e) {        
            var message = $("span#message").text();

            if (message.includes("a été déjà")) {
                $(this).css({"color":"red","border":"2px solid red","background":"white"});
                $("#profile-tab").css({"color":"white","background":"red"});
                $(".btnRegister").css("background","red");
            }
        });


        $('#table').bootstrapTable();

        $(document).on( "click","#retour", function(e) {
            var num = $("#num_ticket").val();
            if ($.fn.DataTable.isDataTable("#datatable_")) {
              $('#datatable_').DataTable().clear().destroy();
            }
            $(".result").html("");

            $.ajax({
                method: "POST",
                url: "resultat.php",
                dataType: 'JSON',
                data: { factR: num }

            }).done(function(res){

                var articles = res[0]["articles"];
                var row      = "";

                Object.keys(articles).forEach(function(key) {
                    const art = articles[key];
                    row       = "<tr><td>"+art.reference+"</td><td>"+art.desc1+"</td><td>"+art.quantity+"</td><td><input type='number' name='qte_retour' id='qte_retour' class='form-control float-end text-end' min='1' value='"+art.quantity+"'/></td><td><input type='checkbox' class='statut' checked></td></tr>";
                    $(".result").append(row);
                });
                
                $('#datatable_').DataTable({
                    "order": [ 1, "asc" ],
                    "pageLength": 100,
                    "fnDrawCallback": function(oSettings){
                        $('.Factmodal-lg').modal('show');
                    }
                });
            });
            
        });

        $(document).on( "click","#retourner", function(e) {
            var num = $("#num_ticket").val();
            var arr  = [];
            var arr2 = [];
            $("span#message").text("");
            var rowCount = $(".result tr").length;
            if(rowCount > 0){
              $(".result tr").each(function(){
                 if($(this).find('input').is(':checked')){
                    arr.push($(this).find("td:first").text());
                    arr2.push($(this).children().eq(1).text() + '-----'+ $(this).children().eq(2).text() + '-----'+ $(this).children().eq(3).children("input").val());
                 }                
              });

              var result =  arr2.reduce(function(result, field, index) {
                                result[arr[index]] = field;
                                return result;
                          }, {});

              $.ajax({
                  method: "POST",
                  url: "resultat.php",
                  async: true,
                  cache: false,
                  data: { facture:num, add: result }
              }).done(function(res){
                    $("span#message").text(res);
                    $("span#message").show();
              });

              $('.Factmodal-lg').modal('hide');
            }
            setTimeout(function (e) {
                location.reload(true);
              }, 5000);

            return false;            
        });

        $(document).on("click", "#valider", function(e){
            var num = $("#num_ticket").val();
            $("#loading_insert").show();
            $("span#message").text("");
            $.ajax({
                method: "POST",
                url: "resultat.php",
                data: { fact: num }

            }).done(function(resultat){
                $(".btnRegister_").hide();
                $("#loading_insert").hide();
                $("span#message").text(resultat);
                $("span#message").show();

                if (resultat.includes("a été déjà")) {
                    $(".register").css("background","radial-gradient(circle at 10% 20%, rgb(238, 56, 56) 0%, rgba(206, 21, 0, 0.92) 90.1%)");
                    $(".register .nav-tabs").css("background","red");
                    $(".register .nav-link").css("border","0px");
                    $(".nav-link.active").css({"color":"red","border":"2px solid red"});
                }
            });

            return false;
        });

        $('.dateRange').datepicker({
            inputs: $('.dateRangeInput'),
            language: 'fr'
        });
        
        $('.dateRangeTrigger').click(function() {
            $(this).closest('.input-group').find('.dateRangeInput').datepicker('show');
        });
        
        $(document).on("click", "#print_", function(e){
            var d = $('#startDate').val().split("/").reverse().join("-");
            var f = $('#endDate').val().split("/").reverse().join("-");
            var t = $('input[name = "selection"]:checked').val();
            if(d == "") alert("Veuillez séléctionner une date d'extraction svp!!");
            else window.open("impression.php?d="+d+"&f="+f+"&t="+t,"_blank");
            return false;
        });

        var gdf = {}; 
        gdf.Recherche=function() { 
            $('#ticket').each(function() {
               if ($(this).val() != "") {
                    $("#loading-image").show();
                    $(".register").css("background","-webkit-linear-gradient(left, #f8d13b, #f8d13b54)");
                    $(".register .nav-tabs").css("background","#f8d13b");
                    $(".register .nav-tabs .nav-link.active").css("border","2px solid #f8d13b");
                    $(".btnRegister").css("background","#f8d13b");
                    var message = $("span#message").text();
                    if (message.includes("a été déjà")) {
                        location.reload();
                    }

                    $.ajax({
                      method: "POST",
                      url: "resultat.php",
                      dataType: 'JSON',
                      data: { num: $(this).val() }

                    }).done(function(resultat){
                        if(resultat == ""){
                            $("#loading-image").hide();
                            $('#popup').modal('show');
                        }
                        else{
                            $("#table tbody").text("");
                            $(".detail").html("Détail du ticket ");
                            $("span#message").hide();
                            $(".btnRegister_").show();

                            Object.keys(resultat[0]).forEach(key => {
                                const dateTime  = resultat[0]["created_date"];
                                const parts     = dateTime.split(/[- :]/);
                                const wanted    = `${parts[2]}/${parts[1]}/${parts[0]} ${parts[3]}:${parts[4]}:${parts[5]}`;
      
                                $("#num_facture").val(resultat[0]["invoice_num"]);
                                $("#num_ticket").val(resultat[0]["receipt_number"]);
                                $("#date_facture").val(wanted);
                                $("#num_client_facture").val(resultat[0]["orderby_code"]);
                                $("#adr_client_facture").val(resultat[0]["orderby_adr1"]);
                                $("#prenom_client").val(resultat[0]["orderby_first_name"]);
                                $("#nom_client").val(resultat[0]["orderby_last_name"]);
                                $("#tel_client").val(resultat[0]["orderby_phone"]);

                            });
                            var articles = resultat[0]["articles"];
                            var row      = "";

                            Object.keys(articles).forEach(function(key) {
                                const art = articles[key];
                                row       = "<tr><td>"+art.reference+"</td><td>"+art.desc1+"</td><td>"+art.quantity+"</td></tr>";
                                $("#table tbody").append(row);
                            });
                            $(".detail").append('<span class="facture_">'+$("#num_ticket").val()+'</span>');
                            $("#loading-image").hide();
                            $(".resultat").show();
                        }
                    });
               }
               else return false;
            });
            
        }

    });