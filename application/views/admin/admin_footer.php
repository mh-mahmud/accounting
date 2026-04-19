            <hr>
            <footer>
                <div>
                    <div class="pull-left"><p>&copy; Base4 Technologies</p></div>
                    <div class="pull-right"><p>Powred By: <a target="_blank" href="http://base4bd.com">Base4 Technologies</a></p></div>
                </div>
            </footer>
        </div>
        <!--/.fluid-container-->
        <link href="<?php echo $url_prefix; ?>global/admin/vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/vendors/chosen.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">

        <script src="<?php echo $url_prefix; ?>global/admin/vendors/jquery-1.9.1.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/datatables/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/jquery.uniform.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/chosen.jquery.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/bootstrap-datepicker.js"></script>

        <script src="<?php echo $url_prefix; ?>global/admin/vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="<?php echo $url_prefix; ?>global/admin/vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

        <script type="text/javascript" src="<?php echo $url_prefix; ?>global/admin/vendors/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/assets/form-validation.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/easypiechart/jquery.easy-pie-chart.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/jGrowl/jquery.jgrowl.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/assets/scripts.js"></script>
        <script src="<?php echo $url_prefix; ?>global/admin/assets/DT_bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo $url_prefix; ?>global/admin/assets/functions.js?v=1.0"></script>
        
        <script>
        $(function() {
            // Easy pie charts
            $('.chart').easyPieChart({animate: 1000});
        });
        </script>

        <script>
        $(function() {
            $('.tooltip').tooltip();    
            $('.tooltip-left').tooltip({ placement: 'left' });  
            $('.tooltip-right').tooltip({ placement: 'right' });    
            $('.tooltip-top').tooltip({ placement: 'top' });    
            $('.tooltip-bottom').tooltip({ placement: 'bottom' });

            $('.popover-left').popover({placement: 'left', trigger: 'hover'});
            $('.popover-right').popover({placement: 'right', trigger: 'hover'});
            $('.popover-top').popover({placement: 'top', trigger: 'hover'});
            $('.popover-bottom').popover({placement: 'bottom', trigger: 'hover'});

            $('.notification').click(function() {
                var $id = $(this).attr('id');
                switch($id) {
                    case 'notification-sticky':
                        $.jGrowl("Stick this!", { sticky: true });
                    break;

                    case 'notification-header':
                        $.jGrowl("A message with a header", { header: 'Important' });
                    break;

                    default:
                        $.jGrowl("Hello world!");
                    break;
                }
            });
        });
        </script>

        <script>

        jQuery(document).ready(function() {   
           FormValidation.init();
        });
    

        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });
        </script>

    </body>

</html>