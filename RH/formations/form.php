<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('ajout');

?>
<style>
div {

    flex-wrap: wrap;
}
</style>

  <form method="post" style="margin-top:20px;" enctype="multipart/form-data">
<div class="row">


  <div class="col-md-2">

    <div class="row">

    <div class="form-group">

      <label>Origine du besoin</label>
        <select name="originebesoin" class="form-control">
          <option value="planformation" selected="selected">Plan Formation</option>
          <option value="entretienindividuel">Entretien Individuel</option>
          <option value="staffing">Staffing review</option>
          <option value="Autres">Autres</option>
        </select>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label>Précisez :     </label>
      <input name="details1" class="form-control" type="text">
    </div>
  </div>
</div>

<div class="col-md-4 col-md-offset-1">
  <div class="row">

  <div class="form-group">
    <label>Intitulé :     </label>
      <input name="intitule" class="form-control" type="text">
  </div>
</div>
  <div class="row">
    <div class="form-group">
      <label>Interne <br>( externe par defaut) :     </label><label style="margin-left:20px">
        <input type="hidden" value="0" name="interne">
        <input name="interne" type="checkbox" value="1"> Oui</label>

    </div>
  </div>
</div>

<div class="col-md-4 col-md-offset-1">
  <div class="row">
    <div class="form-group">
      <label>Objectifs :     </label>
        <input name="objectifs" class="form-control" type="text">
      </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label>Résultats :     </label>
        <input name="resultats" class="form-control" type="text">
      </div>
  </div>
</div>

</div>

<br><br>



<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label>Impact de la formation :     </label>
      <input name="impact" class="form-control" type="text">
    </div>
  </div>
  <div class="col-md-5 col-md-offset-2">
    <div class="form-group">
    <label>Priorité:</label> <br><label style="margin-left:20px">
      <input type="hidden" value="0" name="priorite">
      <input name="priorite" type="checkbox" value="1"> indispensable</label>
    </div>
  </div>
</div>


<br><br>


<div class="row">
  <div class="col-md-4 ">
    <div class="row">
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Jan <br></label>
          <input type="hidden" value="0" name="janvier">
          <input name="janvier" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Fev</label>
          <input type="hidden" value="0" name="fevrier">
          <input name="fevrier" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Mar </label>
          <input type="hidden" value="0" name="mars">
          <input name="mars" type="checkbox" value="1">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Avr <br></label>
          <input type="hidden" value="0" name="avril">
          <input name="avril" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Mai</label>
          <input type="hidden" value="0" name="mai">
          <input name="mai" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Juin </label>
          <input type="hidden" value="0" name="juin">
          <input name="juin" type="checkbox" value="1">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Juil <br></label>
          <input type="hidden" value="0" name="juillet">
          <input name="juillet" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Aout</label>
          <input type="hidden" value="0" name="aout">
          <input name="aout" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Sept </label>
          <input type="hidden" value="0" name="septembre">
          <input name="septembre" type="checkbox" value="1">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Oct <br></label>
          <input type="hidden" value="0" name="octobre">
          <input name="octobre" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Nov</label>
          <input type="hidden" value="0" name="novembre">
          <input name="novembre" type="checkbox" value="1">
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1">
        <div class="form-group">
        <label>Dec </label>
          <input type="hidden" value="0" name="decembre">
          <input name="decembre" type="checkbox" value="1">
        </div>
      </div>
    </div>

  </div>


<div class="col-md-3 col-md-offset-5">
  <br><br><br>
    <input value="Demander la formation" class="btn btn-default" type="submit">

</div>

</form>
