function FormConnexion() {
  document.getElementById("Connexion").style.display = "block";
  document.getElementById("jeu").style.display = "none";
  document.getElementById("Inscription").style.display = "none";
}

function FermerConnexion() {
  document.getElementById("Connexion").style.display = "none";
  document.getElementById("jeu").style.display = "block";
}

function FermerInscription() {
  document.getElementById("Inscription").style.display = "none";
  document.getElementById("jeu").style.display = "block";
}

function FormInscription() {
  document.getElementById("Inscription").style.display = "block";
  document.getElementById("Connexion").style.display = "none";
  document.getElementById("jeu").style.display = "none";
}

$(function () {
  $("#rechercheChampion").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: 'ChampionsNoms.php',
        dataType: 'json',
        success: function (data) {
          var availableChampions = data;
          var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
          var results = $.grep(availableChampions, function (item) {
            return matcher.test(item.label);
          });
          response(results);
        }
      });
    },
    minLength: 1,
    select: function (event, ui) {
      // Handle selection event if needed
    },
    _renderItem: function (ul, item) {
      return $("<li>")
        .append("<div><img src='" + item.image + "' class='champion-image' /><span class='champion-label'>" + item.label + "</span></div>")
        .appendTo(ul);
    }
  }).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
      .append("<div><img src='" + item.image + "' class='champion-image' /><span class='champion-label'>" + item.label + "</span></div>")
      .appendTo(ul);
  };
});

$(function () {
  $("#rechercheAmis").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: 'listeUtilisateur.php',
        dataType: 'json',
        success: function (data) {
          var utilisateur = data;
          var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
          var results = $.grep(utilisateur, function (item) {
            return matcher.test(item.label);
          });
          response(results);
        }
      });
    },
    select: function (event, ui) {
      // Handle selection event if needed
    },
  }).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
      .append("<div><img src='" + item.image + "' class='champion-image' /><span class='champion-label'>" + item.label + "</span></div>")
      .appendTo(ul);
  };
});
function Vert(element) {
  element.style.backgroundColor = 'rgba(8, 190, 45, 1)';
}



