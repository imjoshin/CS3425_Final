$(document).on("ready", function(){
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "scripts/ajax.php",
    data: {
      action: "get_exams",
    },
    cache: false,
    success: function(data) {
      if(data["error"]){
        alert(data["error"]);
      }else{
        $("#exams").html(data["html"]);
      }
    },
    error: function(xhr, status, error) {
      alert("An error has occured.");
    }
  });

  $("#login-btn").on("click", function(){
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "scripts/ajax.php",
      data: {
        action: "login",
        user: $("#input-user").val(),
        pass: $("#input-pass").val()
      },
      cache: false,
      success: function(data) {
        if(data["error"]){
          alert(data["error"]);
        }else{
          $("#exams").html(data["html"]);
          $(".navbar-nav").html("<li><a href='#'>Exams</a></li><li><a id='logout'>Logout</a></li>");
          $("#login-modal").modal("hide");
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });

  $("#logout").on("click", function(){
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "scripts/ajax.php",
      data: {
        action: "logout",
      },
      cache: false,
      success: function(data) {
        if(data["error"]){
          alert(data["error"]);
        }else{
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });

});
