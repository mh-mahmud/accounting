<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container">
            <div class="row-fluid">
                <div class="span12" id="content">
                    <div class="row-fluid">

                        <!-- here will goes alert message -->
                        <!-- <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4>Success</h4>
                            The operation completed successfully
                        </div> -->
                        <!-- alert message end -->

                          <div class="navbar">
                              <div class="navbar-inner">
                                  <ul class="breadcrumb">
                                      <li>
                                          <b>Create Role</b>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>


                         <!-- validation -->
                        <div class="row-fluid">
                             <!-- block -->
                            <div class="block">
                                <div class="navbar navbar-inner block-header">
                                    <div class="muted pull-left">Create Role</div>
                                </div>
                                <div class="block-content collapse in">
                                            <div class="span12">
                                                <!-- BEGIN FORM-->
                                                <form id="frm_filter" method="post" action="" data-parsley-validate>
                                                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                  <input type="hidden" name="ROLE_ID" value="<?php if( isset($edit['ROLE_ID']) && $edit['ROLE_ID'] ){echo $this->webspice->encrypt_decrypt($edit['ROLE_ID'], 'encrypt');} ?>" />
                                                  
                                                  <table width="100%">
                                                    <tr>
                                                      <td>
                                                        <div class="form_label">Role Name*</div>
                                                        <div>
                                                          <input type="text"  class="input_full input_style" id="role_name" name="role_name" value="<?php echo set_value('role_name',$edit['ROLE_NAME']); ?>"  required />
                                                          <span class="fred"><?php echo form_error('role_name'); ?></span>
                                                        </div>
                                                      </td>
                                                    </tr>
                                                    
                                                    <!--permission-->
                                                    <?php if($get_permission): ?>
                                                    <tr>
                                                      <td>
                                                        <table class="table table-bordered">
                                                            <?php
                                                            $total=array();
                                                            $group_name = null;
                                                            $group_count = 0;
                                                            $is_checked = null;
                                                            $edit['PERMISSION_NAME'] ? $edited_permission = explode(',', $edit['PERMISSION_NAME']) : $edited_permission = array();
                                                            foreach($get_permission_data as $k=>$v){
                                                              $is_checked = null;

                                                              # for edit - verify that; the permission is selected before or notes_body
                                                              foreach($edited_permission as $k11=>$v11) {
                                                                if( $v11==$v->PERMISSION_NAME ){ $is_checked = ' checked="checked"'; }
                                                              }

                                                              # get new group name and count by group name
                                                              if( $v->GROUP_NAME != $group_name ){
                                                                $group_name = $v->GROUP_NAME;
                                                                $group_count = 0;
                                                                foreach($get_permission_data as $k1=>$v1){
                                                                  if($v1->GROUP_NAME == $v->GROUP_NAME){$group_count++;}
                                                                }
                                                                echo '<tr>';
                                                                  echo '<td rowspan="'.$group_count.'" class="fbold" style="vertical-align:middle;">'.ucwords(str_replace('_',' ',$group_name)).'</td>';
                                                                  echo '<td><div><input type="checkbox" name="permission[]" value="'.$v->PERMISSION_NAME.'"'.$is_checked.'/>&nbsp;'.ucwords(str_replace('_',' ',$v->MENU_NAME)).'</div></td>';
                                                                echo '</tr>';
                                                                
                                                              }elseif( $v->GROUP_NAME == $group_name ){
                                                                # create checkbox
                                                                echo '<tr><td><div><input type="checkbox" name="permission[]" value="'.$v->PERMISSION_NAME.'"'.$is_checked.' />&nbsp;'.ucwords(str_replace('_',' ',$v->MENU_NAME)).'</div></td></tr>';
                                                              }
                                                              
                                                            }     
                                                            ?>
                                                        
                                                        </table>
                                                      </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    
                                                    <tr>
                                                      <td>
                                                        <div><input type="submit" class="btn btn-success" value="Submit Data" /></div>
                                                      </td>
                                                    </tr>
                                                  </table>
                                                </form>
                                                <!-- END FORM-->
                                            </div>
                                      </div>
                                   </div>
                              <!-- /block -->
                        </div>
                         <!-- /validation -->
                    
                    
                    
                </div>
        
            </div>
            
<?php include(APPPATH."views/admin/admin_footer.php"); ?>