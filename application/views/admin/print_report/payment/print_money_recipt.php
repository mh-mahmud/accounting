<?php
$url_prefix = $this->webspice->settings()->site_url_prefix;
$site_url = $this->webspice->settings()->site_url;
$domain_name = $this->webspice->settings()->domain_name;
$total_column = 16;
$report_name = 'Money Recipt';

# don't edit the below area (csv)
if( $this->uri->segment(2)=='csv' ){
    $file_name = strtolower(str_replace(array('_',' '),'',$report_name)).'_'.date('Y_m_d_h_i').'.xls';
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".$file_name);
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $report_name; ?></title>

    <style type="text/css">
        #printArea { width:1024px; margin:auto; }
        body, table {font-family:tahoma; font-size:13px;}
        table td { padding:8px; }
    </style>

    <?php if( $this->uri->segment(2)=='print_money_recipt'): ?>
        <script type="text/javascript" src="<?php echo $url_prefix; ?>global/js/jquery-1.9.1.js"></script>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo $url_prefix; ?>global/bootstrap_3_2/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $url_prefix; ?>global/bootstrap_3_2/css/bootstrap-theme.min.css">
        <script src="<?php echo $url_prefix; ?>global/bootstrap_3_2/js/bootstrap.min.js"></script>

        <!-- print plugin -->
        <script src="<?php echo $url_prefix; ?>global/js/jquery.jqprint.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('#printArea').jqprint();
                $('#print_icon').click(function(){
                    $('#printArea').jqprint();
                });
            });
        </script>
    <?php endif; ?>
</head>

<body>
	
<!--<a id="print_icon" href="#">Print</a>-->

<div id="printArea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" colspan="<?php //echo $total_column; ?>">
                <div style="font-size:150%;"><?php //echo $domain_name; ?></div>
            </td>
        </tr>
    </table>




<div class="">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <address>
                        <strong>Base4 School</strong>
                        <br>
                        2135 Elephent Road
                        <br>
                        Dhaka, 1200
                        <br>
                        <abbr title="Phone">P:</abbr> +8801717075522
                    </address>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                    <p>
                        <em><?php echo date("d F, Y", time()); ?></em>
                    </p>
                    <p>
                        <em><?php echo $get_record->NAME; ?></em><br />
                        <em>Class: <?php echo $get_record->CLASS_NAME; ?></em><br />
                        <em>Section: <?php echo $get_record->SECTION_NAME; ?></em><br />
                    </p>
                    <p>
                    </p>
                    <p>
                    </p>
                    <p>
                        <em>Receipt #: 34522677W</em>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="text-center">
                    <h1 style="font-family: Copperplate,Copperplate Gothic Light,fantasy;">Money Receipt</h1>
                </div>
                </span>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Payment Type</th>
                            <th>#</th>
                            <th class="text-center">Amount</th>
                            <!-- <th class="text-center">Total</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-5"><em><?php echo ucfirst($get_record->MONTH); ?></em></h4></td>
                            <td class="col-md-5" style=""><?php echo $get_record->CATEGORY_NAME; ?></td>
                            <td class="col-md-1">01</td>
                            <td class="col-md-1 text-center"><?php echo $get_record->AMOUNT; ?></td>
                        </tr>
                        <tr>
                            <td>   </td>
                            <td>   </td>
                            <td class="text-right">
                            <p>
                                <strong>Subtotal: </strong>
                            </p>
                            <p>
                                <strong>Vat: </strong>
                            </p></td>
                            <td class="text-center">
                            <p>
                                <strong><?php echo $get_record->AMOUNT; ?></strong>
                            </p>
                            <p>
                                <strong>0.00</strong>
                            </p></td>
                        </tr>
                        <tr>
                            <td>   </td>
                            <td>   </td>
                            <td class="text-right"><h4><strong>Total: </strong></h4></td>
                            <td class="text-center text-danger"><h4><strong><?php echo $get_record->AMOUNT; ?>/-</strong></h4></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-danger btn-lg btn-block">
                    &nbsp;
                </button></td>
            </div>
        </div>
    </div>
</div?


</div>
</body>
</html>