var countEmail = 0;

function addEmail() {
    countEmail = countEmail + 1;
    $('.blockEmail').append('<input class="envoyerA" onchange="addEmail();" id="destinataire'+ countEmail+'" type="text" name="destinataire1">');
}

var countFichier = 0;

function  addInput(){
    countFichier = countFichier + 1;
    $('.blockUpload').append('<input class="upload" onclick="addInput();" id="fichier'+ countFichier +'" type="file" name="fichier[]">');
}

$('form').submit( function(event){

            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM         
            var monForm = $('form');
            var formdata = new FormData(monForm[0]);
            var data = formdata;

            $.ajax({
            url: "http://vesoul.codeur.online/front/lgwendal/newwedili.MGC/contact_me.php",
            type: "POST",
            dataType : 'json', 
            contentType: false, 
            processData: false,
            data: data,
            cache: false,
            success: function(data) {
                // Success message
                $('#success').html("<div class='alert alert-success'>");
                $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                    .append("</button>");
                $('#success > .alert-success')
                    .append("<strong>Votre article a été posté. </strong>");
                $('#success > .alert-success')
                    .append('</div>');
                //clear all fields
                $('#contactForm').trigger("reset");
            },
            error: function(data) {
                // Fail message
                $('#success').html("<div class='alert alert-danger'>");
                $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                    .append("</button>");
                $('#success > .alert-danger').append("<strong>Désolé le serveur ne répond pas, réessayez plus tard !");
                $('#success > .alert-danger').append('</div>');
                //clear all fields
                $('#contactForm').trigger("reset");
            },
        });
});
                         
                                                  
/************JS DRAG AND DROP ***********************/
                         


var $fileInput = $('.file-input');
var $droparea = $('.file-drop-area');

// highlight drag area
$fileInput.on('dragenter focus click', function() {
  $droparea.addClass('is-active');
});

// back to normal state
$fileInput.on('dragleave blur drop', function() {
  $droparea.removeClass('is-active');
});

// change inner text
$fileInput.on('change', function() {
  var filesCount = $(this)[0].files.length;
  var $textContainer = $(this).prev('.js-set-number');

  if (filesCount === 1) {
    // if single file then show file name
    $textContainer.text($(this).val().split('\\').pop());
  } else {
    // otherwise show number of files
    $textContainer.text(filesCount + ' files selected');
  }
});



/***********************************JS BACKGROUND**************************************************/

(function() {
  window.background = {
    initialize: function() {
        

      return $('.background svg').each(function() {
        var delay, i, len, length, path, paths, previousStrokeLength, results, speed;
        paths = $('path, circle, rect', this);
        delay = 0;
        results = [];
        for (i = 0, len = paths.length; i < len; i++) {
          path = paths[i];
          length = path.getTotalLength();
          previousStrokeLength = speed || 0;
          speed = length < 100 ? 20 : Math.floor(length);
          delay += previousStrokeLength + 100;
          results.push($(path).css('transition', 'none').attr('data-length', length).attr('data-speed', speed).attr('data-delay', delay).attr('stroke-dashoffset', length).attr('stroke-dasharray', length + ',' + length));
        }
        return results;
      });
    },
    animate: function() {
      return $('.background svg').each(function() {
        var delay, i, len, length, path, paths, results, speed;
        paths = $('path, circle, rect', this);
        results = [];
        for (i = 0, len = paths.length; i < len; i++) {
          path = paths[i];
          length = $(path).attr('data-length');
           /* vitesse du dessin*/
          speed = /*$(path).attr('data-speed')*/5000;
            
            //temps d'attente avant le chargement'
          delay = /*$(path).attr('data-delay')*/500;
            
          results.push($(path).css('transition', 'stroke-dashoffset ' + speed + 'ms ' + delay + 'ms linear').attr('stroke-dashoffset', '0'));
        }
        return results;
      });
    }
  };

  $(document).ready(function() {
    window.background.initialize();
    return $('button').on('click', function() {
      window.background.initialize();
      return setTimeout(function() {
        return window.background.animate();
      }, 500);
    });
  });

  $(window).load(function() {
    return window.background.animate();
  });

}).call(this);