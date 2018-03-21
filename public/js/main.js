!function(a){a.fn.datepicker.dates.fr={days:["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"],daysShort:["dim.","lun.","mar.","mer.","jeu.","ven.","sam."],daysMin:["d","l","ma","me","j","v","s"],months:["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"],monthsShort:["janv.","févr.","mars","avril","mai","juin","juil.","août","sept.","oct.","nov.","déc."],today:"Aujourd'hui",monthsTitle:"Mois",clear:"Effacer",weekStart:1,format:"dd/mm/yyyy"}}(jQuery);
$(document).ready(function(){

    $('.js-datepicker').datepicker({
       format: 'dd/mm/yyyy',
       language: 'fr',
        orientation: 'bottom',
        todayHighlight: 'true',

    });

    //Validation Côté client

    var $car_immat = $('car_immat');

    var $r = $("td:contains('roulant')");
    var $ra = $("td:contains('roulant ano')");
    var $pa = $("tr > td:contains('panne')");
    $r.css("color", "green");
    $ra.css("color", "orange");
    $pa.css("color", "red");




    var inp = $('input[id*="slideThree"]');

    var valeurs = [];
    var y = 0;
    var o = 0;
    $("input[id*=\"slideThree\"]").on('click',function() {
        valeurs.push($(this).attr('id'));



            console.log(valeurs[o]);
            o++;


    });



    $('#form_enregistrer').on('click', function(e){
        e.preventDefault();

        var r = valeurs.length;
        for (i = 0; i < r ; i++)
        {

        }
        });
    /*
    
    $('input#form_recherche').click(function(){
        $("#form_recherche").keyup(function(){
          
            var immat = $('#form_recherche').val();

            $.ajax({
                type:'POST',
                data:{'immat' : immat},
                dataType:'json',
                url:'http://localhost:8000/recherche',
                success:function(data){
                    $('#resultat').html(data[0]);
                },
                error:function(data){
                     
                   console.log(data);
                }
            });

        });
    });*/
    
    

    



});