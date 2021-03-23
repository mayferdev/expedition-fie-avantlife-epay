<?php
if ($_REQUEST['auto']){
  require_once './lib/CybsSoapClient.php';

  $referenceCode = $_POST['auto'];
  $client = new CybsSoapClient();
  $request = $client->createRequest($referenceCode);

  $result['post'] = $_POST;
  if ($_POST['nit'] == ''){
    $_POST['nit'] = 'CF';
  }
  $_POST['referenceCode'] = $_POST['auto'];
  if ($_POST['amount'] == '99.25' || $_POST['amount'] == '165' || $_POST['amount'] == '149.99' || $_POST['amount'] == '0.99' || $_POST['amount'] == '569.99' ){
    $_POST['recurrente'] = 'MENSUAL';
    //RECURRENTE
    $ccAuthService = new stdClass();
    $ccAuthService->run = 'true';
    $request->ccAuthService = $ccAuthService;
    $ccCaptureService = new stdClass();
    $ccCaptureService->run = 'true';
    $request->ccCaptureService = $ccCaptureService;

    $paySubscriptionCreateService = new stdClass();
    $paySubscriptionCreateService->run = 'true';
    $request->paySubscriptionCreateService = $paySubscriptionCreateService;

    $billTo = new stdClass();
    $billTo->firstName = $_POST['firstName'];
    $billTo->lastName = $_POST['lastName'];
    $billTo->street1 = $_POST['address'];
    $billTo->city = 'GT';
    $billTo->country = 'GT';
    $billTo->state = 'GT';
    $billTo->postalCode = '010014';
    $billTo->email = $_POST['email'];
    $billTo->ipAddress = $_SERVER['REMOTE_ADDR'];
    $billTo->company = $_POST['nit'];
    $request->billTo = $billTo;

    $card = new stdClass();
    $card->accountNumber = $_POST['cardNumber'];
    $card->expirationMonth = $_POST['expirationMonth'];
    $card->expirationYear = $_POST['expirationYear'];
    $card->cvNumber = $_POST['cardCVV'];
    if ($_POST['cardNumber'][0] == '4') {
      $card->cardType='001';
    } else {
      $card->cardType='002';
    }
    $request->card = $card;

    $purchaseTotals = new stdClass();
    $purchaseTotals->currency = 'USD';
    $request->purchaseTotals = $purchaseTotals;
    

    $recurringSubscriptionInfo = new stdClass();
    $purchaseTotals->grandTotalAmount= strval(number_format((float)$_POST['amount'], 2, '.', ''));
    $request->purchaseTotals = $purchaseTotals;
    $recurringSubscriptionInfo->frequency = 'monthly';
    if ($_POST['amount'] == '99.25'){
      $recurringSubscriptionInfo->frequency = 'bi-weekly';
    }
    $recurringSubscriptionInfo->amount = strval(number_format((float)$_POST['amount'], 2, '.', ''));
    if ($_POST['amount'] == '99.25' || $_POST['amount'] == '165.00') {
      $recurringSubscriptionInfo->numberOfPayments = '1';
    } else {
      $recurringSubscriptionInfo->numberOfPayments = '2';
    }
    $recurringSubscriptionInfo->automaticRenew = 'false';

    $fecha_actual = date("Ymd");
    if ($_POST['amount'] == '99.25'){
      $recurringSubscriptionInfo->startDate = date("Ymd",strtotime($fecha_actual."+ 2 weeks"));
    } else {
      $recurringSubscriptionInfo->startDate = date("Ymd",strtotime($fecha_actual."+ 1 month"));
    }
    

    $request->recurringSubscriptionInfo = $recurringSubscriptionInfo;
  } else {
    $_POST['recurrente'] = 'ÚNICO PAGO';
    // NO RECURRENTE
    $ccAuthService = new stdClass();
    $ccAuthService->run = 'true';
    $request->ccAuthService = $ccAuthService;
    $ccCaptureService = new stdClass();
    $ccCaptureService->run = 'true';
    $request->deviceFingerprintID = $_POST['auto'];
    $request->ccCaptureService = $ccCaptureService;
    $billTo = new stdClass();
    $billTo->firstName = $_POST['firstName'];
    $billTo->lastName = $_POST['lastName'];
    $billTo->street1 = $_POST['address'];
    $billTo->city = 'GT';
    $billTo->country = 'GT';
    $billTo->state = 'GT';
    $billTo->postalCode = '010014';
    $billTo->email = $_POST['email'];
    $billTo->ipAddress = $_SERVER['REMOTE_ADDR'];
    $billTo->company = $_POST['nit'];
    $request->billTo = $billTo;
    $card = new stdClass();
    $card->accountNumber = $_POST['cardNumber'];
    $card->expirationMonth = $_POST['expirationMonth'];
    $card->expirationYear = $_POST['expirationYear'];
    $card->cvNumber = $_POST['cardCVV'];
    $request->card = $card;
    $purchaseTotals = new stdClass();
    $purchaseTotals->currency = 'USD';
    $purchaseTotals->grandTotalAmount = strval(number_format((float)$_POST['amount'], 2, '.', ''));
    $request->purchaseTotals = $purchaseTotals;
  }

  $reply = $client->runTransaction($request);
}

