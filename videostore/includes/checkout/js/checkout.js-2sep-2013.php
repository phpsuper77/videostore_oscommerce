/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
var Checkout = new function() {
  this.d = {
<?php 
  @require_once(CHECKOUT_WS_INCLUDES . 'languages/' . $language . '/' . FILENAME_CHECKOUT); 
  echo 'img_loading:"' . CHECKOUT_IMG_LOADING . '",';
  echo 'img_logo:"' . CHECKOUT_IMG_LOGO . '",';
  echo 'show_shipping:' . (($cart->show_weight() > 0 && strpos($cart->content_type, 'virtual') === false || !CHECKOUT_DYNAMIC_SHIPPING) ? '1' : '0') . ',';
  echo 'dynamic_shipping:' . (CHECKOUT_DYNAMIC_SHIPPING ? '1' : '0') . ',';
  echo 'dynamic_payment:' . (CHECKOUT_DYNAMIC_PAYMENT ? '1' : '0') . ',';
  echo 'file_checkout:"' . FILENAME_CHECKOUT . '",';
  echo 'file_shopping_cart:"' . tep_href_link(FILENAME_REAL_SHOPPING_CART, '', 'SSL') . '",';
  echo 'checkout_ws_includes:"' . CHECKOUT_WS_INCLUDES . '",';
  echo 'checkout_tip_shipping:"' . str_replace('"', '\"', CHECKOUT_TIP_SHIPPING) . '",';
  echo 'checkout_verify_coupon:"' . str_replace('"', '\"', CHECKOUT_VERIFY_COUPON) . '",';
  echo 'checkout_coupon_failure:"' . str_replace('"', '\"', CHECKOUT_COUPON_FAILURE) . '",';
  echo 'checkout_item:"' . str_replace('"', '\"', CHECKOUT_ITEM) . '",';
  echo 'checkout_item_name:"' . str_replace('"', '\"', CHECKOUT_ITEM_NAME) . '",';
  echo 'checkout_qty:"' . str_replace('"', '\"', CHECKOUT_QTY) . '",';
  echo 'checkout_unit_price:"' . str_replace('"', '\"', CHECKOUT_UNIT_PRICE) . '",';
  echo 'checkout_total_price:"' . str_replace('"', '\"', CHECKOUT_TOTAL_PRICE) . '",';
  echo 'checkout_remove:"' . str_replace('"', '\"', CHECKOUT_REMOVE) . '",';
  echo 'checkout_error_submitted:"' . str_replace('"', '\"', CHECKOUT_ERROR_SUBMITTED) . '",';
  echo 'checkout_email_address_invalid:"' . str_replace('"', '\"', CHECKOUT_EMAIL_ADDRESS_INVALID) . '",';
  echo 'remove_product:"' . str_replace('"', '\"', CHECKOUT_REMOVE_PRODUCT). '",';
  echo 'cart_images:"' . (CHECKOUT_CART_IMAGES ? '1' : '0') . '",';
  echo 'is_logged_in:' . (tep_session_is_registered('customer_id') ? '1' : '0') . ',';
  echo 'contrib_referral:' . (CHECKOUT_CONTRIB_REFERRAL ? '1' : '0') . ',';
  echo 'generate_password:' . (CHECKOUT_GENERATE_PASSWORD ? '1' : '0') . ',';
  echo 'req_email:\'' . CHECKOUT_REQUIRE_EMAIL . '\',';
  echo 'req_company:\'' . CHECKOUT_REQUIRE_COMPANY . '\',';
  echo 'req_gender:\'' . CHECKOUT_REQUIRE_GENDER . '\',';
  echo 'req_dob:\'' . CHECKOUT_REQUIRE_DOB . '\',';
  echo 'req_suburb:\'' . CHECKOUT_REQUIRE_SUBURB . '\',';
  echo 'req_state:\'' . CHECKOUT_REQUIRE_STATE . '\',';
  echo 'req_telephone:\'' . CHECKOUT_REQUIRE_TELEPHONE . '\',';
  echo 'req_postcode:\'' . CHECKOUT_REQUIRE_POSTCODE . '\',';
  echo 'req_newsletter:\'' . CHECKOUT_REQUIRE_NEWSLETTER . '\',';
  echo 'accept_terms:"' . (CHECKOUT_CONTRIB_TERMS_CONDITIONS ? '1' : '0') . '"';
?>
  };
  this.submitted = false;
  this.error = false;
  this.dialog = '';
  this.timeoutShipping = false;
  this.loadingBar = '<div class="checkout-loading"><img src="'+this.d.img_loading+'" /></div>';
  this.smlLoadingBar = '<img src="'+this.d.img_loading+'" />';
  this.checkoutEnabled = false;
  this.shippingLoaded = false;
  this.orderTotalsLoaded = false;
  this.initialized = false;
  this.checkoutCompleted = false;
  this.errorOccurred = false;
  this.loadingTimer;
    
  this.ToggleShipping = function() {
    try {
      if (this.ShipSame()) {
        $("#checkout-shipping").css("display", "none");
        if ($('#bill_state').length > 0) {
          if ($('#bill_state')[0].type == 'select-one')
            $('#bill_state').bind('change', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
          else if ($('#bill_state')[0].type == 'text')
            $('#bill_state').bind('keyup', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
        }
        if ($('#bill_country')[0].type == 'select-one')
          $('#bill_country').bind('change', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
          
        $('input#bill_postcode').bind('keyup', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
        $('#ship_state').unbind();
        $('#ship_country').unbind();
        
        $('input#ship_postcode').unbind('keyup');
      } else {
        $("#checkout-shipping").css("display", "block");
        
        if ($('#ship_state').length > 0) {
          if ($('#ship_state')[0].type == 'select-one')
            $('#ship_state').bind('change', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
          else if ($('#ship_state')[0].type == 'text')
            $('#ship_state').bind('keyup', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
        }
        if ($('#ship_country')[0].type == 'select-one')
          $('#ship_country').bind('change', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
          
        $('input#ship_postcode').bind('keyup', function(e) { Checkout.TimeoutGetPaymentShipping(); } );
        $('input#bill_postcode').unbind('keyup');
        $('#bill_state').unbind();
        $('#bill_country').unbind();
      }
    } catch(e) {}
    this.GetPaymentShipping();
  }
  
  this.GetAddress = function() {
    var address = new Array();

    address['bfn'] = $('input#bill_firstname').val();
    address['bln'] = $('input#bill_lastname').val();
    address['ba1'] = $('input#bill_street_address').val();
    address['ba2'] = $('input#bill_suburb').val();
    address['bc'] = $('input#bill_city').val();
    address['bs'] = ($('#bill_state').length > 0) ? $('#bill_state').val() : '';
    address['bpc'] = $('input#bill_postcode').val();
    address['bcn'] = $('#bill_country').val();
    
    if (!this.ShipSame()) {
      address['sfn'] = $('input#ship_firstname').val();
      address['sln'] = $('input#ship_lastname').val();
      address['sa1'] = $('input#ship_street_address').val();
      address['sa2'] = $('input#ship_suburb').val();
      address['sc'] = $('input#ship_city').val();
      address['ss'] = ($('#ship_state').length > 0) ? $('#ship_state').val() : '';     
      address['spc'] = $('input#ship_postcode').val();
      address['scn'] = $('#ship_country').val();
    } else {
      address['sfn'] = address['bfn'];
      address['sln'] = address['bln'];
      address['sa1'] = address['ba1'];
      address['sa2'] = address['ba2'];
      address['sc'] = address['bc'];
      address['ss'] = address['bs'];
      address['spc'] = address['bpc'];
      address['scn'] = address['bcn'];
    }
    
    address['shipsame'] = this.ShipSame();
    
    return address;
  }
  
  this.TimeoutGetPaymentShipping = function() {
    if (Checkout.d.show_shipping) {
      clearTimeout(this.timeoutShipping);
      this.timeoutShipping = setTimeout('Checkout.GetPaymentShipping();', 1000);
    }
    return false;
  }
 
  this.UpdateOrderTotal = function() {
    var address = this.GetAddress();

    var shipping = $("input[name=shipping]:checked").val();
    if (typeof shipping == 'undefined') shipping = '';
    
    var payment = $("input[name=payment]:checked").val();
    if (typeof payment == 'undefined') payment = '';
    
    var options = {
      x: "order_total", 
      fn:address['sfn'],
      ln:address['sln'],
      a1:address['sa1'],
      a2:address['sa2'],
      c:address['sc'],
      s:address['ss'],
      pc:address['spc'],
      cn:address['scn'],
      shipping: shipping,
      payment: payment
    };
    
    if ($("input[name=qp_card]:checked").length > 0) {
      options.qp_card = $("input[name=qp_card]:checked").val();
    }

    $.post(Checkout.d.file_checkout, options,
      function(data) {
        var tbl = document.createElement('table');
        var tr,td,total;
        
        tbl.width = "100%";
        
        total = (data != null) ? data.length : 0;
        
        for (x = 0; x < total; x++) {
          tr = tbl.insertRow(x);
          td = tr.insertCell(0);
          td.className = 'main '+data[x]['code'];
          td.align = 'right';
          td.innerHTML = data[x]['title'];
          
          td = tr.insertCell(1);
          td.className = 'main '+data[x]['code'];
          td.align = 'right';
          td.innerHTML = data[x]['text'];
        }
        
        $(".checkout-order-total").html('').append(tbl);
        Checkout.orderTotalsLoaded = true;
      }, "json");
  }
  
  this.HighlightRow = function(hID, className) {
    $('.'+className).removeClass("checkout-highlight");
    $('#'+hID).addClass("checkout-highlight");
  }

  this.GetPaymentShipping = function() {

    var address = this.GetAddress();

      if (Checkout.d.dynamic_payment) $('div#checkout-payment-methods').html(this.loadingBar);
      $('div#checkout-shipping-quotes').html(this.loadingBar);

      $.post(Checkout.d.file_checkout,
       {x:"get_payment_shipping",
        bfn:address['bfn'],
        bln:address['bln'],
        ba1:address['ba1'],
        ba2:address['ba2'],
        bc:address['bc'],
        bs:address['bs'],
        bpc:address['bpc'],
        bcn:address['bcn'],
        sfn:address['sfn'],
        sln:address['sln'],
        sa1:address['sa1'],
        sa2:address['sa2'],
        sc:address['sc'],
        ss:address['ss'],
        spc:address['spc'],
        scn:address['scn']},
        function(data) {
          Checkout.shippingLoaded = true;
          //if ($('input[name=shipping]').length > 0)
          //  $('input[name=shipping]').unbind('click');
          if (typeof data == "undefined") {
            this.GetPaymentShipping();
            return false;
          }
          var shipping = data.shipping;
          var payment = data.payment;
          var shipping_total = shipping.length;
          var payment_total = payment.length;
          var tbl,tbl2,tr,td,tr2,td2,cols,radio,div,method,methods,method_safe,label,checked,highlight;
          var rows = 0;
          var rows2 = 0;
          
          if (Checkout.d.dynamic_payment == 1 && Checkout.initialized) {
            tbl = document.createElement('table');
          
            for (i = 0; i < payment_total; i++) {
              if (i > 0) {
                tr = tbl.insertRow(rows);
                rows++;
                
                td = tr.insertCell(0);
                td.colSpan = '2';
                td.className = 'checkout-spacing-1';
              }
              
              tr = tbl.insertRow(rows);
              tr.className = 'checkout-payment-row';
              tr.id = payment[i]['id'];
              rows++;
              
              td = tr.insertCell(0);
              td.className = 'main';
              td.vAlign = 'top';
              td.align = 'left';
              td.width = '10';
              
              checked = (i == 0) ? ' CHECKED' : '';
              
              if (payment_total == 1)
                td.innerHTML = '<input type="hidden" name="payment" value="'+payment[i]['id']+'" />';
              else
                td.innerHTML = '<input type="radio" name="payment" value="'+payment[i]['id']+'" onclick="Checkout.HighlightRow(\'module-row-'+payment[i]['id']+'\', \'payment-row\')"'+checked+' />';
              
              td = tr.insertCell(1);
              td.className = 'payment-row main';
              td.vAlign = 'top';
              td.align = 'left';
              td.id = 'module-row-'+payment[i]['id'];
              td.style.width = '100%';
              td.innerHTML = '<h2>'+payment[i]['module']+'</h2>';
              
              tr = tbl.insertRow(rows);
              rows++;
              td = tr.insertCell(0);
              td.colSpan = 2;
              td.style.padding = '0 0 0 30px';

              if (typeof payment[i]['fields'] != 'undefined' && payment[i]['fields'].length > 0) {
                div = document.createElement('div');
                div.id = 'module_'+payment[i]['id'];
                div.className = 'payment_module';
                tbl2 = document.createElement('table');
                tbl2.cellPadding = '2';
                rows2 = 0;
                
                for (j = 0; j < payment[i]['fields'].length; j++) {
                  tr2 = tbl2.insertRow(rows2);
                  rows2++;
                  td2 = tr2.insertCell(0);
                  td2.className = 'main';
                  td2.innerHTML = payment[i]['fields'][j]['title'];
                  
                  if (typeof payment[i]['fields'][j]['field'] != 'undefined') {
                    td2 = tr2.insertCell(1);
                    td2.className = 'main';
                    td2.innerHTML = payment[i]['fields'][j]['field'];
                  }
                }

                div.appendChild(tbl2);
                td.appendChild(div);
              }

            }
            
            $('#checkout-payment-methods').html('').append(tbl);
            
            $('.checkout-payment-row').bind('click', 
              function(e) {
                var id = $(this).attr('id');
                Checkout.HighlightRow('module-row-'+id, 'payment-row');
                Checkout.SelectRowEffect($(this)[0], 'pay', id);
                Checkout.UpdateOrderTotal();
              }
            );
            $('.payment-row').bind('click', 
              function(e) {
                var id = $(this).attr('id');
                $('input#'+id).attr('checked', 'checked');
                Checkout.HighlightRow('module-row-'+id, 'payment-row');
                Checkout.UpdateOrderTotal();
              }
            );

            $('input[name=payment]').bind('click', 
              function(e) {
                Checkout.UpdateOrderTotal();
              }
            );

          }
                    
          var address = Checkout.GetAddress();
          if ((address['scn'] < 1 || (address['spc'] == '' && Checkout.d.req_postcode == 'R')) && Checkout.d.dynamic_shipping == 1) {
            $("div#checkout-shipping-quotes").html(Checkout.d.checkout_tip_shipping);
          } else {
            rows = 0;
            tbl = document.createElement('table');
            
            for (i = 0; i < shipping_total; i++) {
              if (i > 0) {
                tr = tbl.insertRow(rows);
                rows++;
                
                td = tr.insertCell(0);
                td.colSpan = '2';
                td.className = 'checkout-spacing-1';
              }
            
              tr = tbl.insertRow(rows);
              tr.className = 'shipping-row';
              rows++;
              td = tr.insertCell(0);
              td.className = 'main';
              td.colSpan = '2';
              /*
              if (typeof shipping[i]['icon'] != 'undefined' && shipping[i]['icon'] != '')
                td.innerHTML = shipping[i]['icon']+'&nbsp;<h2>'+shipping[i]['module']+'</h2>';
              else
              */
              if (typeof shipping[i]['module'] != 'undefined') {
                td.innerHTML = '<h2>'+shipping[i]['module']+'</h2>';
              }
              
              if (shipping[i]['error'] != '') {
                tr = tbl.insertRow(rows);
                rows++;
                td = tr.insertCell(0);
                td.className = 'main';
                td.colSpan = '2';
                td.innerHTML = shipping[i]['error'];
              } else {
                methods = shipping[i]['methods'].length;
              
                for (j = 0; j < methods; j++) {
                  tr = tbl.insertRow(rows);
                  rows++;
                  
                  method = shipping[i]['id']+'_'+shipping[i]['methods'][j]['id'];
                  
                  td = tr.insertCell(0);    
                  td.className = 'main';
                  td.colSpan = '1';
                  td.width = '20';
                  td.vAlign = 'top';

                  checked = '';
                  highlight = '';
                  
                  if (shipping[i]['methods'][j]['cheapest'] == "1") {
                    checked = ' checked="checked"';
                    highlight = ' checkout-highlight';
                  }

                  method_safe = method.replace(/[^A-Za-z0-9_\- ]/g, "");
                  method_safe = method_safe.replace(/ /g, "_");

                  td.innerHTML = '<input type="radio" name="shipping" id="'+method_safe+'" value="'+method+'"'+checked+' />';
                  
                  td = tr.insertCell(1);
                  td.className = 'main';
                  td.innerHTML = '<div class="shipping-row'+highlight+'" id="module-row-'+method_safe+'"><div class="title">'+shipping[i]['methods'][j]['title']+'</div><div class="cost">'+shipping[i]['methods'][j]['cost']+'</div>';
                }
              }
            }

            $('#checkout-shipping-quotes').html('').append(tbl);
            
            $('input[name=shipping]').bind('click', 
              function(e) {
                Checkout.HighlightRow('module-row-'+$(this).attr('id'), 'shipping-row')
                Checkout.UpdateOrderTotal();
              }
            );
            $('.shipping-row').bind('click', 
              function(e) {
                var id = $(this).attr('id');
                id = id.replace('module-row-', '');
                $('input#'+id).attr('checked', 'checked');
                Checkout.HighlightRow('module-row-'+id, 'shipping-row')
                Checkout.UpdateOrderTotal();
              }
            );
          }
          Checkout.UpdateOrderTotal();
        }, "json");

    if ((address['scn'] < 1 || (address['spc'] == '' && Checkout.d.req_postcode == 'R')) && Checkout.d.dynamic_shipping == 1) {
      $('div#checkout-shipping-quotes').html(Checkout.d.checkout_tip_shipping);
    }
  }
  this.ShipSame = function() {
    if (!$("input#checkout-ship-same").attr("checked"))
      return true;
    else
      return false;
  }
  this.SelectRowEffect = function(object, type, buttonSelect) {
    var o = $(object);
    var f = document.checkout;
    if (type == 'ship') {
      $(".moduleShipRowSelected").removeClass().addClass('moduleShipRow');
      o.removeClass().addClass('moduleShipRowSelected');
      this.UpdateOrderTotal();
    } else {
      $(".moduleBillRowSelected").removeClass().addClass('moduleBillRow');
      o.removeClass().addClass('moduleBillRowSelected');

      if ($(f.payment).length > 1) {
        $("div").filter(".payment_module").slideUp('slow').end().filter("div#module_"+buttonSelect).slideDown('slow');
        $("input[name='payment']").filter("[value='"+buttonSelect+"']").attr("checked", "checked");
      } else {
        $("input[name='payment']").attr("checked", "checked");
      }
      $('div#ERROR_payment').slideUp('slow').html('');
    }
  }

  this.RowOverEffect = function(object) {
    if ($(object).attr('class') == 'moduleShipRow')
      $(object).removeClass().addClass('moduleShipRowOver');
    else if ($(object).attr('class') == 'moduleBillRow') 
      $(object).removeClass().addClass('moduleBillRowOver');
  }
  this.RowOutEffect = function(object) {
    if ($(object).attr('class') == 'moduleShipRowOver')
      $(object).removeClass().addClass('moduleShipRow');
    else if ($(object).attr('class') == 'moduleBillRowOver') 
      $(object).removeClass().addClass('moduleBillRow');
  }
  this.UpdateZones = function(type) {
    $.post(Checkout.d.file_checkout,
      { x: "get_zones",
        c: $("select#"+type+"_country").val(),
        n: type+"_state"},
      function (data) {
        var html = '';
        
        if (data.zones.length > 1) {
          $("div#"+type+"_state_blk").html(
            '<select name="'+type+'_state" class="checkout-select" id="'+type+'_state"></select>'
          );
          
          $.each(data.zones, function(i, n) {
            $("#"+type+"_state").append(
              '<option value="'+n.id+'">'+n.text+'</option>'
            );
          });
          
          $("#"+type+"_state").bind("change", function() {
            Checkout.GetPaymentShipping();
          });
        } else {
          $("div#"+type+"_state_blk").html(
            '<input type="text" name="'+type+'_state" class="checkout-input-text" id="'+type+'_state" />'
          );
          
          $("#"+type+"_state").bind("keyup", function() {
            Checkout.TimeoutGetPaymentShipping();
          });
        }
      }, 'json');
  }
  this.ApplyPoints = function(status) {
    $.post(Checkout.d.file_checkout,
      { x: "apply_points",
        s: status ? 1 : 0
      },
      function(data) {
        Checkout.UpdateOrderTotal();
      }
    , 'json');
  }
  this.ApplyGiftWrap = function() {
    var selected = $('input[name=giftwrap]:checked').val();
    if (typeof selected == 'undefined') return false;

    $.post(Checkout.d.file_checkout,
      { x: "apply_giftwrap",
        gw: selected
      },
      function(data) {
        Checkout.UpdateOrderTotal();
      }
    , 'json');
  }
  this.ApplyCoupon = function() {
    var code = $('input#checkout-coupon').val();
    
    if (code == '') return;
    
    $('div#checkout-coupon-status').html(Checkout.d.checkout_verify_coupon+'<br />'+this.smlLoadingBar);
    
    $.post(Checkout.d.file_checkout,
      { x: "apply_coupon", 
        coupon: code,
        shipping: $('input[name=shipping]:checked').val()
      },
      function(data){
        var status;
        
        if (data != '') {

          var j = data;

          try {
            if (j.status == "1") {
              status = 'success';
            } else {
              status = 'failure';
            }
          } catch(e) {
            status = 'failure';
            j = {"text":Checkout.d.checkout_coupon_failure};
          }

          $('div#checkout-coupon-status').html('<span class="checkout-msg-'+status+'">'+j['text']+'</span>');
          
          Checkout.UpdateOrderTotal();
        } else {
          $('div#checkout-coupon-status').html('');
        }
      }, "json");
  }
  this.RemoveCoupon = function() {
    $('input#checkout-coupon').val('');

    $('div#checkout-coupon-status').html(this.smlLoadingBar);
    
    $.post(Checkout.d.file_checkout,
      { x: "remove_coupon" },
      function() {
        $('div#checkout-coupon-status').html('');
        Checkout.UpdateOrderTotal();
      }, 'json');
  }
  this.CheckInput = function(n, s, m) {
    var o = $(document.checkout.elements[n]);
    if (o && o.attr('type') != "hidden") {
      var v = $.trim(o.val());
      if (v == '' || v.length < s) {
        o.addClass("checkout-input-error");
        $('div#ERROR_'+n).show().html(m);
        this.error = true;
      } else {
        o.removeClass("checkout-input-error");
        $('div#ERROR_'+n).hide().html('');
      }
    }
  }
  this.CheckRadio = function(field_name, message) {
    if ($("input[name='"+field_name+"']").is(":checked")) {
      $('div#ERROR_'+field_name).hide().html('');
    } else {
      $('div#ERROR_'+field_name).show().html(message);
      this.error = true;
    }
  }
  this.CheckSelect = function(field_name, field_default, message) {
    var o = $(document.checkout.elements[field_name]);
    if (o && o.attr('type') != "hidden") {
      var v = $.trim(o.val());

      if (v == field_default) {
        o.addClass("textbox input_error");
        $('div#ERROR_'+field_name).show().html(message);
        this.error = true;
      } else {
        o.removeClass("input_error");
        $('div#ERROR_'+field_name).hide().html('');
      }
    }
  }
  
  this.CheckLuhn10 = function(cc) {
    cc = $.trim(cc);
    if (cc.length > 19 || cc == '')
      return false;

    sum = 0; mul = 1; l = cc.length;
    for (i = 0; i < l; i++) {
      digit = cc.substring(l-i-1,l-i);
      tproduct = parseInt(digit ,10)*mul;
      if (tproduct >= 10)
        sum += (tproduct % 10) + 1;
      else
        sum += tproduct;
        
      if (mul == 1)
        mul++;
      else
        mul--;
    }
    if ((sum % 10) == 0)
      return true;
    else
      return false;
  }

  this.UpdateCart = function(cart) {
    if (typeof(cart) != 'object') return false;
    
    if (cart['cart'].length < 1) {
      window.location.href = Checkout.d.file_shopping_cart;
      return false;
    }
    
    var UpperCart = document.createElement('Table');
    var LowerCart = document.createElement('Table');
    var total = cart['cart'].length;
    var UpperTr,LowerTr,td,cols,pname,aname,hvals,InnerTable,iTR,iTD;
    var UpperRows = 0, LowerRows = 0;
    
    UpperCart.className = 'productListing';
    UpperCart.border = '0';
    UpperCart.width = "100%";
    UpperCart.cellSpacing = "1";
    UpperCart.cellPadding = "2";
    
    LowerCart.cellSpacing = "0";
    LowerCart.cellPadding = "2";
    LowerCart.width = "100%";
    
    UpperTr = UpperCart.insertRow(UpperRows);
    UpperRows++;
    
    td = UpperTr.insertCell(0);
    td.className = 'productListing-heading';
    td.width = '120';
    td.innerHTML = Checkout.d.checkout_item;
    
    td = UpperTr.insertCell(1);
    td.className = 'productListing-heading';
    td.innerHTML = Checkout.d.checkout_item_name;
    
    td = UpperTr.insertCell(2);
    td.className = 'productListing-heading';
    td.width = '40';
    td.innerHTML = Checkout.d.checkout_qty;
    
    td = UpperTr.insertCell(3);
    td.className = 'productListing-heading';
    td.width = '80';
    td.innerHTML = Checkout.d.checkout_unit_price;

    td = UpperTr.insertCell(4);
    td.className = 'productListing-heading';
    td.width = '80';
    td.innerHTML = Checkout.d.checkout_total_price;
    
    td = UpperTr.insertCell(5);
    td.className = 'productListing-heading';
    td.width = '20';
    td.innerHTML = '';
    var iCnt;
    
    for (i = 0; i < total; i++) {
      iCnt = 0;
      
      UpperTr = UpperCart.insertRow(UpperRows);
      UpperRows++;
      
      LowerTr = LowerCart.insertRow(LowerRows);
      LowerRows++;
      
      if ((i/2) == Math.floor(i/2))
        UpperTr.className = 'productListing-odd';
      else
        UpperTr.className = 'productListing-even';
        
      td = UpperTr.insertCell(0);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      td.innerHTML = '<b>'+cart['cart'][i]['model']+'</b>';
      td = UpperTr.insertCell(1);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      
      InnerTable = document.createElement('Table');

      iTR = InnerTable.insertRow(0);

      if (Checkout.d.cart_images == "1") {
        iTD = iTR.insertCell(iCnt);
        iCnt++;
        iTD.className = 'productListing-data';
        iTD.innerHTML = '<a href="'+cart['cart'][i]['link']+'"><img src="'+cart['cart'][i]['image']+'" height="80" border="0" /></a>';
      }
      
      iTD = iTR.insertCell(iCnt);
      iTD.className = 'productListing-data';
      iTD.vAlign = 'middle';
      pname = '<a href="'+cart['cart'][i]['link']+'"><b>'+cart['cart'][i]['name']+'</b></a>';
      hvals = '';
      
      if (cart['cart'][i]['attributes'].length > 0) {
        for (j=0; j<cart['cart'][i]['attributes'].length; j++) {
          pname += '<br><small><i> - '+cart['cart'][i]['attributes'][j]['option_name']+' '+cart['cart'][i]['attributes'][j]['value_name']+'</i></small>';
          
          <?php if (CHECKOUT_CONTRIB_OPTIONS_TYPE) { ?>
          aname = 'id['+cart['cart'][i]['id']+'+++'+j+']['+cart['cart'][i]['attributes'][j]['option_id']+']';
          <?php } else { ?>
          aname = 'id['+cart['cart'][i]['id']+']['+cart['cart'][i]['attributes'][j]['option_id']+']';
          <?php } ?>
          hvals += '<input type="hidden" name="'+aname+'" value="'+cart['cart'][i]['attributes'][j]['value_id']+'">';
        }
      }
      
      iTD.innerHTML = pname+hvals;
      
      td.appendChild(InnerTable);
      
      td = UpperTr.insertCell(2);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      td.innerHTML = '<input type="text" name="cart_quantity[]" value="'+cart['cart'][i]['qty']+'" size="4"><input type="hidden" name="products_id[]" value="'+cart['cart'][i]['id']+'">';
<?php if (CHECKOUT_CONTRIB_GET_1_FREE === true) { ?>
      td.innerHTML += '<input type="hidden" name="free[]" value="'+cart['cart'][i]['free']+'">';
<?php } ?>
      td = UpperTr.insertCell(3);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      td.innerHTML = '<b>'+cart['cart'][i]['unit_price']+'</b>';
      td = UpperTr.insertCell(4);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      td.innerHTML = '<b>'+cart['cart'][i]['final_price']+'</b>';
      td = UpperTr.insertCell(5);
      td.className = 'productListing-data';
      td.vAlign = 'middle';
      td.align = 'center';
      td.innerHTML = '<a href="javascript:void(0);" onclick="Checkout.RemoveProduct(\''+cart['cart'][i]['id']+'\')"><img src="'+Checkout.d.checkout_ws_includes+'images/cross.gif" border="0" alt="'+Checkout.d.checkout_remove+'" title="'+Checkout.d.checkout_remove+'" /></a>';
      
      td = LowerTr.insertCell(0);
      td.className = 'main';
      td.vAlign = 'top';
      td.align = 'right';
      td.width = '30';
      td.innerHTML = cart['cart'][i]['qty']+' x';
      
      td = LowerTr.insertCell(1);
      td.className = 'main';
      td.align = 'left';
      td.width = '';
      td.innerHTML = pname;
      
      td = LowerTr.insertCell(2);
      td.className = 'main';
      td.align = 'right';
      td.width = '80';
      td.innerHTML = cart['cart'][i]['final_price'];
    }

    $('#checkout-shopping-cart').html('').append(UpperCart);
    $('#checkout-lower-shopping-cart').html('').append(LowerCart);
    
    this.GetPaymentShipping();
  }
  this.RemoveProduct = function(id) {
    if (confirm(this.d.remove_product)) {
      $('#checkout-shopping-cart').html(this.loadingBar);
      $.post(Checkout.d.file_checkout, {x:'remove_product', id:id}, function(d) { Checkout.UpdateCart(d); }, "json");
    }
  }
  this.ValidateEmail = function(email) {
    if (typeof email == 'undefined') 
      return false;
    
    if (email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
      return true;
    else
      return false;
  }
  this.SetStatus = function(lvl, msg) {
    $('#fancybox-wrap .progress').css('width', lvl);
    $('#fancybox-wrap .progress_status').text(msg);
  }
  this.ProcessOrder = function() {
    var scrollTo = '';

    this.error = false;

    $('div#ERROR_payment').html('').hide();

    if (!Checkout.d.is_logged_in && Checkout.d.req_email == 'R') {
      var email = $('input[name=email_address]');
      if (!this.ValidateEmail(email.val())) {
        email.addClass("checkout-input-error");
        $('div#ERROR_email_address').show().html(Checkout.d.checkout_email_address_invalid);
        this.error = true;
      } else {
        $('div#ERROR_email_address').hide().html('');
        email.removeClass("checkout-input-error");
      }
    }

    if (Checkout.d.req_telephone == 'R')
      this.CheckInput("telephone", <?php echo (int)ENTRY_TELEPHONE_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_TELEPHONE_NUMBER_ERROR'); ?>");  
    if (!Checkout.d.is_logged_in && !Checkout.d.generate_password)
      this.CheckInput("password", <?php echo (int)ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_PASSWORD_ERROR'); ?>");
    
    if (this.error && scrollTo == '')
      scrollTo = 'checkout-step-1';
      
    this.CheckInput("bill_firstname", <?php echo (int)ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_FIRST_NAME_ERROR'); ?>");
    this.CheckInput("bill_lastname", <?php echo (int)ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_LAST_NAME_ERROR'); ?>");
    this.CheckInput("bill_street_address", <?php echo (int)ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_STREET_ADDRESS_ERROR'); ?>");
    this.CheckInput("bill_city", <?php echo (int)ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_CITY_ERROR'); ?>");
    if (Checkout.d.req_state == 'R') {
      if ($("#bill_state").attr("type") == "text") {
        this.CheckInput("bill_state", <?php echo (int)ENTRY_STATE_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_STATE_ERROR'); ?>");
      } else {
        this.CheckSelect("bill_state", "", "<?php echo $checkout->safeJS('ENTRY_STATE_ERROR'); ?>");
      }
    }
    
    if ($("#bill_country")[0].type == 'select-one')
      this.CheckSelect("bill_country", "", "<?php echo $checkout->safeJS('ENTRY_COUNTRY_ERROR'); ?>");
      
    if (Checkout.d.req_postcode == 'R')
      this.CheckInput("bill_postcode", <?php echo (int)ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_POST_CODE_ERROR'); ?>");
    
    if (!this.ShipSame()) {
      this.CheckInput("ship_firstname", <?php echo (int)ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_FIRST_NAME_ERROR'); ?>");
      this.CheckInput("ship_lastname", <?php echo (int)ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_LAST_NAME_ERROR'); ?>");
      this.CheckInput("ship_street_address", <?php echo (int)ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_STREET_ADDRESS_ERROR'); ?>");
      this.CheckInput("ship_city", <?php echo (int)ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_CITY_ERROR'); ?>");
      
      if (Checkout.d.req_state == 'R') {
        if ($("#ship_state").attr("type") == "text")
          this.CheckInput("ship_state", <?php echo (int)ENTRY_STATE_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_STATE_ERROR'); ?>");
        else
          this.CheckSelect("ship_state", "", "<?php echo $checkout->safeJS('ENTRY_STATE_ERROR'); ?>");
      }
      
      if ($("#ship_country")[0].type == 'select-one')
        this.CheckSelect("ship_country", "", "<?php echo $checkout->safeJS('ENTRY_COUNTRY_ERROR'); ?>");
      
      if (Checkout.d.req_postcode == 'R')      
        this.CheckInput("ship_postcode", <?php echo (int)ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo $checkout->safeJS('ENTRY_POST_CODE_ERROR'); ?>");
    }
    
    if (this.error && scrollTo == '')
      scrollTo = 'checkout-step-2';

    if ($('input[name=payment]').val() == '') {
      $('div#ERROR_payment').show().html("<?php echo $checkout->safeJS('ENTRY_PAYMENT_ERROR'); ?>");
      this.error = true;
    } else {
      if ($("input[name=payment]:radio").length > 0) {
        var payment_value = $('input[name=payment]:checked').val();
      } else {
        var payment_value = $('input[name=payment]').val();
      }
      
      var error_message = '';
      var error = 0;
      
  <?php 
    /* Directly call each module's javascript_validation function */

    foreach ($selections as $selection) { 
      if (method_exists($GLOBALS[$selection['id']], 'javascript_validation')) {
        echo 'try {' . "\n";
        $javascript = explode("\n", $GLOBALS[$selection['id']]->javascript_validation());
        if (count($javascript) > 0) {
          $line_count = count($javascript);
          
          echo 'eval(';
          for ($x = 0; $x < $line_count; $x++) {
            
            $line = trim(str_replace('\\', '\\\\', $javascript[$x]));
            $line = trim(str_replace('"', '\"', $line));
            
            if ($line != '') {
              if ($x > 0) echo '+';
              echo '"' . $line . "\"\n";
            }
          }
          echo ');';
        }
        
        echo '} catch(e) {}' . "\n";
      }
    }
  ?>
  
      if (error && error_message != '') {
        this.error = true;
        $('div#ERROR_payment').show().html(error_message.replace("\n", "<br />"));
      } else {
        $('div#ERROR_payment').html('').hide();
      }
    }
    
    if (this.error && scrollTo == '')
      scrollTo = 'checkout-step-3';
      
    if (Checkout.d.show_shipping) {
      this.CheckRadio("shipping", "<?php echo CHECKOUT_SHIPPING_ERROR; ?>");
    
      if (this.error && scrollTo == '')
        scrollTo = 'checkout-step-4';
    }

    if (Checkout.d.accept_terms == '1') {
      if ($('input[name=accept_terms]').is(':checked')) {
        $('div#ERROR_accept_terms').html('').hide();
      } else {
        $('div#ERROR_accept_terms').show().html('<?php echo str_replace("'", "\'", CHECKOUT_ACCEPT_TERMS_ERROR); ?>');
        this.error = true;
        scrollTo = 'checkout-step-6';
      }
    }
/*
    if (!Checkout.d.is_logged_in && Checkout.d.contrib_referral) {
      this.CheckSelect("source", '', "<?php echo $checkout->safeJS('ENTRY_SOURCE_ERROR'); ?>");
      
      if (this.error && scrollTo == '')
        scrollTo = 'checkout-step-6';
    }
*/
    if (this.error == true) {
      window.location.hash = scrollTo;
      return false;
    } else {
      this.submitted = true;
      window.location.hash = "checkout";
      window.scroll(0,0);
      
      return true;
    }
  }
  this.ReSubmit = function() {
    Checkout.submitted = false;
    document.checkout.submit();
  };
  this.SendError = function(error, close) {
    if (typeof(close) == "undefined" || close != true) close = false;
    
    Checkout.errorOccurred = true;
    Checkout.submitted = false;
    error = '<h2><?php echo CHECKOUT_ERROR_PROCESSING_TITLE; ?></h2>'+error;
    if (close) {
      error += '<div class="fancybox-close-container"><a href="#" class="checkout-lightbox-close">'+
              '<img src="<?php echo DIR_WS_LANGUAGES . $language; ?>/images/buttons/button_back.gif" border="0"></a>';
    }
    $("#fancybox-wrap .checkout-process-content").html(error);
    $.fancybox.resize();
  }
  this.LoadingComplete = function() {
    if (Checkout.submitted && !Checkout.checkoutCompleted && !Checkout.errorOccurred) {
      var error = '';
      error  = "Customer experienced an unspecified error (probably a PHP fatal error) during the checkout process.\n";
      try {
        error += $('#checkout-gateway').contents()[0].documentElement.innerHTML;
      } catch (e) {}
      
      Checkout.LogError(error);
      
      var dialog;

      Checkout.SendError('<?php echo CHECKOUT_ERROR_PROCESSING_MSG; ?>');
      
      //setTimeout("window.location.href='checkout_shipping.php';", 5000);
    }
  }
  this.Location = function(url) {
    window.location.href = url;
  }
  this.LogError = function(error) {
    
    var x = navigator;
    var state;
    
    var user_info = '\nBrowser:\t' + x.appName
                   +'\nMinorVersion:\t' + x.appMinorVersion
                   +'\nUA:\t<?php echo $_SERVER['HTTP_USER_AGENT']; ?>'
                   +'\nCookieEnabled:\t' + x.cookieEnabled
                   +'\nCPUClass:\t' + x.cpuClass
                   +'\nPlatform:\t' + x.platform
                   +'\nLanguage:\t<?php echo str_replace("'", "\'", $_SERVER['HTTP_ACCEPT_LANGUAGE']); ?>'
                   +'\nBill City:\t' + $('input[name=bill_city]').val();
                   
    if ($("select#bill_state").attr("type") == "text")
      user_info += '\nBill State:\t' + $('input#bill_state').val();
    else
      user_info += '\nBill State:\t' + $('select#bill_state option:selected').text()+' ('+$('select#bill_state').val()+')';
      
    user_info += '\nBill ZIP:\t' + $('input[name=bill_postcode]').val()
                +'\nBill Country:\t' + $('select[name=bill_country] option:selected').text()+' ('+$('select[name=bill_country]').val()+')';
                   
    if (!this.ShipSame()) {

      user_info += '\nShip City:\t' + $('input[name=ship_city]').val();
      
      if ($("select#ship_state").attr("type") == "text")
        user_info += '\nShip State:\t' + $('input#ship_state').val();
      else
        user_info += '\nShip State:\t' + $('select#ship_state option:selected').text()+' ('+$('select#ship_state').val()+')';

      user_info += '\nShip ZIP:\t' + $('input[name=ship_postcode]').val()
                  +'\nShip Country:\t' + $('select[name=ship_country] option:selected').text()+' ('+$('select[name=ship_country]').val()+')';
    }
                   
    user_info += '\nPayment:       \t';
    <?php if (count($selections) > 1) { ?>
    user_info += $('input[name=payment]:checked').val();
    <?php } else { ?>
    user_info += $('input[name=payment]').val();
    <?php } ?>
    <?php if ($cart->show_weight() > 0) { ?>
    user_info += '\nShipping:       \t'+$('input[name=shipping]:checked').val();
    <?php } ?>
    
    if (typeof error != 'undefined' && error != '')
      user_info = user_info + '\n' + error;

    $.post(Checkout.d.file_checkout, 
            {x:"log_error",error:user_info}, 
            function() {
              if (!Checkout.shippingLoaded) {
                Checkout.GetPaymentShipping();
              } else {
                window.location.href = 'checkout_shipping.php';
              }
            }
          , 'json');
    
    
    return true;
  }
  this.ErrorHandler = function(desc,page,line,chr)  {
    var error = '';
    
    if (typeof desc != 'undefined' && desc != '')
      error += '\nError description: \t'+desc;
      
    if (typeof page != 'undefined' && page != '')
      error += '\nPage address:      \t'+page;

    if (typeof line != 'undefined' && line != '')
      error += '\nLine number:       \t'+line;
      
    if (typeof chr != 'undefined' && chr != '')
      error += '\nCharacter:       \t'+chr;
      
    if (error != '')
      error += '\n';

    Checkout.LogError(error);
    return true
  }
  this.Accordion = function(id) {
    $(id+' div').hide();
    $("input[name='payment']:checked").parent().parent().find('div').show();
    $(id+' li a.payment-title').click(function() {
      var checkElement = $(this).next();
      if((checkElement.is('div')) && (checkElement.is(':visible'))) return false;

      if((checkElement.is('div')) && (!checkElement.is(':visible'))) {
        $(id+' div:visible').slideUp('normal');
        checkElement.slideDown('normal');
        $("a#"+$(this).attr("id")+" > input").attr("checked", "checked");
        return true;
      }
    });
    
    return false;
  }
  this.BindGateway = function() {
    $('#checkout-gateway').bind('load', function() { 
      Checkout.LoadingComplete(); 
    });
    
    return false;
  }
  this.Init = function() {
    var checkoutHTML;
    clearTimeout(this.loadingTimer);
    
    window.selectRowEffect = Checkout.SelectRowEffect;
    window.rowOverEffect = Checkout.RowOverEffect;
    window.rowOutEffect = Checkout.RowOutEffect;
    
    document.checkout_payment = document.forms['checkout'];

    $('body').prepend(
      '<iframe id="checkout-gateway" name="checkout-gateway" src="'+
      Checkout.d.file_checkout+'?blank=1" frameborder="0"></iframe>'
    );

    Checkout.BindGateway();
    
    $('.checkout-payment-row').bind('click', 
      function(e) {
        var id = $(this).attr('id');
        Checkout.HighlightRow('module-row-'+id, 'payment-row');
        Checkout.SelectRowEffect($(this)[0], 'pay', id);
        Checkout.UpdateOrderTotal();
      }
    );
    
    $('.checkout-payment-methods li').bind('click', 
      function(e) {
        Checkout.UpdateOrderTotal();
      }
    );

    $('input[name=payment]').bind('click', 
      function(e) {
        Checkout.UpdateOrderTotal();
      }
    );

    if (Checkout.d.dynamic_shipping == '0') {
      $('input[name=shipping]').bind('click', 
        function(e) {
          Checkout.UpdateOrderTotal();
        }
      );
    }
    
    $("a.checkout-lightbox-login").fancybox({
      modal: true
    });
    
    $("a.checkout-lightbox-link").fancybox({
      modal: true
    });
    
    $("a#checkout-process-link").click(function() {
      if (Checkout.ProcessOrder()) {
        $(".checkout-first-name").text($('input#bill_firstname').val());
        $.fancybox({
          content: $('#checkout-process').html(),
          onStart: function() {
            Checkout.SetStatus(0, '<?php echo str_replace("'", "\'", CHECKOUT_PROCESSING_ORDER); ?>');
          },
          onComplete: function() {
            $("form[name='checkout']").submit();
          }
        });
      }
      
      return false;
    });
    
    
    $("a.checkout-lightbox-close").live('click', function() {
      $.fancybox.close();
      return false;
    });
    
    Checkout.ToggleShipping();
    
<?php 


  if ($messageStack->size('checkout_payment') > 0) { 
    $error = str_replace('"', '\"', str_replace("\n", "", $messageStack->output('checkout_payment')));
    $messageStack->reset();
?>
    $('div#ERROR_payment').show().html("<?php echo $error; ?>");
<?php
  }
?>    
    if ($('#checkout-payment-methods ul li').length > 1)
      Checkout.Accordion('#checkout-payment-methods ul');
    
    Checkout.initialized = true;
  }
}

window.onerror = Checkout.ErrorHandler;

$(document).ajaxError(function() {
  var error;
  
  if (typeof(arguments) != 'undefined' && arguments.length >= 3) {     
    error  = 'URL: ' + arguments[2].url + '\n';
    error += 'Params: ' + arguments[2].data + '\n';
    error += 'ReadyState: ' + arguments[1].readyState + '\n';
    error += 'ResponseXML: ' + arguments[1].responseXML + '\n';
    error += 'ResponseText: ' + arguments[1].responseText + '\n';
    error += 'Status: ' + arguments[1].status + '\n';
    error += 'StatusText: ' + arguments[1].statusText + '\n';
  } else {
    error = "Bad AJAX Response";
  }
  throw new Error(error);
});

$(document).ready(function(){Checkout.Init();});

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=500,screenX=150,screenY=30,top=30,left=150')
}