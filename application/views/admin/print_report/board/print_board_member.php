<?php
$url_prefix = $this->webspice->settings()->site_url_prefix;
$site_url = $this->webspice->settings()->site_url;
$domain_name = $this->webspice->settings()->domain_name;
$total_column = 16;
$report_name = 'Board Member Information';

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

    <?php if( $this->uri->segment(2)=='print'): ?>
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
            <td align="center" colspan="<?php echo $total_column; ?>">
                <div style="font-size:150%;"><?php echo $domain_name; ?></div>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr style="border-top:1px solid #ccc;">
            <td colspan="<?php echo $total_column; ?>" align="center" style="font-size:17px; font-weight:bold; color:red; text-align:center; padding:0px;"><?php echo $report_name; ?></td>
        </tr>
        <tr>
            <td colspan="<?php echo $total_column; ?>" align="center" style="text-align:center; padding:0px;">Report Date: <?php echo date("d F, Y"); ?>&nbsp;|&nbsp;<?php echo $filter_by; ?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
    </table>

    <table class="table" width="100%" border="1" cellpadding="0" cellspacing="0">
       <tr>
        <th>Member Name</th>
        <th>Member Email</th>
        <th>Member Phone</th>
        <th>Member Occopation</th>
        <th>Marriage Status</th>
        <th>Member Age</th>
        <th>Gender</th>
        <th>Member NID</th>
        <th>Education Background</th>
        <th>Session Start</th>
        <th>Session End</th>
        <th>Address</th>
        <th>Member Image</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Status</th>
      </tr>
      <?php foreach($get_record as $k=>$v): ?>
      <tr style="height:100px;">    
          <td align="center" style="vertical-align:middle"><?php echo $v->MEMBER_NAME; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->EMAIL; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->PHONE; ?></td> 
          <td align="center" style="vertical-align:middle"><?php echo $v->OCCUPATION; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->MARRIAGE_STATUS; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->AGE; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->SEX; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->VOTER_ID; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->EDUCATION_BACK; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->SESSION_START; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->SESSION_END; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->ADDRESS; ?></td>
          <td align="center" style="vertical-align:middle">
								<img src="<?php echo $this->webspice->settings()->site_url.'global/custom_files/member/'.$v->MEMBER_ID.'.jpg'; ?>"  alt="Teacher Photograph" class="img-responsive" width="100" />
					</td>
          <td align="center" style="vertical-align:middle"><?php echo $v->CREATED_BY; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $v->CREATED_DATE; ?></td>
          <td align="center" style="vertical-align:middle"><?php echo $this->webspice->static_status($v->STATUS); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
</div>
</body>
</html>