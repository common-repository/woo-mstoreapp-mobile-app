<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/admin
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Build {



    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

  public function mstoreapp_mobile_app_menu() {

     add_submenu_page('mstoreapp-mobile-app', __('Build App', 'mstoreapp_app'),
            __('Build App', 'mstoreapp_app'), 'activate_plugins', 'mstoreapp_app_block_build', array(&$this, 'Buildapp'));

     // add_meta_box('buildapp_form_meta_box', 'Build App Settings', array(&$this, 'buildapp_form_meta_box_handler'), 'buildapp', 'normal','default');

      //add_meta_box('appbuild_form_meta_box', 'App Build', array(&$this, 'appbuild_form_meta_box_handler'), 'appbuild', 'normal','default');

  }

  function Buildapp(){

    $status == '';

    $fields = array();
    $opt = get_option('mstore_settings');

    $address = $opt['site_url'];
    $companyName = $opt['app_name'];
    $licenceKey = $opt['key'];
    $logo_image = $opt['logo_image'];
    $splash_image = $opt['splash_image'];
    $icon_image = $opt['icon_image'];
    $fields['address'] = $opt['site_url'];
    $fields['companyName'] = $opt['app_name'];
    $fields['licenceKey'] = $opt['key'];

    if(!empty($companyName) && !empty($licenceKey) && !empty($address)){

      if ($_REQUEST['submit']) {

          $fields['address'] = $opt['site_url'];
          $fields['companyName'] = $opt['app_name'];
          $fields['licenceKey'] = $opt['key'];
          $fields['orderId'] = $_REQUEST['orderId'];
          $fields['customerId'] = $_REQUEST['customerId'];
          $fields['productId'] = $_REQUEST['productId'];
          $fields['headerColor'] = $_REQUEST['headerColor'];
          $fields['buttonColor'] = $_REQUEST['buttonColor'];
          $productId = $fields['productId'];
          $orderId = $fields['orderId'];
          $customerId = $fields['customerId'];
          

          $args = array(
            'body' => $fields,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
            'headers' => array(
              'Content-Type: application/json'
            )
          );

          $response = wp_remote_post( 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/createOrder', $args );

          $order_data = json_decode(wp_remote_retrieve_body( $response ));

          $status = 'order_created';

      }

      else {

          $args = array(
            'body' => $fields,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
            'headers' => array(
              'Content-Type: application/json'
            )
          );

          $response = wp_remote_post( 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/fillDetails', $args );

          $order_data = json_decode(wp_remote_retrieve_body( $response ));

          $productId = $order_data->productId;
          $orderId = $order_data->orderId;
          $customerId = $order_data->customerId;
          $logo_image = $order_data->logoURL;
          $icon_image = $order_data->splashURL;
          $splash_image = $order_data->iconURL;
          $headerColor = $order_data->headerColor;
          $buttonColor = $order_data->buttonColor;

          $opt['logo_image'] = $order_data->logoURL;
          $opt['splash_image'] = $order_data->splashURL;
          $opt['icon_image'] = $order_data->iconURL;

          update_option('mstore_settings', $opt);

          $status = 'fill_details';

      }


    ?>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>


     <script>

        $(document).ready(function(){
        $("#post-body-content1").hide();
        $("#submit2").prop("disabled",true); 
        $(".loader").hide();
        $("#hs").hide();
        $("#utable").hide();
        $(".loader1").hide();
        $("#hs1").hide();
        $("#utable1").hide();
        $(".loader2").hide();
        $("#hs2").hide();
        $("#utable2").hide();
        $("#msg").hide();
        $("#msg3").hide();
        $("#msg2").hide();
        $("#submit2").hide();
        $("#hr").hide();
              
        });


        $(function () {
            $("#submit1").click( function (e) {
            $("#msg").text("Please wait ! this will take some time....");
            $("#submit1").prop("disabled",true);
                var formData1 = new FormData();
                    formData1.append("orderId", orderId.value);
                    formData1.append("customerId", customerId.value);
                    formData1.append("productId", productId.value);
                $.ajax({
                    url: 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/buildAndroid',
                    type:"POST",
                    processData:false,
                    contentType: false,
                    data: formData1,
                    cache:false,
                complete: function(){
                    $("#msg").text("Your Android App has been built. Please Download below.");
                    $("#msg").css("color", "#ffffff");
                    $("#msg").css("background-color", "#008000");
                    $("#submit2").show();
                    $("#submit1").hide();
                    $("#submit2").prop("disabled",false);
                }
                });
                e.preventDefault();
                });
             });



        $(function () {
        $("#submit2").click( function (e) {
            window.open('https://mainkart.com:8443/mstoreapp-showcase/wordpress/downloadAndroid?customerId=' + customerId.value + '&productId=' + productId.value + '&orderId=' + orderId.value);
        });
        });

    </script>
 
            

     

<h2><?php _e('Build Settings Mstore-App')?> </h2>

    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
 

        <tr class="form-build">
            <th valign="top" scope="row">
                <label for="logo"><?php _e('Upload Logo ', 'buildapp')?></label>
            </th>
            <td>
        <style>
                #fileSelector1 {
                            background: #006799;width:150px;height:40px;color:#fff;
                }

                .loader {
                          border:7px solid #f3f3f3;
                          border-radius: 50%;
                          border-top: 7px solid #3498db;
                          width: 50px;
                          height: 50px;
                          -webkit-animation: spin 2s linear infinite; /* Safari */
                          animation: spin 2s linear infinite;
                }

                @-webkit-keyframes spin {
                      0% { -webkit-transform: rotate(0deg); }
                      100% { -webkit-transform: rotate(360deg); }
                }

                @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
                }

                #logopreview{
                max-height: 340px;max-width: 138px;padding: 5px;margin-bottom: 0;margin-top: 10px;margin-left: 15px;border: 1px solid #e3e3e3;background: #f7f7f7;-moz-border-radius: 3px;-khtml-border-radius: 3px;-webkit-border-radius: 3px;width:100px;
            }

        </style>
                     
            <img  id="logopreview" src="<?php echo $logo_image?>" style="width: 100px; height: 100px;"><br>
                <label id="logoUploadlbl" style="width: 164px;height: 40px;vertical-align: top;" >Uplaod Logo</label>
                            <table border="2px" id="utable" style="border: 1px solid #e3e3e3;max-height: 340px;max-width: 138px">
                            <td>
                            <div class="loader"></div>
                            <h2 id="hs" style="color:#006799">Uploading Logo...</h2></td> 
                            </table>     
                        <input type="file" id="logoUpload" accept="image/*" onchange="logoimage(this)">    
                    <span id="logospan" style="margin-left: 35px;"></span><br>
                <span id="logospan1"></span>
            <span id="logospan2"></span>

            <script>
                var fileUpload1 = document.getElementById("logoUpload"),
                uploadLabel = document.getElementById("logoUploadlbl"),
                fileInsert1 = document.createElement("button");
                fileInsert1.id = "fileSelector1";
                fileInsert1.innerHTML = uploadLabel.innerHTML;
                fileUpload1.parentNode.insertBefore(fileInsert1, fileUpload1.nextSibling);
                fileUpload1.style.display = "none";
                uploadLabel.style.display = "none";
                    fileInsert1.addEventListener('click', function(e){
                        e.preventDefault();
                        fileUpload1.click();
                    }, false);

            function logoimage(input) {
                var preview1 = document.getElementById('preview1');
                var imgForm1 = document.getElementById('filehandler1');
                    if (input.files && input.files[0]) {  
                        var reader1 = new FileReader();
                            reader1.onload = function(e) { 
                                $(".loader").show();
                                $("#hs").show();
                                $("#logopreview").hide();
                                $("#utable").show();
                            var image2 = new Image();
                            image2.src = e.target.result;
                                image2.onload=function(){
                                    var height_logo = this.height;
                                    var width_logo = this.width;
                                    if (height_logo < 192 || width_logo < 192) {
                                        var logopreview = document.getElementById('logopreview');
                                        document.getElementById("logospan").style.visibility="hidden";
                                        document.getElementById("logospan1").style.visibility="hidden";
                                        document.getElementById("logospan2").style.visibility="visible";
                                        logo.innerHTML = "<h2 style='color:red'><b>Please Upload 192 x 192 px. </b></h2>" ;
                                        logopreview.setAttribute('src', 'http://mstoreapp.com/assets/image/logo.png');
                                    return false;
                                    }
                                        document.getElementById("logospan").style.visibility="visible";
                                        document.getElementById("logospan1").style.visibility="visible";
                                        document.getElementById("logospan2").style.visibility="hidden";
                                        var logopreview = document.getElementById('logopreview');
                                        logopreview.setAttribute('src',e.target.result);
                                    return true;
                                    };
                                }
                                        reader1.readAsDataURL(input.files[0]);
                                        var fileName1 = fileUpload1.value.split('\\')[fileUpload1.value.split('\\').length - 1];
                                        var formData = new FormData();
                                        formData.append("orderId", orderId.value);
                                        formData.append("customerId", customerId.value);
                                        formData.append("productId", productId.value);
                                        formData.append("imageType", 'logo');
                                        formData.append("logo", input.files[0]);
                                $.ajax({
                                url: 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/uploadFile',
                                type:"POST",
                                processData:false,
                                contentType: false,
                                data: formData,
                                cache:false,
                                complete: function(){
                                    $(".loader").hide();
                                    $("#logopreview").show();
                                    $("#hs").hide();
                                    $("#utable").hide();
                                    logospan.innerHTML = "<b>Selected Logo image: </b>" + fileName1 ;
                                    logospan1.innerHTML = "<h2 style='color:#006799'><b>Logo Uploaded Successfully!!! </b></h2>" ;
                                    }
                                });
                    } 
                    else {
                            logopreview.setAttribute('src', '');
                        } 
                }
            </script>   
            </td>
        </tr>


        <tr class="form-build">
            <th valign="top" scope="row">
                <label for="icon"><?php _e('Upload Icon')?></label>
            </th>
            <td>
        <style>
            #fileSelector2 {
                background: #006799;width:150px;height:40px;color:#fff;
            }

             .loader2 {
                          border:7px solid #f3f3f3;
                          border-radius: 50%;
                          border-top: 7px solid #3498db;
                          width: 50px;
                          height: 50px;
                          -webkit-animation: spin 2s linear infinite; /* Safari */
                          animation: spin 2s linear infinite;
                }
                 #iconpreview{
                max-height: 340px;max-width: 138px;padding: 5px;margin-bottom: 0;margin-top: 10px;margin-left: 15px;border: 1px solid #e3e3e3;background: #f7f7f7;-moz-border-radius: 3px;-khtml-border-radius: 3px;-webkit-border-radius: 3px;width:100px;
            }

         </style>
        <img  id="iconpreview" src="<?php echo $icon_image?>" style="width: 100px; height: 100px;"><br>
             <label id="iconlabel" style="width: 164px;height: 40px;">Upload Icon 1024x1024px png</label>
               <table border="2px" id="utable2" style="border: 1px solid #e3e3e3;max-height: 340px;max-width: 138px">
                            <td>
                            <div class="loader2"></div>
                            <h2 id="hs2" style="color:#006799">Uploading Icon...</h2>
                            </td> 
                </table>
                        <input type="file" id="iconupload" accept="image/*" onchange="iconimage(this)">
                <span id="iconspan" style="margin-left: 35px;"></span><br>
           <span id="iconspan1"></span>
        <span id="iconspan2"></span>

            <script>

                var iconupload = document.getElementById("iconupload"),
                iconlabel = document.getElementById("iconlabel"),
                iconinsert = document.createElement("button");
                iconinsert.id = "fileSelector2";
                iconinsert.innerHTML = iconlabel.innerHTML;
                iconupload.parentNode.insertBefore(iconinsert, iconupload.nextSibling);
                iconupload.style.display = "none";
                iconlabel.style.display = "none";
                    iconinsert.addEventListener('click', function(e){
                        e.preventDefault();
                        iconupload.click();
                    }, false);

            function iconimage(input) {
                var icon_preview = document.getElementById('icon_preview');
                var imgForm2 = document.getElementById('filehandler2');
                    if (input.files && input.files[0]) {
                        var icon_reader = new FileReader();
                            icon_reader.onload = function(e) {
                                $(".loader2").show();
                                $("#hs2").show();
                                $("#iconpreview").hide();
                                $("#utable2").show();  
                                var im_con = new Image();
                                im_con.src = e.target.result;
                                     im_con.onload = function () {
                                        var height1 = this.height;
                                        var width1 = this.width;
                                        if (height1 > 1024 || width1 > 1024) {
                                            var iconpreview = document.getElementById('iconpreview');
                                            document.getElementById("iconspan").style.visibility="hidden";
                                            document.getElementById("iconspan1").style.visibility="hidden";
                                            document.getElementById("iconspan2").style.visibility="visible";
                                            iconspan2.innerHTML = "<h2 style='color:red'><b>Please Upload 1024 x 1024 px. </b></h2>" ;
                                            iconpreview.setAttribute('src', 'http://mstoreapp.com/assets/image/icon.png');
                                         return false;
                                        }   
                                            document.getElementById("iconspan").style.visibility="visible";
                                            document.getElementById("iconspan1").style.visibility="visible";
                                            document.getElementById("iconspan2").style.visibility="hidden";
                                            var iconpreview = document.getElementById('iconpreview');
                                            iconpreview.setAttribute('src',e.target.result);
                                        return true;
                                        };
                                    }
                                        icon_reader.readAsDataURL(input.files[0]);
                                        var icon_filename = iconupload.value.split('\\')[iconupload.value.split('\\').length - 1];
                                        var formData_icon = new FormData();
                                            formData_icon.append("orderId", orderId.value);
                                            formData_icon.append("customerId", customerId.value);
                                            formData_icon.append("productId", productId.value);
                                            formData_icon.append("imageType", 'icon');
                                            formData_icon.append("icon", input.files[0]);
                                $.ajax({
                                        url: 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/uploadFile',
                                        type:"POST",
                                        processData:false,
                                        contentType: false,
                                        data: formData_icon,
                                        cache:false,
                                    complete: function(){
                                        $(".loader2").hide();
                                        $("#iconpreview").show();
                                        $("#hs2").hide();
                                        $("#utable2").hide();
                                        iconspan.innerHTML = "<b>Selected Logo image: </b>" + icon_filename ;
                                        iconspan1.innerHTML = "<h2 style='color:#006799'><b>icon uploaded successfully!!! </b></h2>" ;
                                    }
                                });
                    } 
                    else 
                        {
                            iconpreview.setAttribute('src', '');
                        } 
            }

            </script>
            </td>
        </tr>


        <tr class="form-build">
            <th valign="top" scope="row">
                <label for="splash_image"><?php _e('Upload Splash')?></label>
            </th>
            <td>
                <style>
                    #fileSelector {
                        background: #006799;width:150px;height:40px;color:#fff;
                    }

                     .loader1 {
                                  border:7px solid #f3f3f3;
                                  border-radius: 50%;
                                  border-top: 7px solid #3498db;
                                  width: 50px;
                                  height: 50px;
                                  -webkit-animation: spin 2s linear infinite; /* Safari */
                                  animation: spin 2s linear infinite;
                }

                @-webkit-keyframes spin {
                      0% { -webkit-transform: rotate(0deg); }
                      100% { -webkit-transform: rotate(360deg); }
                }

                @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
                }

                #imgpreview{
                max-height: 340px;max-width: 138px;padding: 5px;margin-bottom: 0;margin-top: 10px;margin-left: 15px;border: 1px solid #e3e3e3;background: #f7f7f7;-moz-border-radius: 3px;-khtml-border-radius: 3px;-webkit-border-radius: 3px;width:100px;
            }
                </style>

                <img id="imgpreview" src="<?php echo $splash_image?>" style="width: 100px; height: 100px;"><br>
                    <label for="fileUpload" style="width: 164px;height: 40px;">Upload Splash 2732x2732px png</label>
                            <table border="2px" id="utable1" style="border: 1px solid #e3e3e3;max-height: 340px;max-width: 138px">
                            <td>
                            <div class="loader1"></div>
                            <h2 id="hs1" style="color:#006799">Uploading Splash...</h2></td> 
                            </table>
                            <input type="file" id="fileUpload" accept="image/*" onchange="previewImage(this)">
                        <span id="spnsplashPath" style="margin-left: 35px;"></span><br>
                     <span id="spnsplashPath1"></span>
               <span id="spnsplashPath2"></span>

            <script>

                var fileUpload = document.getElementById("fileUpload"),
                uploadLabel = document.querySelector("label[for='fileUpload']"),
                fileInsert = document.createElement("button");
                fileInsert.id = "fileSelector";
                fileInsert.innerHTML = uploadLabel.innerHTML;
                fileUpload.parentNode.insertBefore(fileInsert, fileUpload.nextSibling);
                fileUpload.style.display = "none";
                uploadLabel.style.display = "none";
                    fileInsert.addEventListener('click', function(e){
                        e.preventDefault();
                        fileUpload.click();
                    }, false);

            function previewImage(input) {
                var preview = document.getElementById('preview');
                var imgForm = document.getElementById('filehandler');
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) { 
                            $(".loader1").show();
                            $("#hs1").show();
                            $("#imgpreview").hide();
                            $("#utable1").show();
                            var image = new Image();
                            image.src = e.target.result;
                                image.onload = function () {
                                    var height = this.height;
                                    var width = this.width;
                                    if (height < 2732 || width < 2732) {
                                        var imgPreview = document.getElementById('imgpreview');
                                        document.getElementById("spnsplashPath").style.visibility="hidden";
                                        document.getElementById("spnsplashPath1").style.visibility="hidden";
                                        document.getElementById("spnsplashPath2").style.visibility="visible";
                                        spnsplashPath2.innerHTML = "<h2 style='color:red'><b>Please Upload 2732 x 2732 px. </b></h2>" ;
                                        imgPreview.setAttribute('src', 'http://mstoreapp.com/assets/image/splash.png');
                                    return false;
                                    }
                                        document.getElementById("spnsplashPath").style.visibility="visible";
                                        document.getElementById("spnsplashPath1").style.visibility="visible";
                                        document.getElementById("spnsplashPath2").style.visibility="hidden"; 
                                        var imgPreview = document.getElementById('imgpreview');
                                        imgPreview.setAttribute('src',e.target.result);
                                    return true;
                                    };
                                }
                                        reader.readAsDataURL(input.files[0]);
                                        var fileName = fileUpload.value.split('\\')[fileUpload.value.split('\\').length - 1];
                                        var formData1 = new FormData();
                                            formData1.append("orderId", orderId.value);
                                            formData1.append("customerId", customerId.value);
                                            formData1.append("productId", productId.value);
                                            formData1.append("imageType", 'splash');
                                            formData1.append("splash", input.files[0]);

                                    $.ajax({
                                            url: 'https://mainkart.com:8443/mstoreapp-showcase/wordpress/uploadFile',
                                            type:"POST",
                                            processData:false,
                                            contentType: false,
                                            data: formData1,
                                            cache:false,
                                        complete: function(response){
                                            $(".loader1").hide();
                                            $("#imgpreview").show();
                                            $("#hs1").hide();
                                            $("#utable1").hide();
                                            spnsplashPath.innerHTML = "<b>Selected Splash image: </b>" + fileName;  
                                            spnsplashPath1.innerHTML = "<h2 style='color:#006799'><b>splash_image Uploaded Successfully!!! </b></h2>" ; 
                                            }
                                        })

                        } 
                        else 
                            {
                                imgPreview.setAttribute('src', '');
                            } 
            }
            </script>
            </td>
        </tr>


                
                 
    </table> 


    <div class="wrap">   
                <div class="icon32 icon32-posts-post" id="icon-edit"><br>
                </div>
                <center><h4 id="msg3" style="  background: linear-gradient(to right, #f2f2f2  10%, #ff0000 38%);padding:10px;width:500px;color: white;"></h4></center>
                <form id="form" method="POST" enctype="multipart/form-data" >



                    <input type="hidden" id="licenceKey" name="licenceKey"  style="width: 95%;" size="50" class="code" value="<?php echo esc_attr($licenceKey)?>">

                <input type="hidden" id="companyName" name="companyName"  style="width: 95%;" size="50" class="code"  value="<?php echo esc_attr($companyName)?>">

                <input type="hidden" id="address" name="address"  style="width: 95%;" size="50" class="code" value="<?php echo esc_attr($address)?>">

                <input type="hidden" id="orderId" name="orderId"  style="width: 95%;" size="50" class="code" value="<?php echo esc_attr($orderId)?>" placeholder="<?php _e('orderId')?>" >


    
                <input type="hidden" id="customerId" name="customerId" style="width: 95%;" size="50" class="code" value="<?php echo esc_attr($customerId)?>" placeholder="<?php _e('customerId', $ch)?>" >

                <input type="hidden"  value="#f4f4f4" class="header_Color" id="headerColor" name="headerColor" onchange="myFunction1()">
                
                <input type="hidden"  class="button_color" value="#488aff" id="buttonColor"  name="buttonColor" onchange="myFunction2()"  >
    


                <input type="hidden" id="productId" name="productId"  style="width: 95%;" size="50" class="code" value="<?php echo esc_attr($productId)?>" placeholder="<?php _e('productId')?>" >

                    <div class="metabox-holder" id="poststuff">
                        <div id="post-body">
                            <div id="post-body-content">
                                <?php if($status == 'order_created'){ ?>
                                <center> <h4 id="msg" style="  background-color:#006799;padding:10px;color: white;">Your settings has been saved Successfully</h4></center>
                                <input type="submit" style="width:250px; background-color:green;" value="<?php _e('Build Android')?>" id="submit1" class="button-primary" name="submit1"> 
                                <?php } ?>

                                <?php if($status == 'fill_details'){ ?>
                                    <input type="submit" style="width:250px;" value="<?php _e('Save Settings')?>" id="submit" class="button-primary" name="submit"> 
                                <?php } ?>    
                                    <hr id="hr">
                                    
                                    <h3 id="msg2">Download your build</h3>
                                   <center> <input type="button" style="width:250px; background-color:green;" value="<?php _e('Download Android')?>" id="submit2" class="button-primary" name="submit2"></center>
                                   
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
           
    <?php

  }

  else {
  
    echo "<h2 style='color:red'>App settings are missing</h2>";

    $url = admin_url( '?page=mstore_settings_options&tab=1' );

    $link = "<a href='{$url}'>Add settings here</a>";
    echo $link;

  }





    }

    function buildapp_form_meta_box_handler(){
       
    }

}   