$months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
function contador() {
  $archivo = "contador.txt"; //el archivo que contiene en numero
  $f = fopen($archivo, "r"); //abrimos el archivo en modo de lectura
  if($f)
  {
    $contador = fread($f, filesize($archivo)); //leemos el archivo
    $contador = $contador + 1; //sumamos +1 al contador
    fclose($f);
  }
  $f = fopen($archivo, "w+");
  if($f)
  {
    fwrite($f, $contador);
    fclose($f);
  }
  return $contador;
}
$AUTO2 = str_pad(contador(), 6, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Pago</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css" integrity="sha384-PDle/QlgIONtM1aqA2Qemk5gPOE7wFq8+Em+G/hmo5Iq0CCmYZLv3fVRDJ4MMwEA" crossorigin="anonymous">
  <script type="text/javascript" src="https://h.online-metrix.net/fp/tags.js?org_id=k8vif92e&session_id=visanetgt_lau<?php echo $AUTO2;?>"></script>
</head>
<body>
<noscript>
    <iframe style="width: 100px; height: 100px; border: 0; position:absolute; top: -5000px;" 
    src="https://h.online-metrix.net/fp/tags?org_id=k8vif92e&session_id=visanetgt_lau<?php echo $AUTO2;?>"></iframe>
  </noscript>
  <form id="payment" class="container" action="#" method="post">  
    <h3>Pago en línea</h3>                      
    <fieldset>
      <div class="form-group">
        <label for="exampleInputEmail1">Correo electrónico</label>
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Correo electrónico" name="email">
      </div>
      <input type="hidden" name="auto" value="<?php echo $AUTO2; ?>">
      <div class="row form-group">
        <div class="col-lg-6">
          <label>Nombre(s)</label>
          <input type="text" class="form-control" placeholder="Nombre(s)" name="firstName">
        </div>
        <div class="col-lg-6">
          <label>Apellido(s)</label>
          <input type="text" class="form-control" placeholder="Apellido(s)" name="lastName">
        </div>
      </div>
      <div class="row form-group">
        <div class="col-lg-6">
          <label>NIT *si aplica</label>
          <input type="text" class="form-control" placeholder="NIT" name="nit">
        </div>
        <div class="col-lg-6">
          <label>Dirección</label>
          <input type="text" class="form-control" placeholder="Dirección" name="address">
        </div>
      </div>
      <div class="form-group">
        <label>Ciudad</label>
        <input type="text" class="form-control" placeholder="Ciudad" name="city">
      </div>
      <hr>
      <div class="row form-group">
        <div class="col-lg-6">
          <label>Número de Tarjeta</label>
          <input type="text" class="form-control" placeholder="Número de tarjeta sin espacios ni guiones" name="cardNumber">
        </div>
        <div class="col-lg-6">
          <label>CVV</label>
          <input type="text" class="form-control" placeholder="CVV" name="cardCVV">
        </div>
        <div class="col-lg-3">
          <label>Mes</label>
          <select name="expirationMonth" class="form-control">
            <?php foreach ($months as $month) {
                echo '<option value="'.$month.'">'.$month.'</option>';
            }?>
          </select>
          <small>Mes de expiración</small>
        </div>
        <div class="col-lg-3">
          <label>Año</label>
          <select name="expirationYear" class="form-control">
            <?php for  ($i = date('Y'); $i <= date('Y') + 10; $i++) {
              echo '<option value="'.$i.'">'.$i.'</option>';
            }?>
          </select>
          <small>Año de expiración</small>
        </div>
        <div class="col-lg-6">
          <label>Selecciona plan</label>
          <select name="amount" class="form-control" id="amount">
            <option value="198.50">Inicial - USD 198.50</option>
            <option value="314.50">Doble - USD 314.50</option>
            <option value="414.50">Pro - USD 414.50</option>
            <option value="464.50">Élite - USD 464.50</option>
            <option value="0.5">Elite 2 - USD 0.5</option>
            <option value="99.25">Inicial - USD 99.25 (2 pagos quincenales)</option>
            <option value="165.00">Doble - USD 165.00 (2 pagos mensuales)</option>
            <option value="149.99">Pro - USD 149.99 (3 pagos mensuales)</option>
            <option value="169.99">Élite - USD 169.99 (3 pagos mensuales)</option>
            <option value="0.99">Élite 2 - USD 0.99 (3 pagos mensuales)</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Pagar</button>
    </fieldset>
    <?php if ($reply) { ?>
    <?php if ($reply->reasonCode == 100){ ?>
    <div class="alert alert-dismissible alert-success" id="success" style="margin-top: 30px">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <span>Tu pago se ha realizado correctamente</span>
    </div>
    <?php } else {?>
    <div class="alert alert-dismissible alert-danger" id="error" style="margin-top: 30px">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <!--<span>Error: <?php print_r($reply); ?></span>-->
      <span>Error: El pago no pudo ser completado, por favor intenta nuevamente.</span>
    </div>
    <?php } ?>
    <?php } ?>
    <?php $_POST = null; ?>
  </form>
</body>
</html>