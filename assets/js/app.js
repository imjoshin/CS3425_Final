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
        $("#content").html(data["html"]);
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
          $("#content").html(data["html"]);
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

  $(document).on("click", "#exam-table tr", function(){
    var exam_name = $(this).data("exam");
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "scripts/ajax.php",
      data: {
        action: "get_questions",
        exam_name: exam_name
      },
      cache: false,
      success: function(data) {
        if(data["error"]){
          alert(data["error"]);
        }else{
          $("#content").html(data["html"]);
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });
});
