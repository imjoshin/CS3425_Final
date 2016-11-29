<?php include "header.php" ?>

<div align="left">
    <div align="center">
        <h1>CS3425 Exam 1</h1> 
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">1. Which of the following is a correct SQL statement?</div>
        <div class="panel-body">
            <div class="radio">
                <label><input type="radio" name="q1"> SELECT *</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="q1"> SELECT * FROM table</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="q1"> INSERT NEW ROW</label>
            </div>
        </div>
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">Correct Answer Example</div>
        <div class="panel-body">
            <div class="radio">
                <label><span class="glyphicon glyphicon-unchecked"></span> SELECT *</label>
            </div>
            <div class="radio">
                <label><span class="glyphicon glyphicon-ok" style="color:green"></span> SELECT * FROM table</label>
            </div>
            <div class="radio">
                <label><span class="glyphicon glyphicon-unchecked"></span> INSERT NEW ROW</label>
            </div>
        </div>
    </div>

    <div class="panel panel-danger">
        <div class="panel-heading">Incorrect Answer Example</div>
        <div class="panel-body">
            <div class="radio">
                <label><span class="glyphicon glyphicon-unchecked"></span> SELECT *</label>
            </div>
            <div class="radio">
                <label><span class="glyphicon glyphicon-ok" style="color:green"></span> SELECT * FROM table</label>
            </div>
            <div class="radio">
                <label><span class="glyphicon glyphicon-remove" style="color:red"></span> INSERT NEW ROW</label>
            </div>
        </div>
    </div>

    <div align="center">
        <button class="btn btn-default">Submit Exam</button>
    </div>
</div>

<?php include "footer.php" ?>