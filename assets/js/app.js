$(document).on("ready", function(){

  //Get exams when page is loaded, or get login prompt
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
        //if no error, set content
        $("#content").html(data["html"]);
      }
    },
    error: function(xhr, status, error) {
      alert("An error has occured.");
    }
  });

  //login button click
  $("#login-btn").on("click", function(){
    //pass username and password into login
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
          //if no error, set content
          $("#content").html(data["html"]);
          //set new navbar after login
          $(".navbar-nav").html("<li><a href=''>Exams</a></li><li><a href='' id='logout'>Logout</a></li>");
          $("#login-modal").modal("hide");
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });

  //logout button
  $(document).on("click", "#logout", function(){
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
          //reload page if successful logout
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });

  //exam table row click
  $(document).on("click", "#exam-table tr:not(:first-child)", function(){
    //get exam name
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
          //if no error, set content
          $("#content").html(data["html"]);
        }
      },
      error: function(xhr, status, error) {
        alert("An error has occured.");
      }
    });
  });

  //exam submit button
  $(document).on("click", ".submit-btn", function(){
    var valid = true;
    //check if each question has an answer
    $("input:radio").each(function(){
      var name = $(this).attr("name");
      if($("input:radio[name="+name+"]:checked").length == 0)
      {
        valid = false;
      }
    });
    //not all questions are filled
    if(!valid){
      alert("Please fill all questions.");
    }else{

      //all questions filled, get answers in an array
      var answers = [];
      $("input:radio:checked").each(function(){
        var name = $(this).attr("name");
        answers.push(name + "," + $("input:radio[name="+name+"]:checked").data("id"));
      });

      //submit exam answers
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "scripts/ajax.php",
        data: {
          action: "submit_exam",
          answers: answers,
          exam_name: $("#header").html()
        },
        cache: false,
        success: function(data) {
          if(data["error"]){
            alert(data["error"]);
          }else{
            //if no error, set content
            $("#content").html(data["html"]);
          }
        },
        error: function(xhr, status, error) {
          alert("An error has occured.");
        }
      });
    }
  });
});
