
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

(function($){
	$('.submenu').click(function() {
		$( this ).toggleClass("open");
	});
})(jQuery);


(function($){
	$.fn.serializeObject = function(){

		var self = this,
		json = {},
		push_counters = {},
		patterns = {
			"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
			"key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
			"push":     /^$/,
			"fixed":    /^\d+$/,
			"named":    /^[a-zA-Z0-9_]+$/
		};


		this.build = function(base, key, value){
			base[key] = value;
			return base;
		};

		this.push_counter = function(key){
			if(push_counters[key] === undefined){
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};

		$.each($(this).serializeArray(), function(){

                    // skip invalid keys
                    if(!patterns.validate.test(this.name)){
                    	return;
                    }

                    var k,
                    keys = this.name.match(patterns.key),
                    merge = this.value,
                    reverse_key = this.name;

                    while((k = keys.pop()) !== undefined){

                        // adjust reverse_key
                        reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                        // push
                        if(k.match(patterns.push)){
                        	merge = self.build([], self.push_counter(reverse_key), merge);
                        }

                        // fixed
                        else if(k.match(patterns.fixed)){
                        	merge = self.build([], k, merge);
                        }

                        // named
                        else if(k.match(patterns.named)){
                        	merge = self.build({}, k, merge);
                        }
                    }

                    json = $.extend(true, json, merge);
                });

		return json;
	};
})(jQuery);



