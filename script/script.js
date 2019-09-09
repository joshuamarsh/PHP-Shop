function searchitem(){	
	var searchtxt = $("input[name='search']").val(); 
	if(searchtxt.length > 0){
		$.post("search.php", {itemsearch: searchtxt}, function(data) {
			document.getElementById("itemsuggest").innerHTML = data;
			document.getElementById("itemsuggest").style = "display: block;";
		});
	}else{
			document.getElementById("itemsuggest").style = "display: none;";
	}
}

$(document).ready(function () {
    $("#itemsuggest").on("click", "li", function(event){
        var searchitem = $.trim($(this).text());
    	$('#searchbox').val(searchitem);
    	document.forms["searchform"].submit();
    	document.getElementById("itemsuggest").style = "display: none;";
    });
});


$(function() {
    $("#sortitem, #itemsperpage").on("change", function() {
        $(this).closest("form").submit();
    });
    
    $("#products form").on("submit", function(e) {
        itemdata = $(this).serializeArray();
        $.ajax({
            type: "GET",
            url: "updatebasket.php",
            data: itemdata,
            success: function(data) {
                var cart = JSON.parse(data);
                $('#minibasketviewheader').html(
                    '<span style="color: black;">Total Price: </span><span id="baskettotal" class="baskettotal">&pound; '+cart.total+' </span><a href="checkout.php" class="checkoutbtn"><span>CHECKOUT</span></a>'
                );
                $('#basketview ul').html(cart.items);
            },
            error: function(error){
                if(error.responseText == "login"){
                    window.location.href = "/login.php?error=1";
                }
            }
        });
         e.preventDefault();
    });
    
    var buttonvalue;
    $('.miniitemremovebtn').click(function() {
          buttonvalue = $(this).attr('value');
    })
    
    $(".miniitemview form").on("submit", function(e) {
        var method = buttonvalue;
        var quantityspan = $(this).find('.miniviewamount');
        fromdata = $(this).serializeArray();
        itemean = fromdata[0].value
        $.ajax({
            type: "GET",
            url: "updatebasket.php",
            data: {
                ean: itemean,
                method: method
            },
            success: function(data) {
                var cart = JSON.parse(data);
                if(method == "removeitem"){
                    var cart = JSON.parse(data);
                    $('#minibasketviewheader').html('<span style="color: black;">Total Price: </span><span id="baskettotal" class="baskettotal">&pound; '+cart.total+' </span><a href="checkout.php" class="checkoutbtn"><span>CHECKOUT</span></a>');
                    $('#basketview ul').html(cart.items);
                }else{
                    quantityspan.text(cart.quantity);
                }
                $('#minibasketviewheader').html('<span style="color: black;">Total Price: </span><span id="baskettotal" class="baskettotal">&pound; ' + cart.total + ' </span><a href="checkout.php" class="checkoutbtn"><span>CHECKOUT</span></a>');
            }
        });
         e.preventDefault();
    });
    
    $('.cancelorderbtn').click(function() {
        var row = $(this).closest('tr');
        var orderid = $(this).attr('data-id');
        
        $.ajax({
            type: "GET",
            url: "cancelorder.php",
            data: {
                orderid: orderid 
            },
            success: function(data) {
                row.remove();
            }
        });
        
    })
});




