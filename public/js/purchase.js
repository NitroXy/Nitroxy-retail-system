var lock = false;
var wait_img = new Image();
wait_img.src="/gfx/loading.gif";
wait_img.alt="wait";

var Products = function() {
	this.initialize.apply(this, arguments);
}



Products.prototype = {
	initialize: function(productList, suggestArea, productField) {
		this.productList = this._getElement(productList);
		this.suggestArea = this._getElement(suggestArea);
		this.productField = this._getElement(productField);
		this.basket = new Object;
		this.last_product = null;
		this.sum = 0;
		this.minBasketAmount = 0;
		this.products = Array();
		this.suggestions = Array();
		this.eans = Array();
		this._addEvent(
			this.productField,
			'submit',
			this._bindEvent(this.submitProductForm)
		);
		this.hookBeforeAddProduct = function() {return true;}
		this.hookOnEmptyProduct = function() {return true;}
		this.hookOnUpdatedProductList = function() {};
		if(arguments[3]) {
			for(var i in arguments[3]) {
				this[i] = arguments[3][i];
			}
		}
	},

	addProduct: function(id, ean, name, suggest_text, count, price, suggested, value) {
		this.products[id] = {
			id: id,
			ean: ean,
			name: name,
			suggest: suggest_text,
			count: count,
			price: price,
			value: value,
		};
		if(suggested) {
			this.suggestions[id] = suggest_text;
		}
		this.eans[ean.toLowerCase()] = id;
	},

	start: function() {
		new Suggest.Local(
			this.productField,
			this.suggestArea,
			this.suggestions,
			{
				dispMax: 20,
				interval: 200,
				highlight: true,
				hookBeforeSearch: function(text) {
					return !text.match(/^[\*\+\-]\d*$/);
				}
			});
	},

	/**
	 * Searches for a product and returns the product_id or null.
	 * @param string input an input that can be an EAN, a product_id or
	 *  an identical text to a suggestion of the product.
	 * @return the product_id or null.
	 */
	getProduct: function(input) {
		if(this.eans[input] != undefined) {
			// ean of product in input	
			return this.eans[input];
		}
		if(this.products[input]!=undefined) {
			// id of product in input
			return input;
		}
		for(var product in this.products) {
			if(product != undefined && this.products[product].suggest.toLowerCase()==input) {
				// There was a suggested product with this name.
				return product;
			}
		}
		return null;
	},

	/**
	 * Update basket with the product specified in the ean field.
	 * If ean is empty and basket is non-empty, focus is shifted to
	 * the recieved field.
	 * If input was not recognized an error message is shown.
	 */
	addToBasket: function() {
		var input=this.productField.value.toLowerCase();
		var amount=0;
		var artno = null;
		var result = this.hookBeforeAddProduct(input);
		if(result == false) {
			return;
		} else if(typeof result == String) {
			input = result;
		}
		if(input=="") {
			if(this.hookOnEmptyProduct() == false) {
				return;
			}
		}
		if(this.last_product && input.match(/^[\+\*\-][0-9]+$/)) {
			var sign = input.substr(0,1);
			artno = this.last_product;
			amount = parseInt(input.substr(1));
			if(this.basket[artno] == undefined) {
				this.basket[artno] = 0;
			}
			if(sign == '*') {
				amount = amount - this.basket[artno];
			} else if(sign == '-') {
				amount = -1 * amount;
			}
		} else {
			artno = this.getProduct(input);
			amount = 1;
		}
		if(artno==null && input.match(/^\%\%/)) {
			ajax = ajax_create();
			ajax.open("POST", "/Coupon/info", false);
			ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			ajax.send('code='+input);
			if(JSON) {
				var coupon = JSON.parse(ajax.responseText);
			} else {
				var coupon = eval('(' + ajax.responseText + ')');
			}
			if(coupon.status == 'valid') {
				artno=input;
				this.addProduct(artno, input, coupon.display, null, null, coupon.value, false, null);
			} else if(coupon.status == 'error') {
				alert(coupon.error);
				return;
			} else if(coupon.status == 'plundered') {
				alert('Denna kupong har redan plundrats');
				return;
			}
		}
		if(artno==null) {
			// Input är ej art.nr eller EAN
			alert("Oväntad inmatning - ej artikelnummer eller EAN");
			return;
		}

		this.last_product = artno;
		if(this.basket[artno]==undefined) {
			this.basket[artno]=amount;
		} else {
			this.basket[artno]=this.basket[artno]+amount;
		}
		if(this.basket[artno] <= this.minBasketAmount) {
			delete this.basket[artno];
		}
		this.updateProductList();
		this.hookOnUpdatedProductList();
		this.productField.value='';
	},

	/**
	 * Redraws the product_list selection box.
	 */
	updateProductList: function() {
		this.productList.innerHTML='';

		for(var i in this.basket) {
			if(this.basket[i]!=NaN) {
				var product = this.products[i];
				var item = this.diplayProductListItem(product, this.basket[i]);
				this.productList.appendChild(item);
			}
		}
	},

	diplayProductListItem: function(product, count) {
		var item=document.createElement('li');
		item.innerHTML=product.name+" [art "+product.id+"]"+
			"<div class=\"product_price\">"+
			count+" st * "+product.price+" kr = "+count*product.price+" kr"+
			"</div>"+
			'<input type="hidden" name="product_id[]" value="'+product.id+'"/>'+
			'<input type="hidden" name="product_price[]" value="'+product.price+'"/>'+
			'<input type="hidden" name="product_count[]" value="'+count+'" />';
		return item;
	},

	_getElement: function(element) {
		return (typeof element == 'string') ?
			document.getElementById(element) :
			element;
	},
	
	_addEvent: (window.addEventListener ?
		function(element, type, func) {
			element.addEventListener(type, func, false);
		} :
		function(element, type, func) {
			element.attachEvent('on' + type, func);
		}
	),
	
	_bind: function(func) {
		var self = this;
		var args = Array.prototype.slice.call(arguments, 1);
		return function(){ func.apply(self, args); };
	},
	_bindEvent: function(func) {
		var self = this;
		var args = Array.prototype.slice.call(arguments, 1);
		return function(event){ event = event || window.event; func.apply(self, [event].concat(args)); };
	},
};

