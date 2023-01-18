$( function() {

    var NoResultsLabel = "Aucun résultat trouvé.";

    $.getJSON(root_url + "Restaurants/Specialities",function(data){
      $( "#search-cuisine" ).autocomplete({
        source: function(request, response) {
          var results = $.ui.autocomplete.filter(data, request.term);

          if (!results.length) {
              results = [NoResultsLabel];
          }

          response(results);
        },
        minLength: 0,
        select: function (event, ui) {
          if (ui.item.label === NoResultsLabel) {
            event.preventDefault();
          }
        },
        focus: function (event, ui) {
          if (ui.item.label === NoResultsLabel) {
            event.preventDefault();
          }
        }
      })
    })

    $( "#search-cuisine" ).on('focus click', function () {
      $(this).autocomplete("search");
    });

    $.ui.autocomplete.prototype._renderItem = function (ul, item) {        
        var t = String(item.value).replace(
                new RegExp(this.term, "gi"),
                "<strong>$&</strong>");
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<div>" + t + "</div>")
            .appendTo(ul);
    };


    $('#home-search-form').submit(function(e){
      e.preventDefault();
      if ($('#home-search-form')[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();

    } else {
      if($('[name="search-city"]').val() == ''){
        swall('Attention!', 'Sélectionnez une ville.', 'warning');
        return false;
      }
      $('#find-restos-icon').removeClass().addClass('fas fa-circle-notch fa-spin');
      try{
        var infos = { city: $('[name="search-city"]').val(), cuisine: $("#search-cuisine").autocomplete("instance").selectedItem.id }
      }catch(e){
        var infos = { city: $('[name="search-city"]').val(), cuisine: 'all' }
      }
      $.post(root_url + 'Restaurants/List', infos)

        .done(function (data) {
            var result = JSON.parse(data);
            if (result.status == 'success') {
                $('#find-restos-icon').removeClass().addClass('mdi mdi-check-circle');
                location.href = result.url;
            }else{
              swall('Échec!', 'Opération a échoué s\'il vous plaît essayez à nouveau.', 'error');
            }
      })

    }
    $('#home-search-form').addClass('was-validated');
    $('#find-restos-icon').removeClass().addClass('mdi mdi-magnify');

    })

  } );